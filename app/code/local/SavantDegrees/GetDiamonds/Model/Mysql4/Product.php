<?php

class SavantDegrees_GetDiamonds_Model_Mysql4_Product extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
    	$this->_isPkAutoIncrement = false;
        $this->_init('product/product', 'lot');
        
        
    }
}