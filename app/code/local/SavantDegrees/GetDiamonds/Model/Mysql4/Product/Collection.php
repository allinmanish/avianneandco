<?php
class SavantDegrees_GetDiamonds_Model_Mysql4_Product_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{ 
    public function __construct()
    {
    	parent::_construct();
		$this->_init('product/product');
    }
}