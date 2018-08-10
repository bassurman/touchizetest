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

class NoConfig implements PageConfigInterface
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
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Catalog\Model\Product\Gallery\ReadHandler $_helperGallery,
        Context $context
    ) {
        $this->productUrlPathGenerator = $productUrlPathGenerator;
        $this->_helperGallery = $_helperGallery;
        $this->priceCurrency = $priceCurrency;
        $this->imageBuilder = $imageBuilder;
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