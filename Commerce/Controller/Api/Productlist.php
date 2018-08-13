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


use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Touchize\Commerce\Model\PageConfigFactory;
use \Magento\Catalog\Model\CategoryFactory;

class Productlist extends \Touchize\Commerce\Controller\Api\ApiCore
{
    const CATEGORY_ID_PARAM = 'taxonId';
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Touchize\Commerce\Model\PageConfigFactory
     */
    protected $pageConfigFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;


    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        PageConfigFactory $pageConfigFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->pageConfigFactory = $pageConfigFactory;
        $this->_storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * Index action
     *
     * @return $this
     */
    public function execute()
    {
        $categoryId = $this->getRequest()->getParam(self::CATEGORY_ID_PARAM);
        $category = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());;

        $this->_coreRegistry->register('current_category', $category);

        $configModel = $this->getConfigModel();
        $configData = $configModel->getConfig();
        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }
}