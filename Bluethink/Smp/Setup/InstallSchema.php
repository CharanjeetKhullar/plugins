<?php
 
namespace Bluethink\Smp\Setup;
 
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('sample_product_table');
        if ($installer->getConnection()->isTableExists($tableName) != true) {
              $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )->addColumn(
                    'product_id',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false, 'default' => ''
                    ],
                    'Product ID'
                )->addColumn(
                    'product_sku',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false, 'default' => ''
                    ],
                    'Product SKU'
                )
                ->addColumn(
                    'sample_title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Sample Title'
                )
                ->addColumn(
                    'sample_price',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Sample Price'
                )
                ->addColumn(
                    'sample_qty',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Sample Qty'
                )->addColumn(
                    'sample_product_id',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Sample Product ID'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                //Set comment for magetop_blog table
                ->setComment('Sample Product Table')
                //Set option for magetop_blog table
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
