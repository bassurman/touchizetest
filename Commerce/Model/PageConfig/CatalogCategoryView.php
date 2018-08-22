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

    public function __construct(
        ProductUrlPathGenerator $productUrlPathGenerator,
        PriceCurrencyInterface $priceCurrency,
        \Touchize\Commerce\Helper\Config $configHelper,
        \Touchize\Commerce\Helper\Data $dataHelper,
        \Magento\Catalog\Helper\Output $outputHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Touchize\Commerce\Helper\Product $productHelper,
        Context $context
    ) {

        parent::__construct($context, $registry, $configHelper);
        $this->productUrlPathGenerator = $productUrlPathGenerator;
        $this->productHelper = $productHelper;
        $this->priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
        $this->outputHelper = $outputHelper;
        $this->_priceHelper = $priceHelper;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $productCollection = $this->getProductCollection();
        foreach ($productCollection as $_product) {
            $config[] = $this->getListProductData($_product);
        }

        return $config;
    }

    /**
     * @param $product
     *
     * @return array
     */
    protected function getListProductData($product)
    {
        $specialPrice = $product->getSpecialPrice();
        $price = $product->getFinalPrice();
        $listConfig = [
            'Id' => $product->getId(),
            'SKU' => $product->getSku(),
            'Title' => $product->getName(),
            'SingleVariantId' => $this->getSimpleProductId($product),
            'Url' => $product->getProductUrl(),
            'Price' => $price,
            'DiscountedPrice' => $specialPrice,
            'FPrice' => $this->_priceHelper->currency($price,true,false),
            'FDiscountedPrice' => $specialPrice? $this->_priceHelper->currency($specialPrice, true, false):'',
            'Images' => $this->getProductImages($product),
            'CTA' => __('Drag to Cart')
        ];
        return $listConfig;
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
        return $currentCategory->getProductCollection()->addAttributeToSelect('*');
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

    /**
     * @param $product
     *
     * @return null|int
     */
    protected function getSimpleProductId($product)
    {
        $singleTypes = $this->dataHelper->getSingeTypes();
        if (in_array($product->getTypeId(),$singleTypes)) {
           return $product->getId();
        }
        return null;
    }
}

