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

namespace Touchize\CommerceBanners\Helper;

class TouchArea extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PERCENT_SIGN = '%';

    protected $_mapData = [
        'banner_id' => 'CampaignId',
        'tx' => 'Tx',
        'ty' => 'Ty',
        'width' => 'Width',
        'height' => 'Height',
        'search_term' => 'SearchTerm',
        'id_product' => 'ProductId',
        'id_category' => 'TaxonId',
    ];

    /**
     * @param $areaData
     *
     * @return array
     */
    public function remapStoredData($areaData)
    {
        return [
            'Id' =>  $areaData['id'],
            'CampaignId' =>  $areaData['banner_id'],
            'Tx' =>  $this->addPercentValues($areaData['tx']),
            'Ty' =>  $this->addPercentValues($areaData['ty']),
            'Width' =>  $this->addPercentValues($areaData['width']),
            'Height' => $this->addPercentValues($areaData['height']),
            'SearchTerm' => $areaData['search_term'],
            'ProductId' => $areaData['id_product'],
            'TaxonId' => $areaData['id_category'],
        ];
    }

    /**
     * @param $requestData
     *
     * @return array
     */
    public function prepareToSave($requestData)
    {
        $preparedData = [];
        foreach ($this->_mapData as $dataKey => $pluginKey ) {
            if(isset($requestData[$dataKey])) {
                $preparedData[$dataKey] = $requestData[$dataKey];
            }
        }
        return $preparedData;
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function addPercentValues($value)
    {
        $percentage = ($value * 100) . self::PERCENT_SIGN;
        return $percentage;
    }
}
