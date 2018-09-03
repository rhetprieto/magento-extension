<?php

namespace Skuiq\SyncModule\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $connection = $installer->getConnection();

        $installer->startSetup();

        /**
         * Create table 'skuiq_syncmodule_skuiq_settings'
         */
        $tableName = $installer->getTable('skuiq_syncmodule_skuiq_settings');

        $table = $connection->newTable(
            $tableName
        )->addColumn(
            'setting_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Setting Id'
        )->addColumn(
            'name',
            Table::TYPE_TEXT,
            255,
            [],
            'Name'
        )->addColumn(
            'store_id',
            Table::TYPE_INTEGER,
            null,
            [],
            'store_id'
				)->addColumn(
            'destination',
            Table::TYPE_TEXT,
            255,
            [],
            'destination'
				)->addColumn(
            'is_active',
            Table::TYPE_BOOLEAN,
            null,
            [ 'nullable' => false ],
            'Is Active'
        )->setComment(
            'SkuIQ Settings Table'
        );
        $connection->createTable($table);

        $installer->endSetup();
    }
}
