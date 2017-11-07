<?php
/**
 * Amazon Payments
 *
 * @category    Amazon
 * @package     Amazon_Payments
 * @copyright   Copyright (c) 2014 Amazon.com
 * @license     http://opensource.org/licenses/Apache-2.0  Apache License, Version 2.0
 */

class Amazon_Payments_Block_Sales_View extends Mage_Sales_Block_Order_View
{

    /**
     * Is order on hold and suspended?
     */
    protected function isSuspended()
    {
        if ($this->getOrder()->getPayment()->getMethod() == 'amazon_payments' && $this->getOrder()->getState() == Mage_Sales_Model_Order::STATE_HOLDED) {

            $_api = Mage::getModel('amazon_payments/api')->setStore($this->getOrder()->getStoreId());
            $orderReferenceDetails = $_api->getOrderReferenceDetails($this->getAmazonOrderReferenceId());

            if ($orderReferenceDetails->getOrderReferenceStatus()->getState() == Amazon_Payments_Model_Api::AUTH_STATUS_SUSPENDED) {
                return true;
            }
        }
        return false;

    }

    /**
     * Return Amazon Order Reference Id
     */
    protected function getAmazonOrderReferenceId()
    {
       return $this->getOrder()->getPayment()->getAdditionalInformation('order_reference');
    }

    /**
     * Return seller ID
     */
    public function getSellerId()
    {
        return $this->helper('amazon_payments')->getSellerId();
    }


}
