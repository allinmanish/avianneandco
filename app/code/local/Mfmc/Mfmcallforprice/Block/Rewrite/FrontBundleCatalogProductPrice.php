<?php

class Mfmc_mfmcallforprice_Block_Rewrite_FrontBundleCatalogProductPrice extends Mage_Bundle_Block_Catalog_Product_Price
{
    // mfmc rewrite getPriceHtml for displaying 'call for price' string
    public function _afterToHtml($html) {
        if(Mage::helper('mfmcallforprice')->getCallForPriceStr($this->getProduct())) {
            return '';
        }
        return $html;
    }

}
