<?php

class Mfmc_mfmcallforprice_Block_Rewrite_FrontBundleCatalogProductViewTypeBundle extends Mage_Bundle_Block_Catalog_Product_View_Type_Bundle
{
    // mfmc rewrite getPriceHtml for displaying 'call for price' string
    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix='')
    {
        if($html = Mage::helper('mfmcallforprice')->getCallForPriceStr($product)) {
            return $html;
        }
        
        return $this->_getPriceBlock($product->getTypeId())
            ->setTemplate($this->_getPriceBlockTemplate($product->getTypeId()))
            ->setProduct($product)
            ->setDisplayMinimalPrice($displayMinimalPrice)
            ->setIdSuffix($idSuffix)
            ->toHtml();
    }

}
