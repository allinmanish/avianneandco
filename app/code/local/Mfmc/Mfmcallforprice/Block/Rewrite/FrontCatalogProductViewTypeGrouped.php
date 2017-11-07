<?php

class Mfmc_mfmcallforprice_Block_Rewrite_FrontCatalogProductViewTypeGrouped extends Mage_Catalog_Block_Product_View_Type_Grouped
{
    //public function _prepareLayout() {
    protected function _afterToHtml($html) {
        $product = $this->getProduct();
        Mage::helper('mfmcallforprice')->getCallForPriceStr($product);
        return $html;
    }
    
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
