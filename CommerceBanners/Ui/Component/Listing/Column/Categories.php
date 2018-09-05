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

namespace Touchize\CommerceBanners\Ui\Component\Listing\Column;

use Magento\Catalog\Ui\Component\Product\Form\Categories\Options as CategoryOptions;
use Magento\Catalog\Model\Category as CategoryModel;

use Magento\Framework\Option\ArrayInterface;

/**
 * Store Options for Cms Pages and Blocks
 */
class Categories extends CategoryOptions
{
    protected $_categoryHelper;

    protected $_categoryListPlain;

    public function __construct(\Magento\Catalog\Helper\Category $catalogCategory)
    {
        $this->_categoryHelper = $catalogCategory;
    }

    /*
     * Return categories helper
     */

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }

    /*
     * Option getter
     * @return array
     */
    public function toOptionArray()
    {
        $this->toArray();
        return  $this->_categoryListPlain;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $categories = $this->getStoreCategories(true,false,true);
        $categoryList = $this->getOptionCategories($categories);

        return $categoryList;
    }


    protected function getOptionCategories($collection)
    {
        $categoryList = [];
        foreach ($collection as $category){
            $this->_categoryListPlain[] =  [
                'label' => str_repeat('---', $category->getLevel()) .' '. __($category->getName()),
                'value' => $category->getId()
                ];

            if ($category->hasChildren()) {
                $this->getOptionCategories($category->getChildren());
            }
        }
        return $categoryList;
    }
}
