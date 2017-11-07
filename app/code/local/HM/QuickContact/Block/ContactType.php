<?php
class HM_QuickContact_Block_ContactType extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('type', array(
            'label' => Mage::helper('adminhtml')->__('Contact Type'),
            'style' => 'width:120px',
        ));
        $this->addColumn('email', array(
            'label' => Mage::helper('adminhtml')->__('Send Emails To'),
            'style' => 'width:160px',
        ));
		$this->addColumn('title', array(
            'label' => Mage::helper('adminhtml')->__('Title'),
            'style' => 'width:200px',
        ));		
						 
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Contact Type');
        parent::__construct();
    }
}