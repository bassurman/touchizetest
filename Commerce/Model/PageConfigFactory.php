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

namespace Touchize\Commerce\Model;


class PageConfigFactory
{
    const BASE_CONFIG_PATH = '\Touchize\Commerce\Model\PageConfig';

    const NO_CONFIG_CLASS = 'NoConfig';

    const ACTION_DELIMITER = '_';

    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    public function __construct(
        \Touchize\Commerce\Helper\Data $helper,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->helper = $helper;
        $this->_objectManager = $objectManager;
    }

    /**
     * @param $actionName
     *
     * @return PageConfigInterface
     */
    public function getConfigModel($actionName)
    {
        $className = $this->getClassName($actionName);
        $configModel = $this->_objectManager->get($className);
        return $configModel;
    }

    /**
     * @param $actionName
     *
     * @return string
     */
    protected function getClassName($actionName)
    {
        $className = self::BASE_CONFIG_PATH . '\\' .
        str_replace(self::ACTION_DELIMITER,'', ucwords($actionName,self::ACTION_DELIMITER));

        if (class_exists($className)) {
           return $className;
        }
        return self::BASE_CONFIG_PATH . '\\' . self::NO_CONFIG_CLASS;
    }

}