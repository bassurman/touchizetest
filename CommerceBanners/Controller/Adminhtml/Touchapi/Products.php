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
        $jsn = '[{"Id":"7","Title":"Printed Chiffon Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/20-home_default\/printed-chiffon-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/20-home_default\/printed-chiffon-dress.jpg"},{"Id":"3","Title":"Printed Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/8-home_default\/printed-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/8-home_default\/printed-dress.jpg"},{"Id":"4","Title":"Printed Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/10-home_default\/printed-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/10-home_default\/printed-dress.jpg"},{"Id":"5","Title":"Printed Summer Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/12-home_default\/printed-summer-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/12-home_default\/printed-summer-dress.jpg"},{"Id":"6","Title":"Printed Summer Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/16-home_default\/printed-summer-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/16-home_default\/printed-summer-dress.jpg"},{"Id":"10","Title":"Printed Summer Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/31-home_default\/printed-summer-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/31-home_default\/printed-summer-dress.jpg"},{"Id":"11","Title":"Printed Summer Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/35-home_default\/printed-summer-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/35-home_default\/printed-summer-dress.jpg"},{"Id":"13","Title":"Printed Summer Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/42-home_default\/printed-summer-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/42-home_default\/printed-summer-dress.jpg"},{"Id":"14","Title":"Printed Summer Dress","ShortDescription":"<img src=\"http:\/\/touchize.loc\/16\/46-home_default\/printed-summer-dress.jpg\" alt=\"\" class=\"imgm img-thumbnail\">","Image":"http:\/\/touchize.loc\/16\/46-home_default\/printed-summer-dress.jpg"}]';

        $configData = \GuzzleHttp\json_decode($jsn, true);

        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }
}
