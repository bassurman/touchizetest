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

use Touchize\Commerce\Model\PageConfig\CatalogCategoryView;
use Magento\Framework\Phrase;

class CatalogProductView extends CatalogCategoryView
{
    protected $currentProduct;
    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getCurrentProduct()
    {
        if(is_null($this->currentProduct)) {
            $this->currentProduct = $this->_registry->registry('current_product');
        }
        return $this->currentProduct;
    }

    /**
     * @return \Magento\Catalog\Model\Category|\Magento\Framework\DataObject
     */
    public function getCurrentCategory()
    {
        $product = $this->getCurrentProduct();
        $category = $product->getCategory();
        if (!$category) {
            $category = $product->getCategoryCollection()->getFirstItem();
        }
        return $category;
    }

    /**
     * @return array
     */
    public function getProductDetails()
    {
        $_product = $this->getCurrentProduct();
        $productDetails = $this->productHelper->getListProductData($_product);
        $productDetailsAdditional = [
            'Description' => $this->outputHelper->productAttribute($_product,$_product->getDescription(),'description'),
            'ShortDescription' => $this->outputHelper->productAttribute($_product,$_product->getShortDescription(),'short_description'),
            'Variants' => $this->getVariantsData(),
            'VariantsSelectionText' => $this->dataHelper->getSelectionTitle(),
            'VariantsText' => $this->dataHelper->getOptionsTitle(),
            'InStock' => $_product->isInStock(),
            'Brands' => $this->outputHelper->productAttribute($_product,$_product->getManufacturer(),'manufacturer')
        ];
        $featuresInfo = $this->getFeaturesInfo();
        $productRelations = $this->getRelations();


        return $productDetails + $productDetailsAdditional + $featuresInfo + $productRelations;
    }

    /**
     * @return array
     */
    public function getVariantsData()
    {
        $variantsData = [];
        $product = $this->getCurrentProduct();

        $type = $product->getTypeId();
        if($type != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return $variantsData;
        }
        $confProductId = $product->getId();
        $typeModel = $product->getTypeInstance(true);
        $usedProductsCollection = $typeModel->getUsedProducts($product);
        $confAttributes = $typeModel->getConfigurableAttributes($product);

        if ($usedProductsCollection) {
            foreach ($usedProductsCollection as $_optionItem) {
                $attributes = [];
                foreach ($confAttributes as $attribute) {
                    $attributeCode = $attribute->getProductAttribute()->getData('attribute_code');
                    $attributes[] = $_optionItem->getAttributeText($attributeCode);
                }
                $variantsData[] = [
                    'Id' => $_optionItem->getId(),
                    'ArticleNbr' => $_optionItem->getSku(),
                    'ProductId' => $confProductId,
                    'Images' => $this->getProductImages($_optionItem),
                    'Attributes' => $attributes

                ];
            }
        }
        return $variantsData;
    }

    /**
     * @return array
     */
    public function getFeaturesInfo()
    {
        $product = $this->getCurrentProduct();
        $attributesData = [];
        $attributeValues = [];
        $attributes = $product->getAttributes();
        foreach ($attributes as $attribute) {
            if ($attribute->getIsVisibleOnFront()) {
                $value = $attribute->getFrontend()->getValue($product);

                if ($value instanceof Phrase) {
                    $value = (string)$value;
                } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                    $value = $this->priceCurrency->convertAndFormat($value);
                }

                if (is_string($value) && strlen($value)) {
                    $attributesData[] = [
                        'Name' => __($attribute->getStoreLabel()),
                        'Id' => $attribute->getAttributeCode(),
                    ];
                    $attributeValues [] = [
                        'Id' => $attribute->getAttributeCode(),
                        'AttributeId' => $attribute->getAttributeCode(),
                        'Value' => $value,
                    ];
                }
            }
        }
        $featuresInfo['AttributeSet'] = array (
            'Id' => 1,
            'Name' => $this->dataHelper->getFeaturesLabel(),
            'Attributes' => $attributesData
        );
        $featuresInfo['AttributeValues'] = $attributeValues;
        return $featuresInfo;
    }

    /**
     * @param $product
     *
     * @return array
     */
    public function getProductImages($product)
    {
        $productImages = $this->productHelper->getProductImages($product,'product_page_main_image');
        return $productImages;
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        $relationsData = [];
        $relations = [];
        $product = $this->getCurrentProduct();
        $addRelated = $this->productHelper->isEnabledRelated();
        if ($addRelated) {
            $relatedProducts = $product->getRelatedProductCollection();
            $relatedProducts->addAttributeToSelect('price');
            if ($relatedProducts->count()) {
                $relations[] = [
                    'Name'     => $addRelated = $this->productHelper->getRelatedLabel(),
                    'Products' => $this->productHelper->getAdaptedProductList($relatedProducts)
                ];
            }
        }

        $addUpsells = $this->productHelper->isEnabledUpSells();
        if ($addUpsells) {
            $upsellsProducts = $product->getUpSellProductCollection();
            $upsellsProducts->addAttributeToSelect('price');
            if ($upsellsProducts->count()) {
                $relations[] = [
                    'Name' => $addRelated = $this->productHelper->getUpSellsLabel(),
                    'Products' => $this->productHelper->getAdaptedProductList($upsellsProducts)
                ];
            }
        }

        $addCross = $this->productHelper->isEnabledCross();
        if ($addCross) {
            $crossProducts = $product->getCrossSellProductCollection();
            $crossProducts->addAttributeToSelect('price');
            if ($crossProducts->count()) {
                $relations[] = [
                    'Name' => $addRelated = $this->productHelper->getCrossLabel(),
                    'Products' => $this->productHelper->getAdaptedProductList($relatedProducts)
                ];
            }
        }

        $relationsData['Relations'] = $relations;
        return $relationsData;
    }
}

