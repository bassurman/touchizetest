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

namespace Touchize\Commerce\Plugin\Product;

use Magento\Catalog\Model\Product;

class ListProduct
{
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $context;

    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $helper;

    /**
     * Detector constructor.
     *
     * @param \Magento\Framework\App\Http\Context $context
     * @param \Touchize\Commerce\Helper\Data      $helper
     */
    public function __construct(
        \Magento\Framework\App\Http\Context $context,
        \Touchize\Commerce\Helper\Data $helper
    ) {

        $this->context = $context;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\App\Http $subject
     */
    public function aroundGetIdentities(\Magento\Catalog\Block\Product\ListProduct $subject, $proceed)
    {
        if ($this->helper->isAllowedToView()) {
            $identities = [];
            $category = $subject->getLayer()->getCurrentCategory();
            if ($category) {
                $identities[] = Product::CACHE_PRODUCT_CATEGORY_TAG . '_' . $category->getId();
            }
            $identities[] = 'touchize_mobile';
            return $identities;
        }

        return $proceed();
    }
}