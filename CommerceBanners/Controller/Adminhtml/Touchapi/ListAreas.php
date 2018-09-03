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

namespace Touchize\CommerceBanners\Controller\Adminhtml\Touchapi;

use Touchize\CommerceBanners\Controller\Adminhtml\Touchapi;

class ListAreas extends Touchapi
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $configData = $this->getListAreas();
        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }

    /**
     * @return array
     */
    protected function getListAreas()
    {
        $data = $this->getRequest()->getParams();

        $configData = [];
        $touchAreaModel = $this->touchareaFactory->create();
        $collection = $touchAreaModel->getCollection();
        $collection->addFieldToFilter('banner_id', $data['id_touchize_touchmap']);
        foreach ($collection as $_touchArea) {
            $configData [] = [
                'Id' =>  $_touchArea['id'],
                'CampaignId' =>  $_touchArea['banner_id'],
                'Tx' =>  $_touchArea['tx'] * 100 . '%',
                'Ty' =>  $_touchArea['ty'] * 100 . '%',
                'Width' =>  $_touchArea['width'] * 100 . '%',
                'Height' => $_touchArea['height'] * 100 . '%',
                'SearchTerm' => $_touchArea['search_term'],
                'ProductId' => $_touchArea['id_product'],
                'TaxonId' => $_touchArea['id_category'],
            ];
        }
        return $configData;
    }
}
