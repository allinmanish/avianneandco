<?php
 /**
 * GoMage LightCheckout Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2012 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.2
 * @since        Class available since Release 3.1
 */

class GoMage_PayOne_Helper_Data extends Mage_Core_Helper_Abstract{

    public function isGoMage_PayOneEnabled()
    {
       $_modules = Mage::getConfig()->getNode('modules')->children();
       	   	   
	   $_modulesArray = (array)$_modules;
	   
	   if(!isset($_modulesArray['GoMage_PayOne'])) return false;
	   if (!$_modulesArray['GoMage_PayOne']->is('active')) return false;

       if(!isset($_modulesArray['Mage_Payone'])) return false;	   
	   if (!$_modulesArray['Mage_Payone']->is('active')) return false;

	   return true;	   
    }	
    
}
