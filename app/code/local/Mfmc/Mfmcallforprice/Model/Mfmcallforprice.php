<?php
/**
* @copyright  Copyright (c) 2009 Modules For Magento Inc. 
*/

class Mfmc_mfmcallforprice_Model_Mfmcallforprice extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mfmcallforprice/mfmcallforprice');
    }
}

?>