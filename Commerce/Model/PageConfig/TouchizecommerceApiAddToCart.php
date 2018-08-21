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

use Touchize\Commerce\Model\PageConfig\TouchizecommerceApiCart;

class TouchizecommerceApiAddToCart extends TouchizecommerceApiCart
{
    /**
     * @var
     */
    protected $cart_product;

    /**
     * @return $this
     */
    public function execute()
    {
        $this->addToCartProcess();
        return $this;
    }

    /**
     * $this
     */
    protected function addToCartProcess()
    {
        $product = $this->getProduct();
        if ($product) {
            $params = $this->getParams();
            $response = $this->cart->addProduct($product, $params);
            if ($response) {
                $this->_eventManager->dispatch(
                    'checkout_cart_product_add_after',
                    ['quote_item' => $response, 'product' => $product]
                );
                $this->cart->save();
            }
        }
        return $this;
    }

    /**
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface|mixed
     */
    protected function _initProduct()
    {

        $productId = $this->getData('vid')?(int)$this->getData('vid'):(int)$this->getData('pid');
        if ($productId) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    protected function getParams()
    {
        $product = $this->getProduct();

        $requestData = [
            'qty' => $this->getData('qty')
        ];


        return $this->objectFactory->create($requestData);

    }

    protected function getProduct()
    {
        if (is_null($this->cart_product)) {
            $this->cart_product = $this->_initProduct();
        }
        return $this->cart_product;
    }
}

