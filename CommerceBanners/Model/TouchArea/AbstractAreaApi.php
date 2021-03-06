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

namespace Touchize\CommerceBanners\Model\TouchArea;

use Magento\Framework\Model\AbstractModel;
use Touchize\CommerceBanners\Model\TouchareaFactory;

abstract class AbstractAreaApi extends AbstractModel
{
    /**
     * @var TouchareaFactory
     */
    protected $touchAreaFactory;

    /**
     * @var \Touchize\Commerce\Helper\TouchArea
     */
    protected $touchAreaHelper;

    public function __construct(
        TouchareaFactory $touchAreaFactory,
        \Touchize\CommerceBanners\Helper\TouchArea $touchAreaHelper
    ) {
        $this->touchAreaFactory = $touchAreaFactory;
        $this->touchAreaHelper = $touchAreaHelper;
    }
}