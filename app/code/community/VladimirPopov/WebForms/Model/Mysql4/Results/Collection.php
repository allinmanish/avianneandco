<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Mysql4_Results_Collection
	extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	
	protected $filtered_values;
	
	public function _construct(){
		parent::_construct();
		$this->_init('webforms/results');
	}
	
	protected function _afterLoad()
	{
		parent::_afterLoad(); 
		foreach ($this as $item) {
			$query = $this->getConnection()->select()
				->from($this->getTable('webforms/results_values'))
				->where($this->getTable('webforms/results_values').'.result_id = '.$item->getId())
				;	
			$results = $this->getConnection()->fetchAll($query);
			foreach($results as $result){
				$item->setData('field_'.$result['field_id'],trim($result['value']));
			}
			
			$item->setData('ip',long2ip($item->getCustomerIp()));
			
			/*if($item->getCustomerId()){
				$item->setData('customer',Mage::getModel('customer/customer')->load($item->getCustomerId())->getName());
			} else {
				$item->setData('customer',Mage::helper('webforms')->__('Guest'));
			}*/
		}
		
		Mage::dispatchEvent('webforms_results_collection_load',array('collection'=>$this));

		return $this;
	}
	
	public function addFieldFilter($field_id,$value){
		$cond = "results_values_$field_id.value like '%".trim(str_replace("'","\\'",$value))."%'";
		$field = Mage::getModel('webforms/fields')->load($field_id);
		if(is_array($value)){
			if(strstr($field->getType(), 'date')){
				if($value['from']) $value['from'] = "'".date($field->getDbDateFormat(), strtotime($value['orig_from']))."'";
				if($value['to']) $value['to'] = "'".date($field->getDbDateFormat(), strtotime($value['orig_to']))."'";
			}
			if($value['from']){
				$cond = "results_values_$field_id.value >= $value[from]";
			}
			if($value['to']){
				$cond = "results_values_$field_id.value <= $value[to]";
			}
			if($value['from'] && $value['to']){
				$cond = "results_values_$field_id.value >= $value[from] AND results_values_$field_id.value <= $value[to]";
			}
		} 
		
		$this->getSelect()
			->join(array('results_values_'.$field_id => $this->getTable('webforms/results_values')),'main_table.id = results_values_'.$field_id.'.result_id',array('main_table.*'))
			->group('main_table.id')
			;
		
		$this->getSelect()
			->where("results_values_$field_id.field_id = $field_id AND $cond");
					
		return $this;
	}
}  
?>
