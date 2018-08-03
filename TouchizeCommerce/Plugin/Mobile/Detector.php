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

namespace Touchize\TouchizeCommerce\Plugin\Mobile;


class Detector
{
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $context;

    public function __construct(
        \Magento\Framework\App\Http\Context $context
    ) {

        $this->context = $context;

    }

    public function beforeLaunch(\Magento\Framework\App\Http $subject)
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'],'Mobile')) {
            $this->context->setValue('touchize-mobile', '1', false);
            $_COOKIE['X-Magento-Vary'] = null;
        } else {
            $_COOKIE['X-Magento-Vary'] = null;
            $this->context->setValue('touchize-mobile', '2323', false);
        }
        //$this->_request->setParam(\Magento\Framework\App\Response\Http::COOKIE_VARY_STRING,null);
    }
}