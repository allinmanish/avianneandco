<?php
/**
* @copyright  Copyright (c) 2009 Modules For Magento Inc. 
*/

class Mfmc_mfmcallforprice_Block_Catalog_Product_Edit_Tab_Mfmcallforprice extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mfmcallforprice/catalog/product/tab/mfmcallforprice.phtml');
    }

    public function getProduct()
    {
        return Mage::registry('product');
    }

    public function getStockItem()
    {
        return Mage::registry('product')->getStockItem();
    }

    public function isConfigurable()
    {
        return Mage::registry('product')->isConfigurable();
    }

    public function isNew()
    {
        if (Mage::registry('product')->getId()) {
            return false;
        }
        return true;
    }

    public function getFieldSuffix()
    {
        return 'product';
    }


    public function loadCallForPriceData() {
        $productId = $this->getProduct()->getId();
        $data = Mage::getModel('mfmcallforprice/mfmcallforprice')->load($productId, 'product_id');
        return $data;
    }

} 