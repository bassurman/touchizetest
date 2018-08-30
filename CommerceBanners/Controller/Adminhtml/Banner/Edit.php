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

namespace Touchize\CommerceBanners\Controller\Adminhtml\Banner;

use Touchize\CommerceBanners\Controller\Adminhtml\Banner;

class Edit extends Banner
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $imageId = $this->getRequest()->getParam('banner_id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Touchize_CommerceBanners::banner')
            ->addBreadcrumb(__('Manage Banners'), __('Manage Banners'))
            ->addBreadcrumb(__('Manage Banners'), __('Manage Banners'));

        if ($imageId === null) {
            $resultPage->addBreadcrumb(__('New Image'), __('New Banner'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Banner'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Image'), __('Edit Banner'));
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Banner'));
        }
        return $resultPage;
    }
}
