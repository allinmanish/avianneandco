<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$webforms_table = 'webforms';

$edition = 'CE';
$version = explode('.', Mage::getVersion());
if ($version[1] >= 9)
	$edition = 'EE';

if((float)substr(Mage::getVersion(),0,3)>1.1 || $edition == 'EE')
	$webforms_table = $this->getTable('webforms/webforms');

$installer->run("
ALTER TABLE  `$webforms_table` ADD  `email_template_id` int( 11 ) NOT NULL AFTER `email`;
ALTER TABLE  `$webforms_table` ADD  `email_customer_template_id` int( 11 ) NOT NULL AFTER `email_template_id`;
ALTER TABLE  `{$this->getTable('webforms/fields')}` ADD  `comment` TEXT NOT NULL AFTER `name`;
");

// Professional Edition 1.2
if((float)substr(Mage::getVersion(),0,3)>1){
	$webform = Mage::getModel('webforms/webforms')->setData(array(
		'name' => 'Professional Edition 1.2',
		'description' => '<p>Introducing new features of the WebForms Professional Edition 1.2</p>',
		'success_text' => '<p>Thank you</p>',
		'registered_only' => 0,
		'send_email' => 1,
		'approve' => 0,
		'survey' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
		
	))->save();

	// star rating fieldset
	$fieldset = Mage::getModel('webforms/fieldsets')->setData(array(
		'webform_id' => $webform->getId(),
		'name' => 'Star rating',
		'position' => 10,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'Rate your satisfaction',
		'comment' => 'Star rating makes it easy',
		'result_label' => 'Satisfaction',
		'code' => 'satisfaction',
		'type' => 'stars',
		'size' => 'standard',
		'position' => 10,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'Rate your importance',
		'comment' => 'Your customers should feel they are important to you',
		'result_label' => 'Importance',
		'code' => 'importance',
		'type' => 'stars',
		'size' => 'standard',
		'value' => '8',
		'position' => 15,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	// file attachments fieldset
	$fieldset = Mage::getModel('webforms/fieldsets')->setData(array(
		'webform_id' => $webform->getId(),
		'name' => 'File attachments',
		'position' => 20,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'Image description',
		'comment' => 'Enter image description here',
		'result_label' => '',
		'code' => 'image_descr',
		'type' => 'text',
		'size' => 'standard',
		'value' => '',
		'position' => 20,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'Image upload',
		'comment' => 'Only image type files are allowed',
		'result_label' => 'Image',
		'code' => 'image_file',
		'type' => 'image',
		'size' => 'standard',
		'value' => '',
		'position' => 30,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'Document description',
		'comment' => 'Enter document description here',
		'result_label' => '',
		'code' => 'doc_descr',
		'type' => 'text',
		'size' => 'standard',
		'value' => '',
		'position' => 40,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'Document upload',
		'comment' => 'Only office documents extensions are allowed',
		'result_label' => 'Document',
		'code' => 'doc_file',
		'type' => 'file',
		'size' => 'standard',
		'value' => "txt\ndoc\ndocx\nxls\nxlsx\npdf",
		'position' => 50,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'File description',
		'comment' => 'Enter file description here',
		'result_label' => '',
		'code' => 'file_descr',
		'type' => 'text',
		'size' => 'standard',
		'value' => '',
		'position' => 60,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();

	$field = Mage::getModel('webforms/fields')->setData(array(
		'webform_id' => $webform->getId(),
		'fieldset_id' => $fieldset->getId(),
		'name' => 'File upload',
		'comment' => 'Upload files of any type here',
		'result_label' => 'File',
		'code' => 'file_file',
		'type' => 'file',
		'size' => 'standard',
		'value' => "",
		'position' => 60,
		'required' => 0,
		'is_active' => 1,
		'created_time' => Mage::getSingleton('core/date')->gmtDate(),
		'update_time' => Mage::getSingleton('core/date')->gmtDate(),
	))->save();
}

$installer->endSetup();
?>