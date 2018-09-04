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

namespace Touchize\CommerceBanners\Model\TouchArea;

use Touchize\CommerceBanners\Model\TouchareaFactory;

class AddArea extends ListAreas implements \Touchize\CommerceBanners\Api\TouchAreaActionModel
{
    /**
     * @return array
     */
    public function getResponseData()
    {
        $this->updateData();
        return $this->getListAreas();
    }

    /**
     * @return $this
     */
    protected function updateData()
    {
        $requestData = $this->getData();
        if ($requestData) {
            $touchArea = $this->getTouchAreaModel();
            $preparedData = $this->touchAreaHelper->prepareToSave($requestData);
            $touchArea->addData($preparedData);
            $touchArea->save();
        }

        return $this;
    }

    protected function getTouchAreaModel()
    {
        $touchArea = $this->touchAreaFactory->create();
        $requestData = $this->getData();
        if(isset($requestData['id'])) {
            $touchArea->load((int)$requestData['id']);
        }
        return $touchArea;
    }
}