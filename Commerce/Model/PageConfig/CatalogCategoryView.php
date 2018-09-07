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

namespace Touchize\Commerce\Model\PageConfig;

use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use \Magento\Framework\View\Element\Template\Context;

class CatalogCategoryView extends NoConfig implements PageConfigInterface
{
    /**
     * @var \Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator
     */
    protected $productUrlPathGenerator;

    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Catalog\Helper\Output
     */
    protected $outputHelper;

    /**
     * @var \Touchize\Commerce\Helper\Product
     */
    protected $productHelper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_priceHelper;


    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Touchize\CommerceBanners\Model\BannerFactory
     */
    protected $bannerFactory;

    /**
     * @var \Touchize\CommerceBanners\Model\TouchareaFactory
     */
    protected $touchAreaFactory;

    /**
     * @var \Touchize\CommerceBanners\Helper\TouchArea
     */
    protected $helperTouchArea;

    public function __construct(
        ProductUrlPathGenerator $productUrlPathGenerator,
        PriceCurrencyInterface $priceCurrency,
        \Touchize\Commerce\Helper\Config $configHelper,
        \Touchize\Commerce\Helper\Data $dataHelper,
        \Magento\Catalog\Helper\Output $outputHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Touchize\Commerce\Helper\Product $productHelper,
        Context $context,
        \Touchize\CommerceBanners\Model\BannerFactory $bannerFactory,
        \Touchize\CommerceBanners\Model\TouchareaFactory $touchAreaFactory,
        \Touchize\CommerceBanners\Helper\TouchArea $helperTouchArea
    ) {

        parent::__construct($context, $registry, $configHelper);
        $this->productUrlPathGenerator = $productUrlPathGenerator;
        $this->productHelper = $productHelper;
        $this->priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
        $this->outputHelper = $outputHelper;
        $this->_priceHelper = $priceHelper;
        $this->bannerFactory = $bannerFactory;
        $this->touchAreaFactory = $touchAreaFactory;
        $this->helperTouchArea = $helperTouchArea;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $productCollection = $this->getProductCollection();
        $config = $this->productHelper->getAdaptedProductList($productCollection);

        return $config;
    }

    /**
     * @return
     */
    public function getProductCollection()
    {
        $currentCategory = $this->getCurrentCategory();
        if(!$currentCategory) {
            return [];
        }
        $collection = $currentCategory->getProductCollection()->addAttributeToSelect('*');
        $isLimit = $this->dataHelper->isLimitEnabled();
        if ($isLimit) {
            $maxCount = $this->dataHelper->getMaxItemsCount();
            $collection->setPageSize($maxCount);
        }
        return $collection;
    }

    /**
     * @return
     */
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    /**
     * @param $product
     *
     * @return array
     */
    public function getProductImages($product)
    {
        $productImages = $this->productHelper->getProductImages($product);
        return $productImages;
    }

    public function getBannersConfig()
    {
        $params = [];
        $collection = $this->getBannerCollection();
        $bannerIds = $collection->getAllIds();
        $touchAreas = $this->fetchTouchAreas($bannerIds);
        foreach($collection as $_banner) {
            $bannerId = $_banner->getId();
            $pluginData = array (
                'Id' => $bannerId,
                'Visible' =>  $_banner->getIsEnabled(),
                'UseInSlider' => false,
                'ImageUrl' => $_banner->getImageUrl(),
                'AspectRatio' => '0%',
            );
            if (isset($touchAreas[$bannerId])) {
                $pluginData['Map'] = $touchAreas[$bannerId];
            }

            $params[] = $pluginData;
        }

        return $params;
    }

    protected function getBannerCollection()
    {
        $banner = $this->bannerFactory->create();
        $collection = $banner->getCollection();
        $collection->addFieldToFilter('is_enabled',['eq' => 1]);
        $currStoreId = $this->dataHelper->getStoreId();
        $collection->addFieldToFilter('stores', [
            ['finset'=> \Touchize\Commerce\Helper\Data::ALL_STORES_ID],
            ['finset'=> $currStoreId]
        ]);

        $this->addSpecificFilters($collection);

        return $collection;
    }


    protected function fetchTouchAreas($bannerIds)
    {
        $touchAreas = [];
        $touchAreaModel = $this->touchAreaFactory->create();
        $collection = $touchAreaModel->getCollection();
        $collection->addFieldToFilter('banner_id', ['in' => $bannerIds]);
        foreach ($collection as $_touchArea) {
            $touchAreas[$_touchArea['banner_id']][] = $this->helperTouchArea->remapStoredData(
                $_touchArea->getData()
            );
        }
        return $touchAreas;
    }

    /**
     * @param $collection
     *
     * @return mixed
     */
    protected function addSpecificFilters($collection)
    {
        if ($this->getCurrentCategory()->getId()) {
            $collection->addFieldToFilter('categories',array('finset'=> array($this->getCurrentCategory()->getId())));
        }
        return $collection;
    }
}

