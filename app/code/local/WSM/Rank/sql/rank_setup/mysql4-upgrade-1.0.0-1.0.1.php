<?php 
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$entityTypeId = $installer->getEntityTypeId('catalog_product');

$idAttributeOldSelect = $installer->getAttribute($entityTypeId, 'rank', 'attribute_id');

// $installer->updateAttribute($entityTypeId,$idAttributeOldSelect, 'frontend_input','multiselect');

$installer->updateAttribute($entityTypeId,$idAttributeOldSelect, 'backend_type','int');

// $installer->updateAttribute($entityTypeId,$idAttributeOldSelect, 'backend_model','eav/entity_attribute_backend_array');

// $installer->updateAttribute($entityTypeId,$idAttributeOldSelect, 'source_model','');

// $installer->run("
// 		INSERT INTO `catalog_product_entity_int` (`entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) 
// 		SELECT `entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value` FROM `catalog_product_entity_varchar` 
// 		WHERE `attribute_id` = {$idAttributeOldSelect}
// ");

$installer->endSetup();
?>