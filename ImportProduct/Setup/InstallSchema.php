<?php

namespace Dev\ImportProduct\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $getTable = $setup->getTable('custom_import_file');
        if($setup->getConnection()->isTableExists($getTable) != true)
        {
            $table = $setup->getConnection()
                ->newTable($getTable)
                ->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ], 
                    'Entity Id'
                )
                ->addColumn(
                    'file_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'File Name'
                )
                ->addColumn(
                    'file_path',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'File Path'
                )
                ->addColumn(
                    'triggered_time',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Triggered Time'
                )
                ->addColumn(
                    'status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    null,
                    ['nullable' => false, 'unsigned' => true],
                    'Status'
                );
            $setup->getConnection()->createTable($table);
        }
    }
}