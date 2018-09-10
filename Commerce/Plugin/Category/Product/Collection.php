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

namespace Touchize\Commerce\Plugin\Category\Product;

use Magento\Catalog\Model\Product;

class Collection
{
    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $catalogProductVisibility;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Touchize\Commerce\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $helper;

    /**
     * Collection constructor.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param Product\Visibility                                             $catalogProductVisibility
     * @param \Magento\Store\Model\StoreManagerInterface                     $storeManager
     * @param \Touchize\Commerce\Helper\Data                                 $helper
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Touchize\Commerce\Helper\Category $categoryHelper,
        \Touchize\Commerce\Helper\Data $helper
    ) {

        $this->_productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->storeManager = $storeManager;
        $this->categoryHelper = $categoryHelper;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\App\Http $subject
     */
    public function aroundGetProductCollection(\Magento\Catalog\Model\Category $subject, $proceed)
    {
        if ($this->helper->isAllowedToView()) {
            $collection = $this->_productCollectionFactory->create()->setStoreId(
                $subject->getStoreId()
            );
            if(
                $subject->getParentId() ==  $this->storeManager->getStore()->getRootCategoryId() &&
                $this->categoryHelper->showAllOnParent()
            ) {
                $allChildren = $subject->getAllChildren(true);
                $allChildren[] = $subject->getId();
                $collection->addCategoriesFilter(['in' => $allChildren]);
            } else {
                $collection->addCategoryFilter(
                    $subject
                );
            }
            $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
            $isLimit = $this->helper->isLimitEnabled();
            if ($isLimit) {
                $maxCount = $this->helper->getMaxItemsCount();
                $collection->setPageSize($maxCount);
            }
            return $collection;
        }

        return $proceed();
    }
}