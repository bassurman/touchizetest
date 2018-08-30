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

namespace Touchize\CommerceBanners\Ui\DataProvider\Image\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Touchize\CommerceBanners\Model\ResourceModel\Banner\CollectionFactory;

class ImageData implements ModifierInterface
{
    /**
     * @var \Touchize\CommerceBanners\Model\ResourceModel\Banner\Collection
     */
    protected $collection;

    /**
     * @param CollectionFactory $imageCollectionFactory
     */
    public function __construct(
        CollectionFactory $imageCollectionFactory
    ) {
        $this->collection = $imageCollectionFactory->create();
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function modifyData(array $data)
    {
        $items = $this->collection->getItems();
        /** @var $banner \Touchize\CommerceBanners\Model\Banner */
        foreach ($items as $banner) {
            $_data = $banner->getData();
            if (isset($_data['image'])) {
                $imageArr = [];
                $imageArr[0]['name'] = $_data['image'];
                $imageArr[0]['url'] = $banner->getImageUrl();
                $_data['image'] = $imageArr;
            }
            $banner->setData($_data);
            $data[$banner->getId()] = $_data;
        }
        return $data;
    }
}
