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

namespace Touchize\Commerce\Model\PageConfig;

use Magento\Framework\View\Element\Template\Context;

class TouchizecommerceApiCmsPage extends NoConfig
{
    /**
     * TouchizecommerceApiCmsPage constructor.
     *
     * @param Context                                    $context
     * @param \Magento\Framework\Registry                $registry
     * @param \Touchize\Commerce\Helper\Config           $configHelper
     * @param \Magento\Cms\Model\PageFactory             $pageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Touchize\Commerce\Helper\Config $configHelper,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context, $registry, $configHelper);
        $this->_pageFactory = $pageFactory;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $pageId = $this->getPageId();
        if ($pageId) {
            $page = $this->_pageFactory->create();
            $page->setStoreId($this->_storeManager->getStore()->getId());
            $page->load($pageId);
            if ($page->getPageId()) {
                $pageConfig = [
                    'id'=>$page->getPageId(),
                    'url'=>$page->getIdentifier(),
                    'title'=>$page->getTitle(),
                    'body'=>$page->getContent(),
                ];
                return $pageConfig;
            }
        }

        return [];
    }
}

