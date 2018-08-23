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

namespace Touchize\Commerce\Controller\Api;

use \Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Touchize\Commerce\Model\PageConfigFactory;
use \Magento\Catalog\Api\ProductRepositoryInterface;

class Product extends \Touchize\Commerce\Controller\Api\ApiCore
{
    const PRODUCT_ID_PARAM = 'Id';

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;


    /**
     * Product constructor.
     *
     * @param Context                                    $context
     * @param JsonFactory                                $resultJsonFactory
     * @param PageConfigFactory                          $pageConfigFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry                $coreRegistry
     * @param ProductRepositoryInterface                 $productRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        PageConfigFactory $pageConfigFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,
        \Touchize\Commerce\Model\PageConfig\NoConfig $configModel,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context, $resultJsonFactory, $pageConfigFactory, $storeManager, $coreRegistry,$configModel);
        $this->productRepository = $productRepository;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $productId = $this->getRequest()->getParam(self::PRODUCT_ID_PARAM);
        $product = $this->productRepository->getById($productId, false, $this->_storeManager->getStore()->getId());
        $this->_coreRegistry->register('current_product', $product);

        $configModel = $this->getConfigModel();
        $configData = $configModel->getConfig();

        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }
}