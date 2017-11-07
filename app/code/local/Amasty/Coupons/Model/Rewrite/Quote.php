<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Coupons
*/
class Amasty_Coupons_Model_Rewrite_Quote extends Mage_Sales_Model_Quote
{
    public function setCouponCode($coupon)
    {
        if (is_array($coupon)){ // set as is
            $couponCode = implode(',', $coupon);
        } 
        elseif ($coupon){ // add to existing
            $appliedCoupons = explode(',', $this->getCouponCode(true));
            if (!in_array($coupon, $appliedCoupons)){
                $appliedCoupons[] = $coupon;
            } 
            $couponCode = implode(',', $appliedCoupons);
        } 
        else {
            $couponCode = '';
        }
        
        // double checking if all have values
        $allCoupons = explode(',', $couponCode);
        foreach ($allCoupons as $i => $coupon){
            if (!$coupon){
                unset($allCoupons[$i]);
            }
        }
        // double checking if all are unique
        $allCoupons = array_unique($allCoupons); 
        
        $specialCoupons = explode(",", str_replace(array(' ',';'),'', Mage::getStoreConfig('amcoupons/codes/code', $this->getStoreId())));
        $specialCoupons = array_unique($specialCoupons);
        $result = array_intersect($specialCoupons, $allCoupons);
        if (!empty($result)) {
            $allCoupons = array($result[0]); 
        }

        $couponCode = implode(',', $allCoupons);
        $this->setData('coupon_code', $couponCode);
        return $this;
    }
    
    public function getCouponCode($all = false)
    {
        if (in_array(Mage::app()->getRequest()->getActionName(), array('couponPost', 'add_coupon'))){
            if ($all){
                return parent::getData('coupon_code');
            }
            $coupons = explode(',', parent::getData('coupon_code'));
            if (count($coupons)){
                //return the last added, for the right validation
                return $coupons[count($coupons) - 1];
            }
        }
        return parent::getData('coupon_code');
    }
    
    public function getAppliedCoupons()
    {
        $coupons    = array();
        $couponCode = $this->getCouponCode();
        if ($couponCode) {
            $coupons = explode(',', $couponCode);
        }
        
        foreach ($coupons as $i => $coupon) {
            if (!$coupon) {
                unset($coupons[$i]);
            }
        }
        
        return $coupons;
    }
    
    protected function _validateCouponCode()
    {
        $codes = $this->getAppliedCoupons();
        if ($codes) {
            $addressHasCoupon = false;
            $addresses = $this->getAllAddresses();
            if (count($addresses)>0) {
                foreach ($addresses as $address) {
                    if ($address->hasCouponCode()) {
                        $addressHasCoupon = true;
                    }
                }
                if (!$addressHasCoupon) {
                    $coupons = explode(',', $this->getCouponCode(true));
                    if (count($coupons)){
                        array_pop($coupons);
                        $coupons = implode(',', $coupons);
                        $this->setData('coupon_code', $coupons);
                    }
                    else {
                        $this->setData('coupon_code', '');
                    }
                }
            }
        }
        
        return $this;
    }
}