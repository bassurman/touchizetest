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

use Magento\Framework\App\Helper\Context;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $_config = [];

    /**
     * @var \Touchize\Commerce\Model\PageConfigFactory
     */
    protected $pageConfigFactory;

    public function __construct(
        Context $context,
        \Touchize\Commerce\Model\PageConfigFactory $pageConfigFactory,
        \Touchize\Commerce\Model\Configurator\TopMenu $configuratorMenu
    ) {
        parent::__construct($context);
        $this->pageConfigFactory = $pageConfigFactory;
        $this->configuratorMenu = $configuratorMenu;
    }


    /**
     * @return array
     */
    public function getActiveConfig()
    {
        if (empty($this->_config)) {
            $this->generate();
        }

        return $this->_config;
    }

    /**
     * @return $this
     */
    public function generate()
    {
        $this->generateBases()->fillRouter()->fillEndpoints();
        $this->fillMainMenu()->fillTopMenu()->fillActiveProducts();
        return $this;
    }

    /**
     * @return $this
     */
    protected function generateBases()
    {
        $this->_config['Debug' ] = false;
        $this->_config['ApiServer' ] = $this->_urlBuilder->getUrl();
        $this->_config['MediaServer' ] = $this->_urlBuilder->getUrl('media/touchizecommerce');
        $this->_config['MediaPath' ] = '/';

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillEndpoints()
    {
        $this->_config['Endpoints'] = [
            'Products' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/productlist',
                ),
            'ProductDetails' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/product',
                ),
            'Campaigns' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/touchmap',
                ),
            'AutoSearch' =>
                array (
                    'Endpoint' => 'touchizecommerce/search/autocomplete',
                ),
            'Cart' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/cart',
                ),
            'Selectors' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/selectors',
                ),
            'Taxonomies' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/taxonomy',
                ),
        ];

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillRouter()
    {
        $this->_config['Router'] = [
            'SiteUrl' => $this->_urlBuilder->getUrl(),
            'qs' => '',
            'tid' => '8',
            'pid' => NULL,
            'page' => NULL,
            'search' => NULL,
        ];
        return $this;
    }

    protected function fillMainMenu()
    {
        $this->_config['MainMenu'] = [];
        return $this;
    }

    /**
     * @return $this
     */
    protected function fillTopMenu()
    {
        $this->_config['StartupModules']['TaxonomyMenu']['Params']['Model'] = $this->getTopMenuTree();
        return $this;
    }

    public function getTopMenuTree()
    {
        return $this->configuratorMenu->getTopMenuTree();
    }

    /**
     * @return $this
     */
    protected function fillActiveProducts()
    {
        $actionName = $this->getActionName();
        $configModel = $this->pageConfigFactory->getConfigModel($actionName);
        $params = $configModel->getConfig();
        $this->_config['StartupModules']['Content']['Params']['Template']['Blocks'][] = [
            'Module' => 'ProductList',
            'Params' => ['Model' => $params]
        ] ;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->_getRequest()->getFullActionName();
    }
}