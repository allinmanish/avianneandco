<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Fieldsets extends Mage_Core_Model_Abstract{

	public function _construct()
	{
		parent::_construct();
		$this->_init('webforms/fieldsets');
	}
	
	public function duplicate(){
		// duplicate fieldset
		$fieldset = Mage::getModel('webforms/fieldsets')
			->setData($this->getData())
			->setId(null)
			->setName($this->getName().' '.Mage::helper('webforms')->__('(new copy)'))
			->setIsActive(false)
			->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())
			->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())
			->save();
			
		// duplicate fields
		$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('fieldset_id',$this->getId());
		foreach($fields as $field){
			$field->duplicate()
				->setFieldsetId($fieldset->getId())
				->save();
		}
		
		return $fieldset;
	}
}
?>
