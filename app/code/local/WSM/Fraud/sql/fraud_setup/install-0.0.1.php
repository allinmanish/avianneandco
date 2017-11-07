<?php

$installer = $this;
$tableName = $installer->getTable('wsm_fraud/fraud');

$installer->startSetup();

$installer->getConnection()->dropTable($tableName);
$table = $installer->getConnection()
->newTable($tableName)
->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'nullable'  => false,
		'primary'   => true,
))
->addColumn('ip', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
		'nullable'  => false,
))
->addColumn('count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned' 	=> true,
		'nullable'  => false,
))
->addColumn('last_attempt', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
		'nullable'  => false,
));
$installer->getConnection()->createTable($table);

$installer->endSetup();