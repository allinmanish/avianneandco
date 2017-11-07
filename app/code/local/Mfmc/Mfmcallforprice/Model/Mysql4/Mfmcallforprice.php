<?php
/**
* @copyright  Copyright (c) 2009 Modules For Magento Inc. 
*/

class Mfmc_mfmcallforprice_Model_Mysql4_Mfmcallforprice extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('mfmcallforprice/mfmcallforprice', 'id');
    }

}