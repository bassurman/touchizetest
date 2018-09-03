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

namespace Touchize\CommerceBanners\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class BannerStore extends AbstractDb
{
    const TABLE_NAME = 'touchize_commercebanners_banner_store';

    protected function _construct()
    {
        $this->_init('touchize_commercebanners_banner_store', 'banner_id');
    }


    public function updateBannerRelations($bannerId, $storeIds)
    {
        $insertData = [];
        if ($storeIds) {
            foreach ($storeIds  as $storeId) {
                $insertData[] = [
                    'banner_id' => $bannerId,
                    'store_id' => $storeId
                ];
            }
            if ($insertData) {
                $this->clearOldBannerRows($bannerId);
                $this->insertMultiple($insertData);
            }
        }
    }

    protected function insertMultiple($insertData)
    {
        $connection = $this->getConnection();
        $connection->insertMultiple($this->getTable(self::TABLE_NAME), $insertData);
    }

    protected function clearOldBannerRows($bannerId)
    {
        return $this->getConnection()->delete(
            $this->getTable(self::TABLE_NAME),
            ['banner_id = ?' => $bannerId]
        );
    }


    public function getAssignedRows($bannerId)
    {
        $select = $this->getConnection()->select();
        $select->from($this->getTable(self::TABLE_NAME), 'store_id');
        $select->where('banner_id = ?', $bannerId);
        return $select;
    }
}
