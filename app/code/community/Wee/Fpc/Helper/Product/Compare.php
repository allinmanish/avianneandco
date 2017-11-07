<?php

class Wee_Fpc_Helper_Product_Compare extends Mage_Catalog_Helper_Product_Compare
{
   public function setItemCollection(array $items)
   {
       $this->_itemCollection = $items;
   }

   public function getItemsFromSession()
   {
        $items = array();
        $customerSession = Mage::getSingleton('customer/session');
        $itemsFromSession = (array)$customerSession->getComparedProducts();
        foreach ($itemsFromSession as $itemId) {
            $product = Mage::getModel('catalog/product')->load($itemId);
            if ($product->getId()) {
                $items[$product->getId()] = $product;
            }
        }
        return $items;
   }
   
   public function getListUrl()
   {
       $itemIds = array();
       $customerSession = Mage::getSingleton('customer/session');
       $itemsFromSession = (array)$customerSession->getComparedProducts();
        
       if ($itemsFromSession) {
           foreach ($itemsFromSession as $itemId) {
               $itemIds[] = $itemId;
           }
       } else {
           foreach ($this->getItemCollection() as $item) {
               $itemIds[] = $item->getId();
           }
       }
   
       $params = array(
               'items'=>implode(',', $itemIds),
               Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->getEncodedUrl()
       );
       return $this->_getUrl('catalog/product_compare', $params);
   }
}