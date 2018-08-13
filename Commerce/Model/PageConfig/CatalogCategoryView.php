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


class CatalogCategoryView extends NoConfig implements PageConfigInterface
{
    public function getConfig()
    {
        $config = [];
        $productCollection = $this->getProductCollection();
        $urlSuffix = $this->getUrlSuffix();
        foreach ($productCollection as $_product) {
            $specialPrice = $_product->getSpecialPrice();
            $price = $_product->getFinalPrice();
            $config[] = [
                'Id' => $_product->getId(),
                    'SKU' => $_product->getSku(),
                    'Title' => $_product->getName(),
                    'SingleVariantId' => $this->getSimpleProductId($_product),
                    'Url' => $this->productUrlPathGenerator->getUrlKey($_product) . $urlSuffix,
                    'Price' => $price,
                    'DiscountedPrice' => $specialPrice,
                    'FPrice' => $this->_priceHelper->currency($price,true,false),
                    'FDiscountedPrice' => $specialPrice? $this->_priceHelper->currency($specialPrice, true, false):'',
                    'Images' => $this->getProductImages($_product)
            ];
        }

        return $config;
    }

    public function getProductCollection()
    {
        $currentCategory = $this->getCurrentCategory();
        return $currentCategory->getProductCollection()->addAttributeToSelect('*');
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getUrlSuffix()
    {
        return '.html';
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

