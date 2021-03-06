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

namespace Touchize\Commerce\Model\ApiConfig;

use Touchize\Commerce\Model\ApiConfig\Cart;

class RemoveFromCart extends Cart
{
    const MINIMAL_QTY = 1;

    /**
     * @return $this
     */
    public function execute()
    {
        $this->removeCartProcess();
        return $this;
    }

    /**
     * $this
     */
    protected function removeCartProcess()
    {
        $itemId = $this->getItemId();
        if ($itemId) {
            $quoteItem = $this->cart->getQuote()->getItemById($itemId);
            $cartItemQty = $quoteItem->getQty();
            if ($cartItemQty > self::MINIMAL_QTY) {
                $quoteItem->setQty(--$cartItemQty);
            } else {
                $this->cart->getQuote()->removeItem($itemId);
            }
            $this->cart->save();
        }
        return $this;
    }

    /**
     * @return int
     */
    protected function getItemId()
    {
        return $this->getData('item_id');
    }
}

