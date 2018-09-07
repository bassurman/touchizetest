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

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;
use Touchize\CommerceBanners\Api\Data\BannerInterface;

class Banner extends AbstractModel implements BannerInterface
{

    protected $_eventPrefix = 'commercebanners_banner';
    /**
     * Cache tag
     */
    const CACHE_TAG = 'touchize_commercebanners_banner';

    /**
     * @var UploaderPool
     */
    protected $uploaderPool;

    /**
     * Sliders constructor.
     * @param Context $context
     * @param Registry $registry
     * @param UploaderPool $uploaderPool
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        UploaderPool $uploaderPool,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->uploaderPool    = $uploaderPool;
    }

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Touchize\CommerceBanners\Model\ResourceModel\Banner');
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(BannerInterface::IMAGE);
    }

    /**
     * Set image
     *
     * @param $image
     * @return $this
     */
    public function setImage($image)
    {
        return $this->setData(BannerInterface::IMAGE, $image);
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(BannerInterface::TITLE);
    }


    /**
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(BannerInterface::TITLE, $title);
    }

    /**
     * @param $isEnabled
     *
     * @return $this
     */
    public function setIsEnabled($isEnabled)
    {
        return $this->setData(BannerInterface::IS_ENABLED, $isEnabled);
    }

    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return (bool)$this->getData(BannerInterface::IS_ENABLED);
    }

    /**
     * @param $isAllowedOnMobile
     *
     * @return $this
     */
    public function setIsAllowedOnMobile($isAllowedOnMobile)
    {
        return $this->setData(BannerInterface::SHOW_MOBILE, $isAllowedOnMobile);
    }


    /**
     * @return bool
     */
    public function getIsAllowedOnMobile()
    {
        return (bool)$this->getData(BannerInterface::SHOW_MOBILE);
    }


    /**
     * @param $isAllowedOnTablet
     *
     * @return $this
     */
    public function setIsAllowedOnTablet($isAllowedOnTablet)
    {
        return $this->setData(BannerInterface::SHOW_TABLET, $isAllowedOnTablet);
    }


    /**
     * @return bool
     */
    public function getIsAllowedOnTablet()
    {
        return (bool)$this->getData(BannerInterface::SHOW_TABLET);
    }

    /**
     * @param $isAllowedOnHomepage
     *
     * @return $this
     */
    public function setIsAllowedOnHomepage($isAllowedOnHomepage)
    {
        return $this->setData(BannerInterface::SHOW_HOME, $isAllowedOnHomepage);
    }


    /**
     * @return bool
     */
    public function getIsAllowedOnHomepage()
    {
        return (bool)$this->getData(BannerInterface::SHOW_HOME);
    }

    /**
     * @param $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        /*if (!$this->getId()) {*/
            $time = time();
            return $this->setData('created_at', $time);
        /*}*/
    }

    /**
     * @param $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $time = time();
        return $this->setData('updated_at', $time);
    }

    /**
     * @return bool|string
     * @throws LocalizedException
     */
    public function getImageUrl()
    {
        $url = false;
        $image = $this->getImage();
        if ($image) {
            if (is_string($image)) {
                $uploader = $this->uploaderPool->getUploader('image');
                $url = $uploader->getBaseUrl().$uploader->getBasePath().$image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    /**
     * @return bool|string
     * @throws LocalizedException
     */
    public function getImagePath()
    {
        $path = false;
        $image = $this->getImage();
        if ($image) {
            if (is_string($image)) {
                $uploader = $this->uploaderPool->getUploader('image');
                $path = $uploader->getBasePath() . $image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $path;
    }


    /**
     * @param $categories
     *
     * @return $this
     */
    public function setStores($stores)
    {
        if (is_array($stores)) {
            $stores = implode(',',$stores);
        }
        return $this->setData('stores', $stores);
    }

    /**
     * @return mixed
     */
    public function getStores()
    {
        return $this->getData('stores');
    }

    /**
     * @param $categories
     *
     * @return $this
     */
    public function setCategories($categories)
    {
        if (is_array($categories)) {
            $categories = implode(',',$categories);
        }
        return $this->setData('categories', $categories);
    }

    /**
     * @return string
     */
    public function getCategories()
    {
        return $this->getData('categories');
    }
}
