<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Mysql4_Webforms_Collection
	extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	
	public function _construct(){
		parent::_construct();
		$this->_init('webforms/webforms');
	}
	
	protected function _afterLoad()
	{
		parent::_afterLoad();
		
		foreach ($this as $item) {
			$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id',$item->getId())->count();
			$item->setData('fields',$fields);
			
			$results = Mage::getModel('webforms/results')->getCollection()->addFilter('webform_id',$item->getId())->count();
			$item->setData('results',$results);
			
			// get last result time
			$last_result = Mage::getModel('webforms/results')->getCollection()->addFilter('webform_id',$item->getId());
			$last_result->getSelect()->order('created_time desc')->limit(1);
			
			$item->setData('last_result_time',$last_result->getFirstItem()->getData('created_time'));
		}

		Mage::dispatchEvent('webforms_collection_after_load',array('collection'=>$this));

		return $this;
	}
	
	
}  
?>
