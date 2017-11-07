<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Mysql4_Webforms
	extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('webforms/webforms','id');
	}
	
	protected function _afterSave(Mage_Core_Model_Abstract $object){
		
		Mage::dispatchEvent('webforms_after_save',array('webform'=>$object));
		
		return parent::_afterSave($object);
	}
	
	protected function _afterDelete(Mage_Core_Model_Abstract $object){
		//delete fields
		$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id',$object->getId());
		foreach($fields as $field){
			$field->delete();
		}
		//delete fieldsets
		$fieldsets = Mage::getModel('webforms/fieldsets')->getCollection()->addFilter('webform_id',$object->getId());
		foreach($fieldsets as $fieldset){
			$fieldset->delete();
		}
		
		Mage::dispatchEvent('webforms_after_delete',array('webform'=>$object));

		return parent::_afterDelete($object);
	}
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object)
	{		
		Mage::dispatchEvent('webforms_after_load',array('webform' => $object));
		
		return parent::_afterLoad($object);
	}

}  
?>