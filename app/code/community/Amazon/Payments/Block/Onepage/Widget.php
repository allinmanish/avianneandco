<?php
/**
 * Amazon Payments
 *
 * @category    Amazon
 * @package     Amazon_Payments
 * @copyright   Copyright (c) 2014 Amazon.com
 * @license     http://opensource.org/licenses/Apache-2.0  Apache License, Version 2.0
 */

class Amazon_Payments_Block_Onepage_Widget extends Mage_Checkout_Block_Onepage_Abstract
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('widget', array(
            'label'     => Mage::helper('checkout')->__('Pay with Amazon'),
            'is_show'   => $this->isShow()
        ));
        parent::_construct();

        $this->getCheckout()->setStepData('shipping', 'is_show', true); // Display "Shipping Information" progress widget

        // Reset checkout steps if switching from OnePage core
        $this->getCheckout()->setStepData('billing', 'is_show', false);
        $this->getCheckout()->setStepData('payment', 'is_show', false);
    }

    /**
     * Return seller ID
     */
    public function getSellerId()
    {
        return $this->helper('amazon_payments')->getSellerId();
    }

    /**
     * Show shipping widget?
     */
    public function isShowShip()
    {
        return true;
        // Mage_Sales_Model_Service_Quote require billing address.
        //return !$this->getQuote()->isVirtual();
    }

    /**
     * Get additional form HTML
     *
     * @return string
     */
    public function getAdditionalForm()
    {
        $output = '';
        if (Mage::helper('core')->isModuleEnabled('Amasty_Customerattr')) {
            $output .= Mage::helper('amcustomerattr')->fields();
        }
        return $output;
    }

    /**
     *  Newsletter module availability
     *
     *  @return boolean
     */
    public function isNewsletterEnabled()
    {
        return (Mage::helper('core')->isModuleOutputEnabled('Mage_Newsletter') &&
                !Mage::getModel('newsletter/subscriber')->loadByCustomer(Mage::getSingleton('customer/session')->getCustomer())->isSubscribed());

    }

    /**
     * Is tokenized payments enabled?
     */
    public function isTokenEnabled()
    {
        return Mage::getSingleton('amazon_payments/config')->isTokenEnabled();
    }

    /**
     * Is tokenized payments required?
     */
    public function isTokenRequired()
    {
        return $this->isTokenEnabled() && Mage::getSingleton('amazon_payments/config')->isTokenRequired();
    }

}