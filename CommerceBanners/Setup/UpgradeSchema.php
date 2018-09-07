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

namespace Touchize\CommerceBanners\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $installer = $setup;
        if (version_compare($context->getVersion(), '1.1.0') < 0) {
            $this->createTouchAreaTable($installer);
        }

        if (version_compare($context->getVersion(), '1.2.0') < 0) {
            $this->addStoreBannerTable($installer);
        }

        if (version_compare($context->getVersion(), '1.3.0') < 0) {
            $this->removeStoreIdRow($installer);
        }

        if (version_compare($context->getVersion(), '1.4.0') < 0) {
            $this->addCategoriesColumn($installer);
        }

        if (version_compare($context->getVersion(), '1.4.1') < 0) {
            $this->addStoresColumn($installer);
        }
    }

    protected function createTouchAreaTable($installer)
    {
        $table = $installer->getConnection()->newTable(
            $installer->getTable('touchize_commercebanners_toucharea')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Toucharea id '
        )->addColumn(
            'banner_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Banner ID'
        )->addColumn(
            'tx',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'X coordinate touch area'
        )->addColumn(
            'ty',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Y coordinate touch area'
        )->addColumn(
            'width',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Width touch area'
        )->addColumn(
            'height',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Height touch area'
        )->addColumn(
            'id_product',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Assigned Product Id'
        )->addColumn(
            'id_category',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Assigned category Id'
        )->addColumn(
            'width',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Width'
        )->addColumn(
            'search_term',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Search term area'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Created At'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Updated At'
        )->addIndex(
            $installer->getIdxName('touchize_commercebanners_toucharea', ['banner_id']),
            ['banner_id']
        )/*->addForeignKey(
            $installer->getFkName('touchize_commercebanners_toucharea', 'banner_id', 'touchize_commercebanners_banner', 'banner_id'),
            'banner_id',
            $installer->getTable('touchize_commercebanners_banner'),
            'banner_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )*/->setComment(
            'Touchize Commercebanners Touchmaps Areas'
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }

    public function addStoreBannerTable($installer)
    {
        /**
         * Create table 'touchize_commercebanners_banner_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('touchize_commercebanners_banner_store')
        )->addColumn(
            'banner_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Banner ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('touchize_commercebanners_banner_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('touchize_commercebanners_banner_store', 'banner_id', 'touchize_commercebanners_banner', 'banner_id'),
            'banner_id',
            $installer->getTable('touchize_commercebanners_banner'),
            'banner_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('touchize_commercebanners_banner_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Touchize Banner To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);
    }

    protected function removeStoreIdRow($installer)
    {
        $installer->getConnection()->dropColumn($installer->getTable('touchize_commercebanners_banner'), 'store_id');
    }

    protected function addCategoriesColumn($installer)
    {

        $installer->getConnection()->addColumn(
            $installer->getTable('touchize_commercebanners_banner'),
            'categories',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Assigned Categories'
            ]
        );
    }

    protected function addStoresColumn($installer)
    {

        $installer->getConnection()->addColumn(
            $installer->getTable('touchize_commercebanners_banner'),
            'stores',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Assigned Stores'
            ]
        );
    }
}