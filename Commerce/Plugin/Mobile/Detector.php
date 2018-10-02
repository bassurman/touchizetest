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

namespace Touchize\Commerce\Plugin\Mobile;

use \Magento\Framework\Stdlib\CookieManagerInterface;

class Detector
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
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * Detector constructor.
     *
     * @param \Magento\Framework\App\Http\Context $context
     * @param \Touchize\Commerce\Helper\Data      $helper
     * @param CookieManagerInterface              $cookieManager
     */
    public function __construct(
        \Magento\Framework\App\Http\Context $context,
        \Touchize\Commerce\Helper\Data $helper,
        CookieManagerInterface $cookieManager
    ) {

        $this->context = $context;
        $this->helper = $helper;
        $this->cookieManager = $cookieManager;
    }

    /**
     * @param \Magento\Framework\App\Http $subject
     */
    public function beforeLaunch(\Magento\Framework\App\Http $subject)
    {
        $this->cookieManager->deleteCookie(\Magento\Framework\App\Response\Http::COOKIE_VARY_STRING);
        if ($this->helper->isAllowedOnDevice()) {
            $this->context->setValue('touchize-mobile', '1', false);
            $this->context->setValue('touchize-desktop', '0', false);
        } else {
            $this->context->setValue('touchize-desktop', '1', false);
            $this->context->setValue('touchize-mobile', '0', false);
        }
    }
}