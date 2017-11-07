<?php
/**
 * Amazon Login
 *
 * @category    Amazon
 * @package     Amazon_Payments
 * @copyright   Copyright (c) 2014 Amazon.com
 * @license     http://opensource.org/licenses/Apache-2.0  Apache License, Version 2.0
 */

class Amazon_Payments_Block_Login_Script extends Mage_Core_Block_Template
{

    /**
     * Return Widgets.js URL
     */
    public function getWidgetsUrl()
    {
        switch (Mage::getStoreConfig('amazon_login/settings/region')) {
          case 'uk':
              $staticRegion = 'eu';
              $widgetRegion = 'uk';
              $lpa = 'lpa/';
              break;

          case 'de':
              $staticRegion = 'eu';
              $widgetRegion = 'de';
              $lpa = 'lpa/';
              break;

          // US
          default:
              $staticRegion = 'na';
              $widgetRegion = 'us';
              $lpa = '';
              break;
        }

        $sandbox = $this->isSandboxEnabled() ? 'sandbox/' : '';

        return "https://static-$staticRegion.payments-amazon.com/OffAmazonPayments/$widgetRegion/{$sandbox}{$lpa}js/Widgets.js?sellerId=" . $this->getSellerId();
    }

    /**
     * Is popup window?
     *
     * @return bool
     */
    public function isPopup()
    {
        return ($this->helper('amazon_payments')->isPopup());
    }

    /**
     * Is sandbox mode?
     */
    public function isSandboxEnabled()
    {
        return (Mage::getStoreConfig('payment/amazon_payments/sandbox'));
    }

    /**
     * Get client ID
     */
    public function getClientId()
    {
        return Mage::getModel('amazon_payments/config')->getClientId();
    }

    /**
     * Return seller ID
     */
    public function getSellerId()
    {
        return $this->helper('amazon_payments')->getSellerId();
    }

    /**
     * Get additional scope
     */
    public function getAdditionalScope()
    {
         return $this->helper('amazon_payments')->getAdditionalScope();
    }

    /**
     * Get login auth URL
     */
    public function getLoginAuthUrl()
    {
         return $this->helper('amazon_payments')->getLoginAuthUrl();
    }

    /**
     * Get region
     */
    public function getRegion()
    {
        switch ($this->helper('amazon_payments')->getRegion()) {
            case 'uk':
            case 'de':
                return 'EU';
            default:
                return 'NA'; // North America
        }
    }

    /**
     * Get language
     */
    public function getLanguage()
    {
        return Mage::getStoreConfig('payment/amazon_payments/language');
    }

    /**
     * Is Amazon Payments enabled?
     *
     * @return bool
     */
    public function isAmazonPaymentsEnabled()
    {
        return $this->helper('amazon_payments')->isEnabled();
    }

}
