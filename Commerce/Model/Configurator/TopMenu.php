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

namespace Touchize\Commerce\Model\Configurator;

use \Magento\Framework\DataObject\Mapper;


class TopMenu extends \Magento\Framework\Model\AbstractModel
{
    const MODIFIED_PARAM = 'category_url';

    /**
     * @var array
     */
    protected $_topMenuMap = array(
        'entity_id'    => 'Id',
        'parent_id'    => 'ParentId',
        'name'         => 'Name',
        'is_active'    => 'IsActive',
        'position'     => 'Position',
        'level'        => 'Level',
        self::MODIFIED_PARAM => 'Url',
    );

    /**
     * Top menu data tree
     *
     * @var \Magento\Framework\Data\Tree\Node
     */
    protected $_menu;

    /**
     * Catalog category
     *
     * @var \Magento\Catalog\Helper\Category
     */
    protected $catalogCategory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Catalog\Helper\Category $catalogCategory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */


    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Category $categoryHelper
    ) {
        $this->storeManager = $storeManager;
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * @return array
     */
    public function getTopMenuTree() {

        if (is_null($this->_menu)) {
            $rootId = $this->storeManager->getStore()->getRootCategoryId();
            /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
            $collection = $this->categoryHelper->getStoreCategories();

            $topMenuTree = $this->_remapTopMenu($collection);

            $this->_menu = [
                'Id' => $rootId,
                'Tree' => $topMenuTree
            ];
        }

        return $this->_menu;
    }

    /**
     * @param $categoryNode
     *
     * @return array
     */
    protected function _remapTopMenu($menuCollection)
    {
        $children = [];
        if ($menuCollection) {
            foreach ($menuCollection as $child) {
                $node = [];
                $child->setData(self::MODIFIED_PARAM, $this->categoryHelper->getCategoryUrl($child));

                $node = Mapper::accumulateByMap($child->getData(), $node, $this->_topMenuMap);

                if ($child->hasChildren()) {
                    $childNodes = $child->getChildren();
                    $node['SubTaxa'] = $this->_remapTopMenu($childNodes);
                }
            $children[] = $node;
            }
            return $children;
        }
    }
}