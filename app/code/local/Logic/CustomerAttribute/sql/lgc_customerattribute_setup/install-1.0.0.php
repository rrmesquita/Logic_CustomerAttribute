<?php
/**
 * @category    Logic
 * @package     Logic_CustomerAttribute
 * @author      Rodrigo Mesquita <rodrigoriome@gmail.com>
 * @license     https://opensource.org/licenses/MIT - MIT License
 */

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
->newTable($installer->getTable('lgc_customerattribute/store'))
->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary'  => true,
), 'Relationship Id')
->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'unsigned' => true,
    'nullable' => false,
), 'Attribute Id')
->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'unsigned' => true,
    'nullable' => false,
), 'Store Id');

$installer->getConnection()->createTable($table);
$installer->endSetup();
