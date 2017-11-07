<?php
/**
* @copyright  Copyright (c) 2009 Modules For Magento Inc. 
*/
class Mfmc_mfmcallforprice_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getCallForPriceStr($product) {
        $callForPrice = Mage::getModel('mfmcallforprice/mfmcallforprice')->load($product->getId(), 'product_id');
        if($callForPrice->getHidePrice())  {
        	$sp = $product->getSpecialPrice();
        	if(!empty($sp)) {
        		return null;
        	} else {
            	$product->setIsSalable(0);
//             	if( in_array( 211, $product->getCategoryIds() ) ) {
//             		$msg = 'Display only';
//             	} else {
            		$msg = $callForPrice->getMessage();
//             	}
            	$html = '<div class="out-of-stock">' . $msg . '</div>';
            	return $html;
        	}
            
        }
        else {
            if(!$product->getIsSalable()) {
                $html = '<div class="out-of-stock">' . Mage::helper('mfmcallforprice')->__('&nbsp;') . '</div>';
                return $html;
            }
        }
        return null;
    }
}