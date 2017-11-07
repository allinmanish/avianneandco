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
	
class GoMage_Feed_Model_Adminhtml_System_Config_Source_Filter{

	const TOGETHER = 0;       
	const SPLIT = 1;
	
    public function toOptionArray()
    {    	
    	$helper = Mage::helper('gomage_feed');
    	
       	return array(
            	array('label'=>$helper->__('Together'), 'value'=>self::TOGETHER),
            	array('label'=>$helper->__('Split'), 'value'=>self::SPLIT),            	
            );
    }
    
}