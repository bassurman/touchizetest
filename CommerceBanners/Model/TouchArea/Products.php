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

class Products extends AbstractAreaApi implements \Touchize\CommerceBanners\Api\TouchAreaActionModel
{

    const THUMBNAIL_SIZE = 'product_thumbnail_image';
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    public function __construct(
        TouchareaFactory $touchAreaFactory,
        \Touchize\CommerceBanners\Helper\TouchArea $touchAreaHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Helper\Image $imageHelper
    ) {
        parent::__construct($touchAreaFactory, $touchAreaHelper);
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @return array
     */
    public function getResponseData()
    {
        $query = $this->getSearchQuery();
        if ($query) {
            return $this->findInProducts($query);
        }
        return [];
    }

    protected function findInProducts($query)
    {
        $product = $this->productFactory->create();
        $collection = $product->getCollection();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('short_description');
        $collection->addAttributeToSelect('image');
        $collection->addAttributeToFilter('name' ,['like' => "%" . $query . "%"]);
        $responseData = [];
        foreach ($collection as $product) {
            $imageUrl = $this->imageHelper
                ->init($product, self::THUMBNAIL_SIZE)
                ->setImageFile($product->getImage())->getUrl();

            $responseData[] = [
                "Id" => $product->getId(),
                "Title" => $product->getName(),
                "ShortDescription" => '<img src="' . $imageUrl . '" alt="" class="imgm img-thumbnail">',
                "Image" => $imageUrl,
            ];
        }

        return $responseData;
    }
}