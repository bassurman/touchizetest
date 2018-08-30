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

class AddArea extends Touchapi
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $configData = array (
            0 =>
                array (
                    'Id' => '2',
                    'CampaignId' => '2',
                    'Tx' => '10.147441457069%',
                    'Ty' => '17.269736842105%',
                    'Width' => '32.263660017346%',
                    'Height' => '33.388157894737%',
                    'SearchTerm' => '',
                    'ProductId' => NULL,
                    'TaxonId' => NULL,
                ),
            1 =>
                array (
                    'Id' => '4',
                    'CampaignId' => '2',
                    'Tx' => '22.376409366869%',
                    'Ty' => '41.282894736842%',
                    'Width' => '45.620121422376%',
                    'Height' => '34.703947368421%',
                    'SearchTerm' => '',
                    'ProductId' => NULL,
                    'TaxonId' => NULL,
                ),
            2 =>
                array (
                    'Id' => '6',
                    'CampaignId' => '2',
                    'Tx' => '72.419774501301%',
                    'Ty' => '39.309210526316%',
                    'Width' => '8.1526452732003%',
                    'Height' => '5.9210526315789%',
                    'SearchTerm' => '',
                    'ProductId' => NULL,
                    'TaxonId' => NULL,
                ),
        );

        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }
}
