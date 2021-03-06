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
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Result\Page;

class UpdateLayoutObserver  implements ObserverInterface
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $helper;

    public function __construct(
        Context $context,
        Page $page,
        \Magento\Framework\View\Asset\GroupedCollection $assets,
        \Touchize\Commerce\Helper\Data $helper,
        \Magento\Framework\App\ViewInterface $vies
    ) {
        $this->layout = $context->getLayout();
        $this->_view = $vies;
        $this->page = $page;
        $this->pageAssets = $assets;
        $this->helper = $helper;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        if ($this->helper->isAllowedToView()) {
            $this->getLayout()->getUpdate()->removeHandle($this->page->getDefaultLayoutHandle());
            $pageConfig = $this->page->getConfig();
            $pageConfig->setPageLayout('touchize');
            $this->getLayout()->removeOutputElement('root');

            $this->_view->getPage()->addHandle('touchizecommerce_index_index');
        }
    }

    /**
     * @return \Magento\Framework\View\LayoutInterface
     */
    protected function getLayout()
    {
        return $this->layout;
    }
}