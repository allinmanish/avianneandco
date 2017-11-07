<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Mysql4_Results
	extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct(){
		$this->_init('webforms/results','id');
	}
	
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
		
		if (! $object->getId() && $object->getCreatedTime() == "") {
			$object->setCreatedTime(Mage::getSingleton('core/date')->gmtDate());
		}
		
		$object->setUpdateTime(Mage::getSingleton('core/date')->gmtDate());
		
		return $this;
	}
	
	protected function _afterSave(Mage_Core_Model_Abstract $object){
		//insert field values
		if(count($object->getData('field'))>0){
			foreach($object->getData('field') as $field_id=>$value){
				if(is_array($value)){
					$value = implode("\n",$value);
				}
				$field = Mage::getModel('webforms/fields')->load($field_id);
				if(strstr($field->getType(), 'date') && strlen($value)>0){
					$date = new Zend_Date();
					$date->setDate($value, $field->getDateFormat(), Mage::app()->getLocale()->getLocaleCode());
					if($field->getType() == 'datetime')
						$date->setTime($value, $field->getDateFormat(), Mage::app()->getLocale()->getLocaleCode());
					$value = date($field->getDbDateFormat(), $date->getTimestamp());
				}
				if($field->getType() == 'select/contact' && is_numeric($value)){
					$value = $field->getContactValueById($value);
				}
				$select = $this->_getReadAdapter()->select()
					->from($this->getTable('webforms/results_values'))
					->where('result_id = ?', $object->getId())
					->where('field_id = ?', $field_id);	
				$result_value = $this->_getReadAdapter()->fetchAll($select);
				if(!empty($result_value[0])){
					$this->_getWriteAdapter()->update($this->getTable('webforms/results_values'),array(
						"value" => $value,
						),						
						"id = ". $result_value[0]['id']
					);
					
				} else {
					$this->_getWriteAdapter()->insert($this->getTable('webforms/results_values'),array(
						"result_id" => $object->getId(),
						"field_id" => $field_id,
						"value" => $value,
					));
				}
			}
		}
		
		Mage::dispatchEvent('webforms_result_save',array('result'=>$object));

		return parent::_afterSave($object);
	}
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object){
		$webform = Mage::getModel('webforms/webforms')->load($object->getData('webform_id'));
		$fields_to_fieldsets = $webform->getFieldsToFieldsets();

		$select = $this->_getReadAdapter()->select()
			->from($this->getTable('webforms/results_values'))
			->where('result_id = ?', $object->getId());	
		$values = $this->_getReadAdapter()->fetchAll($select);
		
		foreach($values as $val){
			$object->setData('field_'.$val['field_id'],$val['value']);
		}
		
		$object->setData('ip',long2ip($object->getCustomerIp()));
		
		Mage::dispatchEvent('webforms_result_load',array('webform'=>$webform,'result'=>$object));
		
		return parent::_afterLoad($object);
	}
	
	protected function _afterDelete(Mage_Core_Model_Abstract $object){
		//delete values
		$values = $this->_getReadAdapter()->delete($this->getTable('webforms/results_values'),
			'result_id = '. $object->getId()
		);	
		$dir =  Mage::getBaseDir('media') . DS . 'webforms' . DS . $object->getId() ;
		$this->rrmdir($dir);
		
		Mage::dispatchEvent('webforms_result_delete',array('result'=>$object));
		
		return parent::_afterDelete($object);
	}
	
	// this function helps delete folder recursively
	public function rrmdir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir); 
			foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
					if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object); 
			} 
		} 
		reset($objects); 
		rmdir($dir); 
		} 
   } 
}  
?>
