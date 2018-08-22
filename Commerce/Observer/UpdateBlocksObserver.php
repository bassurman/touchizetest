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


namespace Touchize\Commerce\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class UpdateBlocksObserver  implements ObserverInterface
{
    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $helper;

    public function __construct(
        \Touchize\Commerce\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        if ($this->helper->isAllowedToView()) {
            $pageSturcture = $observer->getEvent()->getLayout()->getReaderContext()->getPageConfigStructure();
            $pageSturcture->removeAssets('mage/requirejs/mixins.js');
            $pageSturcture->removeAssets('requirejs/require.js');
            $pageSturcture->removeAssets('mage/calendar.css');
            $pageSturcture->removeAssets('css/styles-s.css');
            $pageSturcture->removeAssets('css/styles-m.css');
            $pageSturcture->removeAssets('css/styles-l.css');
            $pageSturcture->removeAssets('css/print.css');
            $pageSturcture->removeAssets('Magento_Swatches::css/swatches.css');

        }
    }
}