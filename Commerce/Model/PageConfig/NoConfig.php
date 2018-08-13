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

use \Magento\Framework\View\Element\Template\Context;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class NoConfig extends \Magento\Framework\Model\AbstractModel implements PageConfigInterface
{
    /**
     * @var \Touchize\Commerce\Helper\Config
     */
    protected $_configHelper;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;


    public function __construct(
        ProductUrlPathGenerator $productUrlPathGenerator,
        PriceCurrencyInterface $priceCurrency,
        \Touchize\Commerce\Helper\Config $configHelper,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Catalog\Model\Product\Gallery\ReadHandler $_helperGallery,
        Context $context
    ) {
        $this->productUrlPathGenerator = $productUrlPathGenerator;
        $this->_helperGallery = $_helperGallery;
        $this->priceCurrency = $priceCurrency;
        $this->imageHelper = $imageHelper;
        $this->_configHelper = $configHelper;
        $this->_priceHelper = $priceHelper;
        $this->_layout = $context->getLayout();
        $this->_registry = $registry;
    }

    /**
     * @return string
     */
    public function getConfig()
    {
        return json_decode('[{"Id":"404","SKU":"msj006c","Title":"Plaid Cotton Shirt",
        "SingleVariantId":null,"Url":"men\/shirts\/plaid-cotton-shirt-577.html","Price":160,"DiscountedPrice":null,"FPrice":"$160.00","FDiscountedPrice":null,"Images":[{"Name":"http:\/\/touchizem1.loc\/media\/catalog\/product\/cache\/1\/small_image\/210x\/9df78eab33525d08d6e5fb8d27136e95\/m\/s\/msj006t.jpg","UseCDN":true,"Alt":"Plaid Cotton Shirt"}]},{"Id":"403","SKU":"msj003c","Title":"Slim fit Dobby Oxford Shirt","SingleVariantId":null,"Url":"men\/shirts\/slim-fit-dobby-oxford-shirt-575.html","Price":175,"DiscountedPrice":140,"FPrice":"$175.00","FDiscountedPrice":"$140.00","Images":[{"Name":"http:\/\/touchizem1.loc\/media\/catalog\/product\/cache\/1\/small_image\/210x\/9df78eab33525d08d6e5fb8d27136e95\/m\/s\/msj003t_2.jpg","UseCDN":true,"Alt":"Slim fit Dobby Oxford Shirt"}]},{"Id":"402","SKU":"msj000c","Title":"French Cuff Cotton Twill Oxford","SingleVariantId":null,"Url":"men\/shirts\/french-cuff-cotton-twill-oxford-573.html","Price":190,"DiscountedPrice":null,"FPrice":"$190.00","FDiscountedPrice":null,"Images":[{"Name":"http:\/\/touchizem1.loc\/media\/catalog\/product\/cache\/1\/small_image\/210x\/9df78eab33525d08d6e5fb8d27136e95\/m\/s\/msj000t_2.jpg","UseCDN":true,"Alt":"French Cuff Cotton Twill Oxford"}]}]',true);
        return [];
    }

    /**
     * @return \Magento\Framework\View\LayoutInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLayout()
    {
        if (!$this->_layout) {
            throw new \Magento\Framework\Exception\LocalizedException(
                new \Magento\Framework\Phrase('Layout must be initialized')
            );
        }
        return $this->_layout;
    }
}