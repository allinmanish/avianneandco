<?php
/**
 * Amazon Payments
 *
 * @category    Amazon
 * @package     Amazon_Payments
 * @copyright   Copyright (c) 2014 Amazon.com
 * @license     http://opensource.org/licenses/Apache-2.0  Apache License, Version 2.0
 */

class Amazon_Payments_Block_Button extends Mage_Core_Block_Template
{
    /**
     * Return URL to use for checkout
     */
    public function getCheckoutUrl()
    {
        return $this->helper('amazon_payments')->getCheckoutUrl();
    }

    /**
     * Return onepage checkout URL
     */
    public function getOnepageCheckoutUrl()
    {
        return $this->helper('amazon_payments')->getOnepageCheckoutUrl();
    }

    /**
     * Return CSS identifier to use for Amazon button
     */
    public function getAmazonPayButtonId() {
        return $this->getNameInLayout();
    }

    /**
     * Return seller ID
     */
    public function getSellerId()
    {
        return $this->helper('amazon_payments')->getSellerId();
    }

    /**
     * Get login auth URL
     */
    public function getLoginAuthUrl()
    {
         return $this->getUrl('amazon_payments/checkout/authorize', array('_forced_secure'=>true));
    }

    /**
     * Get additional login scope
     */
    public function getAdditionalScope()
    {
         return $this->helper('amazon_payments')->getAdditionalScope();
    }

    /**
     * Get button type
     */
    public function getButtonType()
    {
         return Mage::getSingleton('amazon_payments/config')->getButtonType();
    }

    /**
     * Get button size
     */
    public function getButtonSize()
    {
         return Mage::getSingleton('amazon_payments/config')->getButtonSize();
    }

    /**
     * Get button color
     */
    public function getButtonColor()
    {
         return Mage::getSingleton('amazon_payments/config')->getButtonColor();
    }

    /**
     * Get language
     */
    public function getLanguage()
    {
         return Mage::helper('amazon_payments')->getLanguage();
    }

    /**
     * Retrieve ajax cart popup modal URL
     */
    public function getAjaxCartModalUrl()
    {
        return $this->helper('amazon_payments')->getAjaxCartModalUrl();
    }

    /**
     * Is Disabled?
     *
     * @return bool
     */
    public function isDisabled()
    {
        return !Mage::getSingleton('checkout/session')->getQuote()->validateMinimumAmount();
    }

    /**
     * Is Amazon Login enabled?
     *
     * @return bool
     */
    public function isAmazonLoginEnabled()
    {
        return $this->helper('amazon_payments')->isLoginEnabled();
    }

    /**
     * Is button enabled?
     *
     * @return bool
     */
    public function isAmazonPayButtonEnabled()
    {
        if (!Mage::getSingleton('amazon_payments/config')->isEnabled()) {
            return false;
        }
        // Viewing single product
        else if (Mage::registry('current_product')) {
             return $this->helper('amazon_payments')->isEnableProductPayments();
        }
        else {
            return ($this->helper('amazon_payments')->isEnableProductPayments() && (!Mage::getSingleton('amazon_payments/config')->isCheckoutOnepage() || Mage::getSingleton('amazon_payments/config')->showPayOnCart()));
        }
    }

    /**
     * Is button badge enabled?
     *
     * @return bool
     */
    public function isButtonBadgeEnabled()
    {
        return $this->helper('amazon_payments')->isButtonBadgeEnabled();
    }

    /**
     * Is Amazon Payments enabled on product level?
     */
    public function isEnableProductPayments()
    {
        return $this->helper('amazon_payments')->isEnableProductPayments();
    }

    /**
     * Is popup window?
     *
     * @return bool
     */
    public function isPopup()
    {
        // Use redirect for sidecart/minicart pay button
        if ($this->getNameInLayout() == 'AmazonPayButtonSideCart'
            && !Mage::app()->getStore()->isCurrentlySecure()
            ) {
            return 0;
        }

        return ($this->helper('amazon_payments')->isPopup());
    }

    /**
     * Is tokenized payments enabled?
     *
     * @return bool
     */
    public function isTokenEnabled()
    {
        return Mage::getSingleton('amazon_payments/config')->isTokenEnabled();
    }

}
