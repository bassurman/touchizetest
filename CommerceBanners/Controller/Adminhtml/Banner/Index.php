<?php
/*
 * Touchize_CommerceBanners

 * @category   Touchize
 * @package    Touchize_CommerceBanners
 * @copyright  Copyright (c) 2017 Touchize

 * @version    1.0.0
 */
namespace Touchize\CommerceBanners\Controller\Adminhtml\Banner;

use Touchize\CommerceBanners\Controller\Adminhtml\Banner;

class Index extends Banner
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Touchize_CommerceBanners::banner');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Banners'));
        $resultPage->addBreadcrumb(__('Manage Banners'), __('Manage Banners'));
        return $resultPage;
    }
}
