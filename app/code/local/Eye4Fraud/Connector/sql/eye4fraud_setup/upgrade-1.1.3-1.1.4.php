<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$connection = $installer->getConnection();

$installer->startSetup();

//ALTER TABLE `magento1702`.`fraud_status`
//ADD COLUMN `description` TEXT NULL COMMENT '' AFTER `updated_at`;

$installer->getConnection()
    ->changeColumn($installer->getTable('eye4fraud_connector/status'),
        'order_id',
        'order_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => false,
            'length' => 50
        )
    );

$installer->endSetup();