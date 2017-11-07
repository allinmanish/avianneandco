<?php 
$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('catalog_category', 'page_header',  array(
		'type'     => 'varchar',
		'label'    => 'Page Header',
		'input'    => 'text',
		'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => false,
		'default'           => ''
));

$installer->addAttributeToGroup(
		$entityTypeId,
		$attributeSetId,
		$attributeGroupId,
		'page_header',
		'12'                    //last Magento's attribute position in General tab is 10
);

$attributeId = $installer->getAttributeId($entityTypeId, 'new_cat_attrb');

$installer->run("
		INSERT INTO `{$installer->getTable('catalog_category_entity_int')}`
		(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
		SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, '1'
		FROM `{$installer->getTable('catalog_category_entity')}`;
		");

		//this will set data of your custom attribute for root category
		/*Mage::getModel('catalog/category')
		->load(1)
		->setImportedCatId(0)
		->setInitialSetupFlag(true)
		->save();*/

		//this will set data of your custom attribute for default category
		/*Mage::getModel('catalog/category')
				->load(2)
				->setImportedCatId(0)
				->setInitialSetupFlag(true)
				->save();*/

				$installer->endSetup();