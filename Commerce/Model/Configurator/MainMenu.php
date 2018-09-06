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

namespace Touchize\Commerce\Model\Configurator;

use \Magento\Framework\DataObject\Mapper;


class MainMenu extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Catalog\Helper\Config
     */
    protected $dataHelper;


    /**
     * MainMenu constructor.
     *
     * @param \Magento\Framework\UrlInterface         $urlBuilder
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Touchize\Commerce\Helper\Data          $dataHelper
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\LayoutInterface $layout,
        \Touchize\Commerce\Helper\Data $dataHelper
    ) {
        $this->_urlBuilder = $urlBuilder;
        $this->layout = $layout;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @return array
     */
    public function getMainMenuConfig() {

        $defItems = $this->getDefaultItems();
        $confItems = $this->getConfiguredItems();
        $mainMenu['items'] = array_merge( $defItems, $confItems);

        return $mainMenu;
    }

    /**
     * @return array
     */
    protected function getConfiguredItems()
    {
        $confItems = [];
        $menuItems = $this->dataHelper->getConfiguredMenuItems();
        foreach ($menuItems as $_item) {
            $confItems[] = [
                'type' => 'menu-' . $_item['item_type'],
                'title' => $_item['item_type'] == 'divider' ? '' : $_item['link_title'],
                'url' => $this->getItemUrl($_item['link_path']),
                'external' => (bool)$_item['is_external'],
            ];
        }

        return  $confItems;
    }

    /**
     * @return array
     */
    protected function getDefaultItems()
    {
        $blockLinks = $this->layout->getBlock('navigation.menu.links');
        if ($blockLinks) {
            return $blockLinks->getLinks();
        }
        return [];
    }

    /**
     * @param $urlValue
     *
     * @return string
     */
    protected function getItemUrl($urlValue)
    {
        return $this->getUrlBuilder()->getUrl($urlValue);
    }

    /**
     * @return \Magento\Framework\UrlInterface
     */
    public function getUrlBuilder()
    {
        return $this->_urlBuilder;
    }
}