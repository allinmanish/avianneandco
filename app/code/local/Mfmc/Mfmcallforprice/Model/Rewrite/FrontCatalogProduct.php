<?php

class Mfmc_mfmcallforprice_Model_Rewrite_FrontCatalogProduct extends Mage_Catalog_Model_Product
{

    public function _afterLoad() {
        //Mage::helper('mfmcallforprice')->getCallForPriceStr($this);
    }

}