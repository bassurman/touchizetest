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

namespace Touchize\CommerceBanners\Model\TouchArea;

use Touchize\CommerceBanners\Model\TouchareaFactory;

class Categories extends AbstractAreaApi implements \Touchize\CommerceBanners\Api\TouchAreaActionModel
{

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    public function __construct(
        TouchareaFactory $touchAreaFactory,
        \Touchize\CommerceBanners\Helper\TouchArea $touchAreaHelper,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory

    ) {
        parent::__construct($touchAreaFactory, $touchAreaHelper);
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * @return array
     */
    public function getResponseData()
    {
        $query = $this->getSearchQuery();
        if ($query) {
            return $this->findInCategory($query);
        }
        return [];
    }

    protected function findInCategory($query)
    {
        $category = $this->categoryFactory->create();
        $collection = $category->getCollection();
        $collection->addAttributeToFilter('name' ,['like' => "%" . $query . "%"]);
        $collection->count();
        $responseData = [];
        foreach ($collection as $category) {
            $responseData[] = [
                "Id" => $category->getId(),
                "Name" => $category->getName()
            ];
        }

        return $responseData;
    }
}