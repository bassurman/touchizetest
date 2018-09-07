<?php
/**
 * 2018 Touchize Sweden AB.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to magento@touchize.com so we can send you a copy immediately.
 *
 * @author    Touchize Sweden AB <magento@touchize.com>
 * @copyright 2018 Touchize Sweden AB
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Touchize Sweden AB
 */

namespace Touchize\Commerce\Helper;

use function GuzzleHttp\default_ca_bundle;
use Magento\Framework\App\Helper\Context;
use Touchize\Commerce\Model\Mobile\Detect;
use Touchize\Commerce\Model\Config\Source\Devices;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SCRIPT_FILE_NAME = 'slq.js';

    const STYLES_FILE_NAME = 'slq.css';

    const REQUEST_TEST_PARAM = 'touchize';

    const SEARCH_QUERY_PARAM = 'q';

    const CDN_LATEST_PATH = 'latest';

    const ALL_STORES_ID = 0;
    /**
     * @var Detect
     */
    protected $deviceDetector;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Data constructor.
     *
     * @param Context                                    $context
     * @param Detect                                     $deviceDetector
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Detect $deviceDetector,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->deviceDetector = $deviceDetector;
    }

    /**
     * @return bool
     */
    public function isAllowedToView()
    {
        if ($this->_getRequest()->getParam(self::REQUEST_TEST_PARAM, false)) {
            return true;
        }

        $actionName = $this->_getRequest()->getFullActionName();
        if (!$this->isAllowedToGenerateConfig($actionName)) {
            return false;
        }

        $displayType = $this->getTypeDisplayDevices();
        switch ($displayType) {
            case Devices::MOBILE_ONLY :
                return $this->deviceIsMobile();
                break;
            case Devices::TABLET_ONLY :
                return $this->deviceisTablet();
                break;
            case Devices::BOTH_DEVICES :
                return ($this->deviceisTablet() || $this->deviceIsMobile());
                break;
            default:
                return false;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function deviceIsMobile()
    {
        return $this->deviceDetector->isMobile();
    }

    /**
     * @return bool
     */
    public function deviceisTablet()
    {
        return $this->deviceDetector->isTablet();
    }

    /**
     * @param $config_path
     *
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSelectionTitle()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/selection_title');
    }

    /**
     * @return string
     */
    public function getThemeType()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_design/theme_type');
    }

    /**
     * @return string
     */
    public function getOptionsTitle()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/options_title');
    }

    /**
     * @return string
     */
    public function getBackButtonTitle()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/back_button_title');
    }

    /**
     * @return string
     */
    public function getFeaturesLabel()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/features_label');
    }

    /**
     * @return int
     */
    public function getTypeDisplayDevices()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/display_devices');
    }

    /**
     * @return int
     */
    public function getDefaultCategory()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/default_category');
    }

    /**
     * @return int
     */
    public function isLimitEnabled()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_category/enable_limit');
    }

    /**
     * @return int
     */
    public function getMaxItemsCount()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_category/items_limit');
    }

    /**
     * @return string
     */
    public function getCustomHeadHtml()
    {
        $headHtml = $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/html_in_header');
        if ($headHtml) {
            return $headHtml;
        }
        return '';
    }

    /**
     * @return string
     */
    public function getCustomFooterHtml()
    {
        $footerHtml = $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/html_in_footer');
        if ($footerHtml) {
            return $footerHtml;
        }
        return '';
    }

    /**
     * @return string
     */
    public function getPluginUrl()
    {
        $url = $this->generatePluginUrl();

        return $url;
    }

    /**
     * @return string
     */
    public function getStylesUrl()
    {
        $cdnPath = $this->getCDNpath();
        return $cdnPath . 'css/' . self::STYLES_FILE_NAME;
    }

    /**
     * @return string
     */
    public function generatePluginUrl()
    {
        $isCdnUsed = $this->getConfig('touchize_commmerce_config/touchize_commmerce_cdn/use_cdn_for_client');
        if ($isCdnUsed) {
            $cdnPath = $this->getCDNpath();
            $pluginUrl = $cdnPath . 'js/' . self::SCRIPT_FILE_NAME;

            return $pluginUrl;
        }

        return 'https://d2kt9xhiosnf0k.cloudfront.net/touchize/latest/js/slq.js';
    }

    public function getCDNpath()
    {
        $cdnPath   = $this->getConfig('touchize_commmerce_config/touchize_commmerce_cdn/client_cdn_path');
        $themeType = $this->getThemeType();
        $cdnCode = $themeType . '/' . self::CDN_LATEST_PATH;
        return $cdnPath . '/' . $cdnCode . '/' ;
    }

    /**
     * @param $actionName
     *
     * @return bool
     */
    public function isAllowedToGenerateConfig($actionName)
    {
        $allowedActions = $this->getAllowedActions();
        if (in_array($actionName, $allowedActions)) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getConfiguredMenuItems()
    {
        $menuItems = $this->getConfig('touchize_commmerce_config/touchize_commmerce_menu/items');
        if ($menuItems) {
           return \GuzzleHttp\json_decode($menuItems, true);
        }
        return [];
    }

    /**
     * @return array
     */
    protected function getAllowedActions()
    {
        return [
            'cms_page_view',
            'cms_index_index',
            'catalog_product_view',
            'catalog_category_view',
            'catalogsearch_result_index',
        ];
    }

    /**
     * @return array
     */
    public function getSingeTypes()
    {
        return [
            \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
            \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
            \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
        ];
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
}
