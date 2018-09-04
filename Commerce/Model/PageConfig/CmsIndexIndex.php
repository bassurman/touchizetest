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

namespace Touchize\Commerce\Model\PageConfig;

class CmsIndexIndex extends CatalogCategoryView implements PageConfigInterface
{
    /**
     * @return string
     */
    public function getConfig()
    {
        $this->initCurrentCategory();
        return parent::getConfig();
    }

    /**
     *
     */
    protected function initCurrentCategory()
    {
        $this->_configHelper->registerCurrentCategory();
    }

    /**
     * @return \Touchize\CommerceBanners\Model\ResourceModel\Banner\Collection
     */
    protected function getBannerCollection()
    {
        $banner = $this->bannerFactory->create();
        $collection = $banner->getCollection();
        $collection->addFieldToFilter('is_allowed_on_homepage',['eq' => 1]);
        $collection->addFieldToFilter('is_enabled',['eq' => 1]);

        $this->dataHelper->isAllowedToView();

        return $collection;
    }
}