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

use Touchize\Commerce\Model\PageConfig\CatalogProductView;

class TouchizecommerceApiProduct extends CatalogProductView
{
    /**
     * @return array
     */
    public function getConfig()
    {
        $_product = $this->getCurrentProduct();
        $specialPrice = $_product->getSpecialPrice();
        $price = $_product->getFinalPrice();
        $configData = [
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

        /*$configData = array (
            'Id' => '874',
            'SKU' => 'shw004',
            'Title' => 'Broadway Pump',
            'SingleVariantId' => NULL,
            'Url' => 'josie-yoga-jacket.html',
            'Price' => 410,
            'DiscountedPrice' => NULL,
            'FPrice' => '$410.00',
            'FDiscountedPrice' => NULL,
            'Images' =>
                array (
                    0 =>
                        array (
                            'Name' => 'http://touchizem1.loc/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/h/shw004a_5.jpg',
                            'UseCDN' => true,
                            'Alt' => 'Broadway Pump',
                        ),
                    1 =>
                        array (
                            'Name' => 'http://touchizem1.loc/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/h/shw004b_5.jpg',
                            'Thumb' => 'http://touchizem1.loc/media/catalog/product/cache/1/thumbnail/180x/9df78eab33525d08d6e5fb8d27136e95/s/h/shw004b_5.jpg',
                            'UseCDN' => true,
                            'Alt' => 'Broadway Pump',
                        ),
                ),
            'Description' => 'Dyed mohair upper. 3.5" heel. Leather insole and lining. Imported.',
            'ShortDescription' => 'Be after-dark chic with our contemporary mohair creation. The attention grabbing pair fits well with the season\'s trend of neutral hues.',
            'Variants' =>
                array (
                ),
            'VariantsSelectionText' => 'VariantsSelectionText',
            'VariantsText' => 'VariantsText',
            'AttributeSet' =>
                array (
                    'Id' => 1,
                    'Name' => 'Features',
                    'Attributes' =>
                        array (
                            0 =>
                                array (
                                    'Id' => 'occasion',
                                    'Name' => 'Occasion',
                                ),
                            1 =>
                                array (
                                    'Id' => 'width',
                                    'Name' => 'Width',
                                ),
                            2 =>
                                array (
                                    'Id' => 'color',
                                    'Name' => 'Color',
                                ),
                            3 =>
                                array (
                                    'Id' => 'gendered',
                                    'Name' => 'Gender',
                                ),
                        ),
                ),
            'AttributeValues' =>
                array (
                    0 =>
                        array (
                            'Id' => 'occasion',
                            'AttributeId' => 'occasion',
                            'Value' => 'Evening',
                        ),
                    1 =>
                        array (
                            'Id' => 'width',
                            'AttributeId' => 'width',
                            'Value' => 'M',
                        ),
                    2 =>
                        array (
                            'Id' => 'color',
                            'AttributeId' => 'color',
                            'Value' => 'Ivory',
                        ),
                    3 =>
                        array (
                            'Id' => 'gendered',
                            'AttributeId' => 'gendered',
                            'Value' => 'Female',
                        ),
                ),
        );*/

        return $configData;
    }
}

