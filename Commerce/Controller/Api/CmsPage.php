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

class CmsPage extends \Touchize\Commerce\Controller\Api\ApiCore
{
    const PAGE_PARAM = 'Page';

    /**
     * Index action
     *
     * @return $this
     */
    public function execute()
    {
        $pageId = $this->getRequest()->getParam(self::PAGE_PARAM);

        $configModel = $this->getConfigModel();
        $configModel->setPageId($pageId);
        $configData = $configModel->getConfig();

        $result = $this->resultJsonFactory->create();
        return $result->setData($configData);
    }
}