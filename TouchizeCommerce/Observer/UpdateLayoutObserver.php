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


namespace Touchize\TouchizeCommerce\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Result\Page;

class UpdateLayoutObserver  implements ObserverInterface
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    public function __construct(
        Context $context,
        Page $page,
        \Magento\Framework\View\Asset\GroupedCollection $assets,
        \Touchize\TouchizeCommerce\Helper\Data $helper
    ) {
        $this->layout = $context->getLayout();
        $this->page = $page;
        $this->pageAssets = $assets;
        $this->helper = $helper;
    }

    public function execute(EventObserver $observer)
    {
        if ($this->helper->isAllowedToView()) {
            $this->getLayout()->removeOutputElement('root');

            $pageConfig = $this->page->getConfig();
            $pageConfig->setPageLayout('touchize');

            $assetsCollection = $this->pageAssets->getAll();
            foreach ($assetsCollection as $item) {
                $this->pageAssets->remove($item->getUrl());
            }

            $this->getLayout()->getUpdate()->removeHandle($this->page->getDefaultLayoutHandle());
            $this->page->addHandle('touchizecommerce_index_index');
        }
    }

    protected function getLayout()
    {
        return $this->layout;
    }
}