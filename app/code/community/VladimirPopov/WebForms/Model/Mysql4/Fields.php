<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Mysql4_Fields
	extends Mage_Core_Model_Mysql4_Abstract
{

	public function _construct(){
		$this->_init('webforms/fields','id');
	}
	
	protected function _afterSave(Mage_Core_Model_Abstract $object){
		
		Mage::dispatchEvent('webforms_field_save',array('field'=>$object));
		
		return parent::_afterSave($object);
	}
	
	protected function _afterDelete(Mage_Core_Model_Abstract $object){
		//delete values
		$values = $this->_getReadAdapter()->delete($this->getTable('webforms/results_values'),'field_id ='. $object->getId());	

		Mage::dispatchEvent('webforms_field_delete',array('field'=>$object));

		return parent::_afterDelete($object);
	}
}  
?>
