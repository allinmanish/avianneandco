<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2012 Vladimir Popov
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE  `{$this->getTable('webforms/results')}` ADD  `status` varchar( 100 ) NOT NULL DEFAULT '' AFTER `update_time`;
");

$installer->endSetup();
?>