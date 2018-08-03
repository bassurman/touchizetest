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

namespace Touchize\TouchizeCommerce\Helper;

use function GuzzleHttp\default_ca_bundle;
use Magento\Framework\App\Helper\Context;
use Touchize\TouchizeCommerce\Model\Mobile\Detect;
use Touchize\TouchizeCommerce\Model\Config\Source\Devices;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Detect
     */
    protected $deviceDetector;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Detect  $deviceDetector
     */
    public function __construct(
        Context $context,
        Detect $deviceDetector
    ) {
        parent::__construct($context);
        $this->deviceDetector = $deviceDetector;
    }

    /**
     * @return bool
     */
    public function isAllowedToView()
    {
        $displayType = $this->getTypeDisplayDevices();
        switch ($displayType) {
            case Devices::MOBILE_ONLY :
                return $this->deviceDetector->isMobile();
                break;
            case Devices::TABLET_ONLY :
                return $this->deviceDetector->isTablet();
                break;
            case Devices::BOTH_DEVICES :
                return ($this->deviceDetector->isTablet() || $this->deviceDetector->isMobile());
                break;
            default:
                return false;
        }
        return false;
    }

    /**
     * @param $config_path
     *
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getTypeDisplayDevices()
    {
        return $this->getConfig('touchize_commmerce_config/touchize_commmerce_setup/display_devices');
    }

}