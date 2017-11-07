<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

/**
 * Create 'Eye4Fraud_Connector/fraud_status' table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('eye4fraud_connector/status'))
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true
    ), 'Order ID')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'length'    => 5
    ), 'Status code');
$installer->getConnection()->createTable($table);