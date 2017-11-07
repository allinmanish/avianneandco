<?php
/**
 * @copyright  Copyright (c) 2010 Amasty (http://www.amasty.com)
 */ 
class Amasty_Coupons_Model_Observer 
{
    public function handleSalesOrderPlaceAfter($observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order) {
            return $this;
        }
        // default handler works well with 1 code, we don't need to hnabge anything
        if (!strpos($order->getCouponCode(), ','))
            return $this;
        $customerId = $order->getCustomerId();

        if (version_compare(Mage::getVersion(), '1.4.1.0', '>='))
        {    
            $coupon = Mage::getModel('salesrule/coupon');
            foreach (explode(',', $order->getCouponCode()) as $code){
                $coupon->load($code, 'code');
                if ($coupon->getId()) {
                    $coupon->setTimesUsed($coupon->getTimesUsed() + 1);
                    $coupon->save();
                    if ($customerId) {
                        $couponUsage = Mage::getResourceModel('salesrule/coupon_usage');
                        $couponUsage->updateCustomerCouponTimesUsed($customerId, $coupon->getId());
                    }
                }            
            }
        }      
 
        return $this;
    }
}

