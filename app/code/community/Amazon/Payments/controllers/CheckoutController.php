<?php
/**
 * Amazon Payments Checkout Controller
 *
 * @category    Amazon
 * @package     Amazon_Payments
 * @copyright   Copyright (c) 2014 Amazon.com
 * @license     http://opensource.org/licenses/Apache-2.0  Apache License, Version 2.0
 */

class Amazon_Payments_CheckoutController extends Amazon_Payments_Controller_Checkout
{
    protected $_amazonOrderReferenceId;
    protected $_checkoutUrl = 'checkout/amazon_payments';

    /**
     * Checkout page
     */
    public function indexAction()
    {
        $quote = $this->_getCheckout()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message') ?
                Mage::getStoreConfig('sales/minimum_order/error_message') :
                Mage::helper('checkout')->__('Subtotal must exceed minimum order amount');

            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));

        $this->_getCheckout()->initCheckout();
        $this->loadLayout()
            ->_initLayoutMessages('checkout/session');

        // Ajax Modal
        if($this->getRequest()->getParam('ajax')){
            $this->getLayout()->getBlock('root')->setTemplate('page/popup.phtml');
        }

        // Add EE gift wrapping options
        if (Mage::helper('core')->isModuleEnabled('Enterprise_GiftWrapping')) {
            $block = $this->getLayout()
                ->createBlock('enterprise_giftwrapping/checkout_options', 'checkout.options')
                ->setTemplate('giftwrapping/checkout/options.phtml');
            $this->getLayout()->getBlock('content')->append($block);
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
    }

    /**
     * Authorize action for full-page redirects
     */
    public function authorizeAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Clear session and redirect to cart
     */
    public function clearAction()
    {
        $this->clearSession();
        $this->_redirect('checkout/cart');
    }

    /**
     * Order success action
     */
    public function successAction()
    {
        $session = $this->_getCheckout()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }

        $session->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }

    /**
     * Failure action
     */
    public function failureAction()
    {
        $lastQuoteId = $this->_getCheckout()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->_getCheckout()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Widget Address select action
     */
    public function addressSelectAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        $this->_saveShipping();
        $this->_getCheckout()->getQuote()->collectTotals()->save();

        $shipping_block = 'checkout_amazon_payments_shippingmethod';

        // Use ShipperHQ template for split rates
        if (Mage::helper('core')->isModuleEnabled('Shipperhq_Shipper')) {
            $splitRates = $this->_getCheckout()->getQuote()->getShippingAddress()->getSplitRates();
            if((Mage::helper('shipperhq_shipper')->isModuleEnabled('Shipperhq_Splitrates') && $splitRates == 1)
                || Mage::helper('core')->isModuleEnabled('Shipperhq_Pickup')) {
                    $shipping_block = 'checkout_amazon_payments_shippingmethod_shipperhq';
            }
        }

        $result = array(
            'shipping_method' => $this->_getBlockHtml($shipping_block),
            'review'          => $this->_getBlockHtml('checkout_amazon_payments_review'),
        );

        // Validate country
        if (!$this->isCountryAllowed($this->_getCheckout()->getQuote()->getShippingAddress()->getCountry())) {
            $result['shipping_method'] = $this->__('This order cannot be shipped to the selected country. Please use a different shipping address.');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Shipping method action
     */
    public function shippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        $this->_saveShipping();

        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * Additional options action (e.g. gift options)
     */
    public function additionalAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        if ($this->getRequest()->isPost()) {
            Mage::dispatchEvent(
                'checkout_controller_onepage_save_shipping_method',
                array(
                    'request' => $this->getRequest(),
                    'quote'   => $this->_getCheckout()->getQuote()));

            $this->_getCheckout()->getQuote()->collectTotals()->save();

        }

        $this->getResponse()->setBody($this->_getBlockHtml('checkout_amazon_payments_review'));
    }


    /**
     * Review page action
     */
    public function reviewAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        if ($this->_getOnepage()->getQuote()->isVirtual()) {
            $this->_saveShipping();
        }

        if ($data = $this->getRequest()->getParam('shipping_method', '')) {
            $result = $this->_getCheckout()->saveShippingMethod($data);
            $this->_getCheckout()->getQuote()->collectTotals()->save();
        }

        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * Shipping address save action
     */
    public function saveShippingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {

            $result = $this->_saveShipping();

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    /**
     * Shipping method save action
     */
    public function saveShippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->_getCheckout()->saveShippingMethod($data);
            // $result will contain error data if shipping method is empty
            if (!$result) {
                Mage::dispatchEvent(
                    'checkout_controller_onepage_save_shipping_method',
                     array(
                          'request' => $this->getRequest(),
                          'quote'   => $this->_getCheckout()->getQuote()));
                $this->_getCheckout()->getQuote()->collectTotals();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            $this->_getCheckout()->getQuote()->collectTotals()->save();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }


    /**
     * Create order action
     */
    public function saveOrderAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        $result = array();

        try {
            $requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();
            if ($requiredAgreements) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                $diff = array_diff($requiredAgreements, $postedAgreements);
                if ($diff) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }

            // Validate shipping method
            if (!$this->_getCheckout()->getQuote()->isVirtual()) {
                $address = $this->_getCheckout()->getQuote()->getShippingAddress();
                $method  = $address->getShippingMethod();
                $rate    = $address->getShippingRateByCode($method);
                if (!$this->_getCheckout()->getQuote()->isVirtual() && (!$method || !$rate)) {
                    Mage::throwException(Mage::helper('sales')->__('Please specify a shipping method.'));
                }
            }


            $additional_information = array();

            if ($this->getAmazonBillingAgreementId()) {
                $additional_information['billing_agreement_id'] = $this->getAmazonBillingAgreementId();
                $additional_information['billing_agreement_consent'] = $this->getAmazonBillingAgreementConsent();
            }
            else {
                $additional_information['order_reference'] = $this->getAmazonOrderReferenceId();
            }

            if ($this->getRequest()->getPost('sandbox')) {
                $additional_information['sandbox'] = $this->getRequest()->getPost('sandbox');
            }

            $this->_getCheckout()->savePayment(array(
                'method' => 'amazon_payments',
                'additional_information' => $additional_information,
            ));

            $this->_getCheckout()->saveOrder();
            $this->_getCheckout()->getQuote()->save();

            $redirectUrl = Mage::getUrl('checkout/onepage/success');
            $result['success'] = true;
            $result['error']   = false;
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if (!empty($message)) {
                $result['error_messages'] = $message;
            }
            $result['goto_section'] = 'payment';
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->_getCheckout()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            $gotoSection = $this->_getCheckout()->getCheckout()->getGotoSection();
            if ($gotoSection) {
                $result['goto_section'] = $gotoSection;
                $this->_getCheckout()->getCheckout()->setGotoSection(null);
            }
            $updateSection = $this->_getCheckout()->getCheckout()->getUpdateSection();
            if ($updateSection) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->_getCheckout()->getCheckout()->setUpdateSection(null);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->_getCheckout()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->_getCheckout()->getQuote()->save();

        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Initialize coupon
     */
    public function couponPostAction()
    {

        $couponCode = (string) $this->getRequest()->getParam('coupon_code');
        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = $this->_getQuote()->getCouponCode();

        try {
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();

            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $this->_getQuote()->getCouponCode()) {
                    $this->_getSession()->addSuccess(
                        $this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                } else {
                    $this->_getSession()->addError(
                        $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                }
            } else {
                $this->_getSession()->addSuccess($this->__('Coupon code was canceled.'));
            }

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Cannot apply the coupon code.'));
            Mage::logException($e);
        }

        $this->_redirect($this->_checkoutUrl);
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    /**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Create invoice
     *
     * @return Mage_Sales_Model_Order_Invoice
     */
    protected function _initInvoice()
    {
        $items = array();
        foreach ($this->_getOrder()->getAllItems() as $item) {
            $items[$item->getId()] = $item->getQtyOrdered();
        }
        /* @var $invoice Mage_Sales_Model_Service_Order */
        $invoice = Mage::getModel('sales/service_order', $this->_getOrder())->prepareInvoice($items);
        $invoice->setEmailSent(true)->register();

        Mage::register('current_invoice', $invoice);
        return $invoice;
    }


    /**
     * Get order review step html
     *
     * @return string
     */
    protected function _getReviewHtml()
    {
        return $this->getLayout()->getBlock('root')->toHtml();
    }

    /**
     * Render block HTML
     *
     * @string $node block name
     */
    protected function _getBlockHtml($node)
    {
        $cache = Mage::app()->getCacheInstance();
        $cache->banUse('layout');

        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load($node);
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getOutput();
    }

}

