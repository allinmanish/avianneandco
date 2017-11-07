<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Adminhtml_Webforms_Renderer_Fields extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		return $value.' [ <a href="#" style="text-decoration:none" onclick="setLocation(\''.$this->getAddFieldUrl($row).'\')">+</a> ]';
	 
	 }
	 
	 public function getAddFieldUrl(Varien_Object $row)
	 {
		 return $this->getUrl('*/adminhtml_fields/edit',array('webform_id'=>$row->getId()));
	 }
 
}
?>