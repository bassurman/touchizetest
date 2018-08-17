<?php
/**
 * 2018 Touchize Sweden AB.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL":
 * http"://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to magento@touchize.com so we can send you a copy immediately.
 *
 * @author Touchize Sweden AB <magento@touchize.com>
 * @copyright 2018 Touchize Sweden AB
 * @licensehttp"://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Touchize Sweden AB
 */

namespace Touchize\Commerce\Model\PageConfig;


class TouchizecommerceApiSelectors extends NoConfig
{
    public function getConfig()
    {
        $selectorsData ='{"Stores":[{"Url":"http:\/\/touchize2.loc\/16\/","Name":"Test shop","Selected":false},{"Url":"http:\/\/touchize.loc\/16\/","Name":"Touchsize","Selected":true}],"Currencies":[{"Url":"http:\/\/touchize.loc\/16\/en\/module\/touchize\/selector?id_currency=1","Name":"Belarusian ruble","Selected":false,"ISOCode":"BYR"},{"Url":"http:\/\/touchize.loc\/16\/en\/module\/touchize\/selector?id_currency=2","Name":"Euro","Selected":true,"ISOCode":"EUR"},{"Url":"http:\/\/touchize.loc\/16\/en\/module\/touchize\/selector?id_currency=3","Name":"Indian Rupee","Selected":false,"ISOCode":"INR"},{"Url":"http:\/\/touchize.loc\/16\/en\/module\/touchize\/selector?id_currency=4","Name":"Krona","Selected":false,"ISOCode":"SEK"}],"Languages":[{"Url":"http:\/\/touchize.loc\/16\/en\/","Name":"English (English)","Selected":true,"ISOCode":"en"},{"Url":"http:\/\/touchize.loc\/16\/ru\/","Name":"\u0420\u0443\u0441\u0441\u043a\u0438\u0439 (Russian)","Selected":false,"ISOCode":"ru"},{"Url":"http:\/\/touchize.loc\/16\/de\/","Name":"Deutsch (German)","Selected":false,"ISOCode":"de"},{"Url":"http:\/\/touchize.loc\/16\/ca\/","Name":"Catal\u00e0 (Catalan)","Selected":false,"ISOCode":"ca"},{"Url":"http:\/\/touchize.loc\/16\/es\/","Name":"Espa\u00f1ol (Spanish)","Selected":false,"ISOCode":"es"},{"Url":"http:\/\/touchize.loc\/16\/gl\/","Name":"Galego (Galician)","Selected":false,"ISOCode":"gl"},{"Url":"http:\/\/touchize.loc\/16\/eu\/","Name":"Euskera (Basque)","Selected":false,"ISOCode":"eu"},{"Url":"http:\/\/touchize.loc\/16\/sv\/","Name":"Svenska (Swedish)","Selected":false,"ISOCode":"sv"}],"BackButtonTitle":"Back"}';
        return json_decode($selectorsData,true);
    }
}

