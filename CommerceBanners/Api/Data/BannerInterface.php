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
namespace Touchize\CommerceBanners\Api\Data;

interface BannerInterface
{
    const BANNER_ID = 'banner_id';
    const IMAGE = 'image';
    const TITLE = 'title';
    const IS_ENABLED = 'is_enabled';
    const SHOW_MOBILE = 'is_allowed_on_mobile';
    const SHOW_TABLET = 'is_allowed_on_tablet';
    const SHOW_HOME = 'is_allowed_on_homepage';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const POSITION = 'position';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param $title
     * @return BannerInterface
     */
    public function setTitle($title);

    /**
     * Set ID
     *
     * @param $id
     * @return BannerInterface
     */
    public function setId($id);

    /**
     * Set image
     *
     * @param $image
     * @return BannerInterface
     */
    public function setImage($image);

    /**
     * @return BannerInterface
     */
    public function setIsEnabled($isEnabled);

    /**
     * @return bool
     */
    public function getIsEnabled();

    /**
     * @return mixed
     */
    public function setIsAllowedOnMobile($isAllowedOnMobile);


    /**
     * @return BannerInterface
     */
    public function getIsAllowedOnMobile();

    /**
     * @return mixed
     */
    public function setIsAllowedOnTablet($isAllowedOnTablet);


    /**
     * @return BannerInterface
     */
    public function getIsAllowedOnTablet();

    /**
     * @return mixed
     */
    public function setIsAllowedOnHomepage($isAllowedOnHomepage);


    /**
     * @return BannerInterface
     */
    public function getIsAllowedOnHomepage();

    /**
     * @param $createdAt
     *
     * @return BannerInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * @param $updatedAt
     *
     * @return BannerInterface
     */
    public function setUpdatedAt($updatedAt);

    public function getStores();
}
