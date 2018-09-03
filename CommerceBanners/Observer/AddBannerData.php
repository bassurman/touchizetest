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

namespace Touchize\CommerceBanners\Observer;

class AddBannerData implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Touchize\CommerceBanners\Model\BannerStore
     */
    protected $bannerStoreModel;

    public function __construct(
        \Touchize\CommerceBanners\Model\ResourceModel\BannerStore $bannerStoreModel
    ) {
        $this->bannerStoreModel = $bannerStoreModel;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $bannerModel = $observer->getEvent()->getObject();
        $this->addStoreData($bannerModel);
    }

    protected function addStoreData($bannerModel)
    {
        if ($bannerModel->getId()) {
            $bannerModel->setData('stores', [3,4]);
            $assignedStores = $this->bannerStoreModel->getAssignedRows($bannerModel->getId());
        }
    }
}