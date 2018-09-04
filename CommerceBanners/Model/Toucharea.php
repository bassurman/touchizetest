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

namespace Touchize\CommerceBanners\Model;

use \Magento\Framework\Model\AbstractModel;

class Toucharea extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'touchize_commercebanners_toucharea';

    protected $_cacheTag = 'touchize_commercebanners_toucharea';

    protected $_eventPrefix = 'touchize_commercebanners_toucharea';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('Touchize\CommerceBanners\Model\ResourceModel\Toucharea');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    /**
     * @param $bannerId
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getCollectionByBannerId($bannerId)
    {
        $collection = $this->getCollection();
        $collection->addFieldToFilter('banner_id', $bannerId);
        return $collection;
    }
}