<?php
/*
 * Touchize_CommerceBanners

 * @category   Touchize
 * @package    Touchize_CommerceBanners
 * @copyright  Copyright (c) 2017 Touchize

 * @version    1.0.0
 */
namespace Touchize\CommerceBanners\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('touchize_commercebanners_banner');

        if (!$installer->tableExists('touchize_commercebanners_banner')) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'banner_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Banner ID'
                )
                ->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable'  => false],
                    'Banner'
                )
                ->addColumn(
                    'title',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '255',
                    [],
                    'Banner title'
                )
                ->addColumn(
                    'is_enabled',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '5',
                    [],
                    'Is enabled'
                )
                ->addColumn(
                    'is_allowed_on_mobile',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '5',
                    [],
                    'Is Allowed On Mobile'
                )
                ->addColumn(
                    'is_allowed_on_tablet',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '5',
                    [],
                    'Is Allowed On Tablet'
                )
                ->addColumn(
                    'is_allowed_on_homepage',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '5',
                    [],
                    'Is Allowed On HomePage'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Banner Created At'
                )
                ->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Banner Updated At'
                )
                ->addColumn(
                    'position',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '255',
                    [],
                    'Position'
                )
                ->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    '5',
                    [],
                    'Store Id'
                )
                ->setComment(
                    'Touchize Commerce Banners'
                );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
