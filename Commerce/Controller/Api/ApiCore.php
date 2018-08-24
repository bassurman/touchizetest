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

class ApiCore extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Touchize\Commerce\Model\PageConfig\NoConfig
     */
    protected $configModel;

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

    /**
     * ApiCore constructor.
     *
     * @param Context                                    $context
     * @param JsonFactory                                $resultJsonFactory
     * @param PageConfigFactory                          $pageConfigFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry                $coreRegistry
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        PageConfigFactory $pageConfigFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,
        \Touchize\Commerce\Model\PageConfig\NoConfig $configModel
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->pageConfigFactory = $pageConfigFactory;
        $this->_storeManager = $storeManager;
        $this->_coreRegistry = $coreRegistry;
        $this->configModel = $configModel;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        return $this->_redirect('/');
    }

    /**
     * @return \Touchize\Commerce\Model\PageConfigInterface
     */
    public function getConfigModel()
    {
        return $this->configModel;
    }
}