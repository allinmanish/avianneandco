<?php
class Livechat_Livechat_Block_Adminhtml_Livechat extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  
  public function __construct()
  {
	$this->_controller = 'adminhtml_livechat';
    $this->_blockGroup = 'livechat';
	$this->_headerText = Mage::helper('livechat')->__('LiveChat Configuration');
 	$this->_addButtonLabel = Mage::helper('livechat')->__('Create Account');
	$this->_addButton('save', array(
		'label'     => Mage::helper('livechat')->__('Save'),
		'onclick'   => "jQuery('#livechat_already_have form').submit()",
		'class'     => 'save',
	));

    parent::__construct();
  }
}