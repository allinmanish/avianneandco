<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Adminhtml_Results_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		if($value){
			$output = "<a href='".$this->getCustomerUrl($row)."' target='_blank'>".Mage::getModel('customer/customer')->load($value)->getName()."</a>";
		} else{
			$output = Mage::helper('webforms')->__('Guest');
		}
		return $output;
	}

	 public function getCustomerUrl(Varien_Object $row)
	 {
		 
		 return $this->getUrl('adminhtml/customer/edit',array('id'=>$row->getCustomerId(),'_current'=>false));
	 }

}
  
?>
