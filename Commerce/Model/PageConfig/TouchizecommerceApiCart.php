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
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Cart;
use \Magento\Catalog\Api\ProductRepositoryInterface;

class TouchizecommerceApiCart extends NoConfig
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\DataObject\Factory
     */
    protected $objectFactory;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_priceHelper;

    /**
     * @var \Touchize\Commerce\Helper\Product
     */
    protected $productHelper;

    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Touchize\Commerce\Helper\Config $configHelper,
        Cart $cart,
        ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DataObject\Factory $objectFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Touchize\Commerce\Helper\Product $productHelper

    ) {

        parent::__construct($context, $registry, $configHelper);
        $this->cart = $cart;
        $this->quoteRepository = $quoteRepository;
        $this->productRepository = $productRepository;
        $this->_storeManager = $storeManager;
        $this->objectFactory = $objectFactory;
        $this->_eventManager = $context->getEventManager();
        $this->_priceHelper = $priceHelper;
        $this->productHelper = $productHelper;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $quoteTotals = $this->getCurrentQuoteData();
        $quoteItems = $this->getItemsQuoteData();

        return $quoteTotals + $quoteItems;
    }

    /**
     * @return array
     */
    protected function getCurrentQuoteData()
    {
        $quote = $this->cart->getQuote();
        $totals = $quote->collectTotals()->getTotals();

        $quoteData = [
            'Type' => 'Cart',
            'SubTotal' =>
                array (
                    'Value' => $quote->getSubtotalWithDiscount(),
                    'FValue' => $this->formatPrice($quote->getSubtotalWithDiscount()),
                    'Title' => __('Subtotal'),
                ),
            'SubTotalWithoutTax' => $quote->getSubtotalWithDiscount() - $totals['tax']->getValue(),
            'Discount' => $quote->getSubtotal()-$quote->getSubtotalWithDiscount(),
            'Shipping' =>
                array (
                    'Value' => $totals['shipping']->getValue(),
                    'FValue' => $this->formatPrice($totals['shipping']->getValue()),
                    'Title' => __('Shipping'),
                ),
            'FixedProductTax' => NULL,
            'Tax' => $totals['tax']->getValue(),
            'GrandTotal' =>
                array (
                    'Value' => $quote->getGrandTotal(),
                    'FValue' => $this->formatPrice($quote->getGrandTotal()),
                    'Title' => __('Grand total'),
                ),
            'GrandTotalWithoutTax' => $quote->getGrandTotal() - $totals['tax']->getValue(),
            'FSubTotal' => $this->formatPrice($quote->getSubtotal()),
            'FGrandTotal' => $this->formatPrice($quote->getGrandTotal()),
            'FShipping' => $this->formatPrice($totals['shipping']->getValue()),
            'FDiscount' => $this->formatPrice($quote->getSubtotal()-$quote->getSubtotalWithDiscount()),
            'FTax' => $this->formatPrice($totals['tax']->getValue()),
            'ItemsCount' => $quote->getItemsCount(),
            'ItemsQty' => $quote->getItemsQty(),
        ];
        return $quoteData;
    }

    /**
     * @return array
     */
    protected function getItemsQuoteData()
    {
        $quoteItems = $this->cart->getQuote()->getItemsCollection();
        $itemsData = [];
        if ($quoteItems) {
            foreach ($quoteItems as $_item) {
                $itemProduct = $_item->getProduct();
                if (!$_item->isDeleted()) {
                    $itemsData['Items'][] = [
                        'Id' => $_item->getId(),
                        'Title' => $_item->getName(),
                        'Qty' => $_item->getQty(),
                        'SubTotal' => $_item->getRowTotal(),
                        'ItemPrice' => $_item->getPriceInclTax(),
                        'Discount' => $_item->getDiscountAmount(),
                        'FSubTotal' => $this->formatPrice($_item->getRowTotal()),
                        'FItemPrice' => $this->formatPrice($_item->getPriceInclTax()),
                        'FDiscount' => $this->formatPrice($_item->getDiscountAmount()),
                        'ProductVariant' =>
                            array (
                                'Id' => $itemProduct->getId(),
                                'ArticleNbr' => $itemProduct->getSku(),
                                'Price' => $itemProduct->getPrice(),
                                'PriceWithoutTax' => $itemProduct->getPrice(),
                                'DiscountedPrice' => $_item->getDiscountAmount(),
                                'ProductId' => $itemProduct->getId(),
                                'FPrice' => $this->formatPrice($itemProduct->getPrice()),
                                'FDiscountedPrice' => $this->formatPrice($_item->getDiscountAmount()),
                                'Product' =>
                                    array (
                                        'Id' => $itemProduct->getId(),
                                        'Title' => $itemProduct->getName(),
                                    ),
                                'Attributes' => $this->getItemSelectedOptions($_item),
                                'Images' => $this->productHelper->getProductImages($itemProduct)
                            ),
                    ];
                }
            }
            if (isset($itemsData['Items'])) {
                rsort($itemsData['Items']);
            }
        }
        return $itemsData;
    }

    /**
     * @param $_item
     *
     * @return array
     */
    protected function getItemSelectedOptions($_item)
    {

        return []; /** TO DO **/
        $attributes = [];
        $product = $_item->getProduct();
        $parent = $product->getTypeInstance()->getParentIdsByChild($product->getId());

        if($parent->getType() != 'configurable') {
            return $attributes;
        }
        $confAttributes = $parent->getTypeInstance()->getConfigurableAttributes($parent);
        foreach ($confAttributes as $attribute) {
            $attributeCode = $attribute->getProductAttribute()->getData('attribute_code');
            $attributes[] = $product->getAttributeText($attributeCode);
        }

        return $attributes;
    }

    /**
     * @param      $amount
     * @param bool $format
     * @param bool $includeContainer
     *
     * @return float|string
     */
    protected function formatPrice($amount, $format=true, $includeContainer=false)
    {
        return $this->_priceHelper->currency($amount,$format,$includeContainer);
    }
}

