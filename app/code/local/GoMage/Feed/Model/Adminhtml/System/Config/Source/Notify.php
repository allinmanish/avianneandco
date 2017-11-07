<?php
/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2012 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.0
 * @since        Class available since Release 3.0
 */
	
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Notify{
        
    const ERRORS = 1;
    const SUCCESSFULLY_GENERATED = 2;
    const SUCCESSFULLY_UPLOADED = 3;
    
    public function toOptionArray()
    {    	
    	$helper = Mage::helper('gomage_feed');
    	
        return array(
            array('value' => self::ERRORS, 'label' => $helper->__('Errors')),
            array('value' => self::SUCCESSFULLY_GENERATED, 'label' => $helper->__('File Successfully Generated')),            
            array('value' => self::SUCCESSFULLY_UPLOADED, 'label' => $helper->__('File Successfully Uploaded')),
        );
    }
    
	public static function toOptionHash()
    {    	
    	$helper = Mage::helper('gomage_feed');
    	
        return array(
            self::ERRORS => $helper->__('Errors'),
            self::SUCCESSFULLY_GENERATED => $helper->__('File Successfully Generated'),            
            self::SUCCESSFULLY_UPLOADED => $helper->__('File Successfully Uploaded'),
        );
    }

}