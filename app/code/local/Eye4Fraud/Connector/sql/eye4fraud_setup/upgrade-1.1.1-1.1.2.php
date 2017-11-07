<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$connection = $installer->getConnection();

$installer->startSetup();
// ALTER TABLE `magento1702`.`fraud_status`
//CHANGE COLUMN `order_id` `order_id` BIGINT(10) UNSIGNED NOT NULL COMMENT 'Order ID' ;

$installer->getConnection()
    ->changeColumn($installer->getTable('eye4fraud_connector/status'),
        'order_id',
        'order_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_BIGINT,
            'nullable' => false,
            'unsigned' => true
        )
    );

$installer->endSetup();