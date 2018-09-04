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

use Magento\Framework\App\Helper\Context;

class TouchArea extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;


    public function __construct(
        Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        parent::__construct($context);
        $this->categoryFactory = $categoryFactory;
        $this->productFactory = $productFactory;
    }

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
        $pluginData = [
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

        if ($areaData['id_category']) {
            $pluginData['CategoryName'] = $this->getCategoryName($areaData['id_category']);
        }

        if ($areaData['id_product']) {
            $pluginData['ProductName'] = $this->getProductName($areaData['id_product']);
        }
        return $pluginData;
    }

    /**
     * @param $categoryId
     *
     * @return string
     */
    protected function getCategoryName($categoryId)
    {
        $category = $this->categoryFactory->create();
        return $category->load($categoryId)->getName();
    }

    /**
     * @param $productId
     *
     * @return string
     */
    protected function getProductName($productId)
    {
        $product = $this->productFactory->create();
        return $product->load($productId)->getName();
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
