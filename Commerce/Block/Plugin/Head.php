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
 *  @author    Touchize Sweden AB <magento@touchize.com>
 *  @copyright 2018 Touchize Sweden AB
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Touchize Sweden AB
 */

namespace Touchize\Commerce\Block\Plugin;


use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Head extends Template
{
    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $_dataHelper;

    public function __construct(
        Context $context,
        \Touchize\Commerce\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_dataHelper = $helper;
    }

    /**
     * @return string
     */
    public function getPluginUrl()
    {
        return $this->_dataHelper->getPluginUrl();
    }

    /**
     * @return string
     */
    public function getStylesUrl()
    {
        return $this->_dataHelper->getStylesUrl();
    }

    /**
     * @return string
     */
    public function getCustomHeadHtml()
    {
        return $this->_dataHelper->getCustomHeadHtml();
    }
}