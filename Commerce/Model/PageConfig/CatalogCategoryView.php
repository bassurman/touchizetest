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
     * @var \Magento\Catalog\Model\Product\Gallery\ReadHandler
     */
    protected $_helperGallery;


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
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Catalog\Model\Product\Gallery\ReadHandler $_helperGallery,
        Context $context
    ) {

        parent::__construct($context, $registry, $configHelper);
        $this->productUrlPathGenerator = $productUrlPathGenerator;
        $this->_helperGallery = $_helperGallery;
        $this->priceCurrency = $priceCurrency;
        $this->imageHelper = $imageHelper;;
        $this->dataHelper = $dataHelper;
        $this->outputHelper = $outputHelper;
        $this->_priceHelper = $priceHelper;
    }

    public function getConfig()
    {
        $config = [];
        $productCollection = $this->getProductCollection();
        foreach ($productCollection as $_product) {
            $specialPrice = $_product->getSpecialPrice();
            $price = $_product->getFinalPrice();
            $config[] = [
                'Id' => $_product->getId(),
                'SKU' => $_product->getSku(),
                'Title' => $_product->getName(),
                'SingleVariantId' => $this->getSimpleProductId($_product),
                'Url' => $_product->getProductUrl(),
                'Price' => $price,
                'DiscountedPrice' => $specialPrice,
                'FPrice' => $this->_priceHelper->currency($price,true,false),
                'FDiscountedPrice' => $specialPrice? $this->_priceHelper->currency($specialPrice, true, false):'',
                'Images' => $this->getProductImages($_product)
            ];
        }

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
        return $currentCategory->getProductCollection()->addAttributeToSelect('*');
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getImageUrl($product, $image ,$imageId)
    {
        return  $this->imageHelper->init($product, $imageId)
            ->setImageFile($image->getFile())
            ->getUrl();
    }

    public function getProductImages($product)
    {
        $images = [];
        $this->_helperGallery->execute($product);
        $productName = $product->getName();
        $productImages = $product->getMediaGalleryImages();

        foreach ($productImages as $image) {
            $images[] =[
                'Name' => $this->getImageUrl($product, $image, 'category_page_list'),
                'UseCDN' => true,
                'Alt' => $image->getLabel()?$image->getLabel():$productName,
            ];
        }
        return $images;
    }

    protected function getSimpleProductId($product)
    {
        if ($product->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
           return $product->getId();
        }
        return null;
    }
}

