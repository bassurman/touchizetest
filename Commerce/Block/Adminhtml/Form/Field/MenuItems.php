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
 *  @author    Touchize Sweden AB <magento@touchize.com>
 *  @copyright 2018 Touchize Sweden AB
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Touchize Sweden AB
 */

namespace Touchize\Commerce\Block\Adminhtml\Form\Field;

use \Magento\Backend\Block\Template\Context;

class MenuItems extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \Magento\Framework\View\Element\BlockInterface
     */
    protected $typesRenderer;

    /**
     * @var \Magento\Framework\View\Element\BlockInterface
     */
    protected $isExternalRenderer;

    public function __construct(
        Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * Prepare to render.
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn('item_type', [
            'label' => __('Item Type'),
            'renderer' => $this->_getItemTypeRenderer()
        ]);
        $this->addColumn('item_title', [
            'label' => __('Title'),
            'style' => 'width:190px',
        ]);
        $this->addColumn('link_path', [
            'label' => __('Link Path/Url'),
            'style' => 'width:190px'
        ]);
        $this->addColumn('is_external', [
            'label' => __('Is External'),
            'renderer' => $this->getIsExternalRenderer()

        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add New Menu Item');
    }


    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getItemTypeRenderer()
            ->calcOptionHash($row->getData('item_type'))]
                         = 'selected="selected"';
        $optionExtraAttr['option_' . $this->getIsExternalRenderer()
            ->calcOptionHash($row->getData('is_external'))]
                         = 'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }

    protected function _getItemTypeRenderer()
    {
        $this->typesRenderer = $this->getLayout()->createBlock(
            \Touchize\Commerce\Block\Adminhtml\Form\Renderer\Field\MenuItems\Type::class,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        )->setName($this->_getCellInputElementName('item_type'))
            ->setTitle('item_type')
            ->setClass('menu-item-type')
            ->setId($this->_getCellInputElementId('<%- _id %>', 'item_type'));
        return $this->typesRenderer;
    }


    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    public function getIsExternalRenderer()
    {
        $this->isExternalRenderer = $this->getLayout()->createBlock(
            \Touchize\Commerce\Block\Adminhtml\Form\Renderer\Field\MenuItems\IsExternal::class,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        )->setName($this->_getCellInputElementName('is_external'))
            ->setTitle('is_external')
            ->setId($this->_getCellInputElementId('<%- _id %>', 'is_external'));
        return $this->isExternalRenderer;
    }
}