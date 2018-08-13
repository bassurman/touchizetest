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



class Cart extends \Touchize\Commerce\Controller\Api\ApiCore
{
    const CATEGORY_ID_PARAM = 'taxonId';

    /**
     * @return $this
     */
    public function execute()
    {
        $categoryId = $this->getRequest()->getParam(self::CATEGORY_ID_PARAM);

        $configModel = $this->getConfigModel();
        $configModel->setData('identy_id', $categoryId);
        $configData = $configModel->getConfig();

        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }
}