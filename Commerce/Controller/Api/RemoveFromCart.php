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


namespace Touchize\Commerce\Controller\Api;

class RemoveFromCart extends \Touchize\Commerce\Controller\Api\ApiCore
{
    /**
     * @return $this
     */
    public function execute()
    {
        $data = $this->getRequestedData();

        $configModel = $this->getConfigModel();
        $configModel->setData($data);
        $configData = $configModel->execute()->getConfig();

        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }

    /**
     * @return array
     */
    protected function getRequestedData()
    {
        return [
          'item_id' => $this->getRequest()->getParam('cid'),
          'vid' => $this->getRequest()->getParam('vid'),
          'qtyInCart' => $this->getRequest()->getParam('qtyInCart'),
          'qty' => $this->getRequest()->getParam('qty'),
          'ciid' => $this->getRequest()->getParam('ciid'),
        ];
    }
}