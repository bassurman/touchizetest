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
use Touchize\Commerce\Model\Mobile\Detect;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\Product\Gallery\ReadHandler
     */
    protected $_helperGallery;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_priceHelper;

    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $dataHelper;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Detect  $deviceDetector
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\Product\Gallery\ReadHandler $_helperGallery,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Touchize\Commerce\Helper\Data $dataHelper
    ) {
        parent::__construct($context);
        $this->_helperGallery = $_helperGallery;
        $this->imageHelper = $imageHelper;
        $this->_priceHelper = $priceHelper;
        $this->dataHelper = $dataHelper;
    }

    public function getProductImages($product, $type = 'category_page_list')
    {
        $images = [];
        $this->_helperGallery->execute($product);
        $productName = $product->getName();
        $productImages = $product->getMediaGalleryImages();

        foreach ($productImages as $image) {
            $images[] =[
                'Name' => $this->getImageUrl($product, $image, $type),
                'UseCDN' => true,
                'Alt' => $image->getLabel()?$image->getLabel():$productName,
            ];
        }
        return $images;
    }

    public function getImageUrl($product, $image ,$imageId)
    {
        return  $this->imageHelper->init($product, $imageId)
            ->setImageFile($image->getFile())
            ->getUrl();
    }

    /**
     * @param $collection
     *
     * @return array
     */
    public function getAdaptedProductList($collection)
    {
        $collection->addAttributeToSelect('price');
        $config = [];
        foreach ($collection as $_product) {
            $config[] = $this->getListProductData($_product);
        }

        return $config;
    }

    /**
     * @param $product
     *
     * @return array
     */
    public function getListProductData($product)
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

    /**
     * @return string
     *
     */
    public function isEnabledRelated()
    {
        return $this->dataHelper->getConfig('touchize_commmerce_config/touchize_commmerce_category/enable_related');
    }

    /**
     * @return string
     *
     */
    public function getRelatedLabel()
    {
        return $this->dataHelper->getConfig('touchize_commmerce_config/touchize_commmerce_category/label_related');
    }

    /**
     * @return string
     *
     */
    public function isEnabledUpSells()
    {
        return $this->dataHelper->getConfig('touchize_commmerce_config/touchize_commmerce_category/enable_upsells');
    }

    /**
     * @return string
     *
     */
    public function getUpSellsLabel()
    {
        return $this->dataHelper->getConfig('touchize_commmerce_config/touchize_commmerce_category/label_upsells');
    }

    /**
     * @return string
     *
     */
    public function isEnabledCross()
    {
        return $this->dataHelper->getConfig('touchize_commmerce_config/touchize_commmerce_category/enable_cross');
    }

    /**
     * @return string
     *
     */
    public function getCrossLabel()
    {
        return $this->dataHelper->getConfig('touchize_commmerce_config/touchize_commmerce_category/label_cross');
    }



}
