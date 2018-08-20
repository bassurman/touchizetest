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

namespace Touchize\Commerce\Helper;

use Magento\Framework\App\Helper\Context;
use Touchize\Commerce\Model\Mobile\Detect;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\Product\Gallery\ReadHandler
     */
    protected $_helperGallery;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;
    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Detect  $deviceDetector
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\Product\Gallery\ReadHandler $_helperGallery
    ) {
        parent::__construct($context);
        $this->_helperGallery = $_helperGallery;
        $this->imageHelper = $imageHelper;
    }

    public function getProductImages($product, $type = 'category_page_list')
    {
        $images = [];
        $this->_helperGallery->execute($product);
        $productName = $product->getName();
        $productImages = $product->getMediaGalleryImages();

        foreach ($productImages as $image) {
            $images[] =[
                'Name' => $this->getImageUrl($product, $image, $type),
                'UseCDN' => true,
                'Alt' => $image->getLabel()?$image->getLabel():$productName,
            ];
        }
        return $images;
    }

    public function getImageUrl($product, $image ,$imageId)
    {
        return  $this->imageHelper->init($product, $imageId)
            ->setImageFile($image->getFile())
            ->getUrl();
    }
}
