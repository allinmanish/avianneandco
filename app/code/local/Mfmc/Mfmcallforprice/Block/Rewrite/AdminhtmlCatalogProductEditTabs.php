<?php

class Mfmc_mfmcallforprice_Block_Rewrite_AdminhtmlCatalogProductEditTabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        $product = $this->getProduct();

        if (!($setId = $product->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }        
        if ($setId) {
            $this->addTab('mfmcallforprice', array(
                'label'     => Mage::helper('mfmcallforprice')->__('Call For Price'),
                'content'   => $this->getLayout()->createBlock('mfmcallforprice/catalog_product_edit_tab_mfmcallforprice')->toHtml(),
            ));
        }
    }
}