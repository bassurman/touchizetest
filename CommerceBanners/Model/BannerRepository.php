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

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Touchize\CommerceBanners\Api\BannerRepositoryInterface;
use Touchize\CommerceBanners\Api\Data\BannerInterface;
use Touchize\CommerceBanners\Api\Data\BannerInterfaceFactory;
use Touchize\CommerceBanners\Model\ResourceModel\Banner as ResourceImage;
use Touchize\CommerceBanners\Model\ResourceModel\Banner\CollectionFactory as ImageCollectionFactory;

class BannerRepository implements BannerRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];
    /**
     * @var ResourceImage
     */
    protected $resource;

    /**
     * @var ImageCollectionFactory
     */
    protected $imageCollectionFactory;

    /**
     * @var BannerInterfaceFactory
     */
    protected $bannerInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        ResourceImage $resource,
        ImageCollectionFactory $imageCollectionFactory,
        BannerInterfaceFactory $bannerInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->imageCollectionFactory = $imageCollectionFactory;
        $this->bannerInterfaceFactory = $bannerInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param BannerInterface $image
     * @return BannerInterface
     * @throws CouldNotSaveException
     */
    public function save(BannerInterface $image)
    {
        try {
            /** @var BannerInterface|\Magento\Framework\Model\AbstractModel $image */
            $this->resource->save($image);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the image: %1',
                $exception->getMessage()
            ));
        }
        return $image;
    }

    /**
     * Get image record
     *
     * @param $imageId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($imageId)
    {
        if (!isset($this->instances[$imageId])) {
            $image = $this->bannerInterfaceFactory->create();
            $this->resource->load($image, $imageId);
            if (!$image->getId()) {
                throw new NoSuchEntityException(__('Requested image doesn\'t exist'));
            }
            $this->instances[$imageId] = $image;
        }
        return $this->instances[$imageId];
    }

    /**
     * @param BannerInterface $image
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(BannerInterface $image)
    {
        /** @var \Touchize\CommerceBanners\Api\Data\BannerInterface|\Magento\Framework\Model\AbstractModel $image */
        $id = $image->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($image);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove image %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * @param $imageId
     * @return bool
     */
    public function deleteById($imageId)
    {
        $image = $this->getById($imageId);
        return $this->delete($image);
    }
}
