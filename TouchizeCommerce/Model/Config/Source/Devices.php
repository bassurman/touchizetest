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

namespace Touchize\TouchizeCommerce\Model\Config\Source;


/**
 * @api
 * @since 100.0.2
 */
class Devices implements \Magento\Framework\Option\ArrayInterface
{
    const MOBILE_ONLY = '1';

    const TABLET_ONLY = '2';

    const BOTH_DEVICES = '3';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::BOTH_DEVICES, 'label' => __('I`d like to enable it on both smartphones and tablets')],
            ['value' => self::TABLET_ONLY, 'label' => __('I`d like to enable it only on tablets')],
            ['value' => self::MOBILE_ONLY, 'label' => __('I`d like to enable it only on smartphones')],
            ['value' => 0, 'label' => __('I`d like to disable it')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::BOTH_DEVICES => __('Both Devices'),
            self::TABLET_ONLY => __('Table'),
            self::MOBILE_ONLY => __('Mobile')
        ];
    }
}