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
 * @since        Class available since Release 1.0
 */

class GoMage_Feed_Block_Adminhtml_Helper_Labeldate extends Varien_Data_Form_Element_Label {
	
	public function getValue($index = null) {
		
		$value = parent::getValue($index);
		
		if ('0000-00-00 00:00:00' == $value || ! $value) {
			return '';
		}
		
		$format = $this->_getFormat();
		try {
			$value = Mage::app()->getLocale()->date($value, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
		}
		catch (Exception $e) {
			$value = Mage::app()->getLocale()->date($value, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
		}
		
		return $value;
	}
	
	protected function _getFormat() {
		return Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);	
	}

}
