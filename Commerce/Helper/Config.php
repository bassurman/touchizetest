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

    /**
     * @var \Touchize\Commerce\Model\PageConfigInterface
     */
    protected $_configModel;

    /**
     * @var \Touchize\Commerce\Model\Configurator\TopMenu
     */
    protected $configuratorMenu;

    /**
     * @var \Touchize\Commerce\Model\Configurator\MainMenu
     */
    protected $configuratorMainMenu;

    /**
     * Config constructor.
     *
     * @param Context                                       $context
     * @param \Touchize\Commerce\Model\PageConfigFactory    $pageConfigFactory
     * @param \Touchize\Commerce\Model\Configurator\TopMenu $configuratorMenu
     * @param \Magento\Framework\View\LayoutInterface       $layout
     * @param Data                                          $dataHelper
     * @param \Magento\Store\Model\StoreManagerInterface    $storeManager
     * @param \Magento\Catalog\Model\CategoryRepository     $categoryRepository
     * @param \Magento\Framework\Registry                   $coreRegistry
     */
    public function __construct(
        Context $context,
        \Touchize\Commerce\Model\PageConfigFactory $pageConfigFactory,
        \Touchize\Commerce\Model\Configurator\TopMenu $configuratorMenu,
        \Touchize\Commerce\Model\Configurator\MainMenu $configuratorMainMenu,
        \Magento\Framework\View\LayoutInterface $layout,
        \Touchize\Commerce\Helper\Data $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->pageConfigFactory  = $pageConfigFactory;
        $this->configuratorMenu   = $configuratorMenu;
        $this->configuratorMainMenu   = $configuratorMainMenu;
        $this->layout             = $layout;
        $this->dataHelper         = $dataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->_storeManager      = $storeManager;
        $this->_coreRegistry      = $coreRegistry;
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
        $this->generateBases()
            ->fillRouter()
            ->fillEndpoints()
            ->fillBanners();
        $this->fillMainMenu()
            ->fillTopMenu()
            ->fillActiveProducts()
            ->fillCurrentProduct();

        return $this;
    }

    /**
     * @return $this
     */
    protected function generateBases()
    {
        $this->_config['Debug']              = false;
        $this->_config['ApiServer']          = $this->_urlBuilder->getUrl();
        $this->_config['MediaServer']        = $this->_urlBuilder->getUrl('media/touchizecommerce');
        $this->_config['MediaPath']          = '/';
        $this->_config['Checkout']           = [
            'Title' => __('Checkout'),
            'Url'   => $this->_urlBuilder->getUrl('checkout'),
        ];
        $this->_config['Search']             = [
            'Placeholder' => __('Search ...'),
        ];
        $this->_config['ProductRelations']   = [
            'Title' => __('Related products'),
        ];
        $this->_config['ProductTechSpec']    = [
            'Title' => __('Features'),
        ];
        $this->_config['OptionSelect']       = [
            'Title' => __('Select variation'),
        ];
        $this->_config['ProductVariants']    = [
            'Title' => __('Drag variation'),
        ];
        $this->_config['ProductDescription'] = [
            'Title' => __('Description'),
        ];

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillEndpoints()
    {
        $this->_config['Endpoints'] = [
            'Products' => [
                'Endpoint' => 'touchizecommerce/api/productlist'
            ],
            'ProductDetails' => [
                'Endpoint' => 'touchizecommerce/api/product'
            ],
            'Campaigns' => [
                'Endpoint' => 'touchizecommerce/api/banner'
            ],
            'AutoSearch' => [
                'Endpoint' => 'touchizecommerce/api/searchAutocomplete'
            ],
            'Search' => [
                'Endpoint' => 'touchizecommerce/api/search'
            ],
            'Cart' => [
                'Endpoint' => 'touchizecommerce/api/cart'
            ],
            'AddToCart' => [
                'Endpoint' => 'touchizecommerce/api/addToCart'
            ],
            'RemoveFromCart' => [
                'Endpoint' => 'touchizecommerce/api/removeFromCart'
            ],
            'Selectors' => [
                'Endpoint' => 'touchizecommerce/api/selectors',
            ],
            'Taxonomies' => [
                'Endpoint' => 'touchizecommerce/api/taxonomy',
            ],
            'Content' => [
                'Endpoint' => 'touchizecommerce/api/cmsPage',
            ],
        ];

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillRouter()
    {
        $this->_config['Router'] = [
            'SiteUrl' => $this->_urlBuilder->getBaseUrl(),
            'qs' => $this->getQueryUrl(),
            'tid' => 8,
            'pid' => $this->getCurrentProductId(),
            'page' => $this->getIdentifierCmsPage(),
            'search' => $this->getQueryString(),
        ];

        return $this;
    }

    /**
     * @return bool
     */
    public function getIdentifierCmsPage()
    {
        $cmsBlock = $this->layout->getBlock('cms_page');
        if ($cmsBlock && $this->_request->getParam('page_id')) {
            return $cmsBlock->getPage()->getId();
        }

        return false;
    }

    /**
     * @return string | bool
     */
    public function getQueryString()
    {
        return $this->_request->getParam('q');
    }

    public function getQueryUrl()
    {
        $queryString = $this->getQueryString();
        if ($queryString) {
            return $this->_urlBuilder->setQueryParam('q',$queryString)->getUrl('catalogsearch/result');
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function getCurrentProductId()
    {
        $product = $this->_coreRegistry->registry('current_product');
        if ($product && $product->getId()) {
            return $product->getId();
        }

        return false;
    }

    /**
     * @return $this
     */
    protected function fillMainMenu()
    {

        $mainMenu = $this->configuratorMainMenu->getMainMenuConfig();
        $this->_config['MainMenu'] = $mainMenu;

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
        $configModel = $this->getConfigModel();
        $params = $configModel->getConfig();
        $this->_config['StartupModules']['Content']['Params']['Template']['Blocks'][] = [
            'Module' => 'ProductList',
            'Params' => ['Model' => $params]
        ];

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillBanners()
    {
        $configModel = $this->getConfigModel();
        $params = $configModel->getBannersConfig();
        $this->_config['StartupModules']['Content']['Params']['Template']['Blocks'][] = [
            'Module' => 'Campaign',
            'Params' => ['Model' => $params]
        ];

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillCurrentProduct()
    {
        if ($this->getCurrentProductId()) {
            $configModel = $this->getConfigModel();
            $productDetails = $configModel->getProductDetails();
            $this->_config['StartupModules']['ProductDetailsPopup']['Params']['Model'] = $productDetails;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->_getRequest()->getFullActionName();
    }

    /**
     *
     */
    public function registerCurrentCategory()
    {
        $categoryId = $this->dataHelper->getDefaultCategory();
        if ($categoryId) {
            $category = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
            $this->_coreRegistry->register('current_category', $category);
        }
    }

    /**
     * @return \Touchize\Commerce\Model\PageConfigInterface
     */
    public function getConfigModel()
    {
        if (is_null($this->_configModel)) {
            $actionName         = $this->getActionName();
            $this->_configModel = $this->pageConfigFactory->getConfigModel($actionName);
        }

        return $this->_configModel;
    }
}
