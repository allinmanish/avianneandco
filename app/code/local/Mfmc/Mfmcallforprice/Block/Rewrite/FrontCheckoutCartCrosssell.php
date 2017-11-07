<?php

class Mfmc_mfmcallforprice_Block_Rewrite_FrontCheckoutCartCrosssell extends Mage_Checkout_Block_Cart_Crosssell
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


    public function getItems()
    {
        $items = $this->getData('items');
        if (is_null($items)) {
            $items = array();
            if ($ninProductIds = $this->_getCartProductIds()) {
                if ($lastAdded = (int) $this->_getLastAddedProductId()) {
                    $collection = $this->_getCollection()
                        ->addProductFilter($lastAdded);
                    if (!empty($ninProductIds)) {
                        $collection->addExcludeProductFilter($ninProductIds);
                    }
                    $collection->load();

                    foreach ($collection as $item) {
                        // mfmc modification
                        if(is_null(Mage::helper('mfmcallforprice')->getCallForPriceStr($item))) {
                            $ninProductIds[] = $item->getId();
                            $items[] = $item;
                        }
                        // end mfmc mod
                    }
                }

                if (count($items)<$this->_maxItemCount) {
                    $collection = $this->_getCollection()
                        ->addProductFilter($this->_getCartProductIds())
                        ->addExcludeProductFilter($ninProductIds)
                        ->setPageSize($this->_maxItemCount-count($items))
                        ->setGroupBy()
                        ->setRandomOrder()
                        ->load();
                    foreach ($collection as $item) {
                        //$items[] = $item;
                        // mfmc modification
                        if(is_null(Mage::helper('mfmcallforprice')->getCallForPriceStr($item))) {
                            $items[] = $item;
                        }
                        // end mfmc mod
                    }
                }
            }

            $this->setData('items', $items);
        }
        return $items;
    }


}
