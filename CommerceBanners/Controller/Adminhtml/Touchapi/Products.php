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

class Products extends Touchapi
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $query = $this->getRequest()->getParam('q');
        $configData = $this->getSearchResults($query);
        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    protected function getSearchResults($query)
    {
        $this->touchAreaActionModel->setSearchQuery($query);
        return $this->touchAreaActionModel->getResponseData();
    }
}
