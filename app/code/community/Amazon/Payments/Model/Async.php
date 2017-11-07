<?php
/**
 * Amazon Payments
 *
 * @category    Amazon
 * @package     Amazon_Payments
 * @copyright   Copyright (c) 2014 Amazon.com
 * @license     http://opensource.org/licenses/Apache-2.0  Apache License, Version 2.0
 */

class Amazon_Payments_Model_Async extends Mage_Core_Model_Abstract
{

    /**
     * Return Amazon API
     */
    protected function _getApi($storeId = null)
    {
        $_api = Mage::getModel('amazon_payments/api');
        $_api->setStoreId($storeId);
        return $_api;
    }

    /**
     * Create invoice
     */
    protected function _createInvoice(Mage_Sales_Model_Order $order, $captureReferenceIds)
    {
        if ($order->canInvoice()) {
            $transactionSave = Mage::getModel('core/resource_transaction');

            // Create invoice
            $invoice = $order
                ->prepareInvoice()
                ->register();
            $invoice->setTransactionId(current($captureReferenceIds));

            $transactionSave
                ->addObject($invoice)
                ->addObject($invoice->getOrder());

            return $transactionSave->save();
        }

        return false;
    }

    /**
     * Send Payment Decline email
     */
    protected function _sendPaymentDeclineEmail(Mage_Sales_Model_Order $order, $type = 'soft')
    {
        $emailTemplate = Mage::getModel('core/email_template')->loadDefault('amazon_payments_async_decline_' . $type);

        $orderUrl = Mage::getUrl('sales/order/view', array(
            'order_id'       => $order->getId(),
            '_store'         => $order->getStoreId(),
            '_forced_secure' => true,
        ));

        $templateParams = array(
            'order_url' => $orderUrl,
            'store'     => Mage::app()->getStore($order->getStoreId()),
            'customer'  => Mage::getModel('customer/customer')->load($order->getCustomerId()),
        );

        $sender = array(
            'name' => Mage::getStoreConfig('trans_email/ident_general/email', $order->getStoreId()),
            'email' => Mage::getStoreConfig('trans_email/ident_general/name', $order->getStoreId())
        );

        $emailTemplate->sendTransactional($emailTemplate->getId(), $sender, $order->getCustomerEmail(), $order->getCustomerName(), $templateParams, $order->getStoreId());
    }

    /**
     * Poll Amazon API to receive order status and update Magento order.
     */
    public function syncOrderStatus(Mage_Sales_Model_Order $order, $isManualSync = false)
    {
        $_api = $this->_getApi($order->getStoreId());
        $message = '';

        try {
            $payment = $order->getPayment();
            $method  = $payment->getMethodInstance(); // Amazon_Payments_Model_PaymentMethod
            $amount  = $payment->getAmountOrdered();
            $amazonOrderReference = $order->getPayment()->getAdditionalInformation('order_reference');

            // Pre-orders
            if ($method->_isPreorder($payment)) {
                // Authorize pre-order on manual sync
                if ($isManualSync) {
                    $method->authorize($payment, $amount);
                    $amazonOrderReference = $payment->getAdditionalInformation('order_reference');
                }
                // Ignore pre-order if cron
                else {
                    return;
                }
            }

            $orderReferenceDetails = $_api->getOrderReferenceDetails($amazonOrderReference);

            if ($orderReferenceDetails) {

                // Retrieve Amazon Authorization Details

                // Last transaction ID is Amazon Authorize Reference ID
                $lastAmazonReference = $order->getPayment()->getLastTransId();
                $resultAuthorize = $this->_getApi($order->getStoreId())->getAuthorizationDetails($lastAmazonReference);
                $amazonAuthorizationState = $resultAuthorize->getAuthorizationStatus()->getState();
                $reasonCode = $resultAuthorize->getAuthorizationStatus()->getReasonCode();

                // Re-authorize if holded, an Open order reference, and manual sync
                if ($order->getState() == Mage_Sales_Model_Order::STATE_HOLDED && $orderReferenceDetails->getOrderReferenceStatus()->getState() == 'Open' && $isManualSync) {
                    $payment = $order->getPayment();
                    $amount  = $payment->getAmountOrdered();
                    $method  = $payment->getMethodInstance();

                    // Re-authorize
                    $payment->setTransactionId($amazonOrderReference);
                    $payment->setAdditionalInformation('sandbox', null); // Remove decline and other test simulations

                    $method->setForceSync(true);

                    switch ($method->getConfigData('payment_action')) {
                        case $method::ACTION_AUTHORIZE:
                            $resultAuthorize = $method->authorize($payment, $amount, false);
                            break;

                        case $method::ACTION_AUTHORIZE_CAPTURE:
                            $resultAuthorize = $method->authorize($payment, $amount, true);
                            break;
                        default:
                            break;
                    }

                    // Resync
                    $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true);
                    $order->save();
                    $this->syncOrderStatus($order);
                    return;
                }

                $message = Mage::helper('payment')->__('Sync with Amazon: Authorization state is "%s".', $amazonAuthorizationState);

                switch ($amazonAuthorizationState) {
                  // Pending (All Authorization objects are in the Pending state for 30 seconds after Authorize request)
                  case Amazon_Payments_Model_Api::AUTH_STATUS_PENDING:
                      $message .= Mage::helper('payment')->__(' (Payment is currently authorizing. Please try again momentarily.)');
                      break;

                  // Declined
                  case Amazon_Payments_Model_Api::AUTH_STATUS_DECLINED:
                      if ($order->getState() != Mage_Sales_Model_Order::STATE_HOLDED) {
                          $order->setState(Mage_Sales_Model_Order::STATE_HOLDED, true);
                      }

                      if ($reasonCode == 'InvalidPaymentMethod') {
                          $this->_sendPaymentDeclineEmail($order, 'soft');
                          $message .= " Order placed on hold due to $reasonCode. Email sent to customer with link to order details page and instructions to update their payment method.";
                      } else {
                          $this->_sendPaymentDeclineEmail($order, 'hard');
                          $message .= " Order placed on hold due to $reasonCode. Email sent to customer with instructions to contact seller.";
                      }
                      break;

                  // Open (Authorize Only)
                  case Amazon_Payments_Model_Api::AUTH_STATUS_OPEN:
                      $order->setState(Mage_Sales_Model_Order::STATE_NEW);
                      $order->setStatus($_api->getConfig()->getNewOrderStatus($order->getStoreId()));
                      break;

                  // Closed (Authorize and Capture)
                  case Amazon_Payments_Model_Api::AUTH_STATUS_CLOSED:

                      // Payment captured; create invoice
                      if ($reasonCode == 'MaxCapturesProcessed') {
                          $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING);
                          $order->setStatus($_api->getConfig()->getNewOrderStatus($order->getStoreId()));

                          if ($this->_createInvoice($order, $resultAuthorize->getIdList()->getmember())) {
                              $message .= ' ' . Mage::helper('payment')->__('Invoice created.');
                          }
                      }
                      else {
                          $order->setState(Mage_Sales_Model_Order::STATE_HOLDED, true);

                          $message .= Mage::helper('payment')->__(' Unable to create invoice due to Authorization Reason Code: %s', $reasonCode);
                      }

                      break;
                }

                // Update order
                if ($amazonAuthorizationState != Amazon_Payments_Model_Api::AUTH_STATUS_PENDING) {
                    $order->addStatusToHistory($order->getStatus(), $message, false);
                    $order->save();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess($message);
            }
        } catch (Exception $e) {
            // Change order to "On Hold"
            if ($order->getState() != Mage_Sales_Model_Order::STATE_HOLDED) {
                $message = 'Error exception during sync. Please check exception.log';
                $order->setState(Mage_Sales_Model_Order::STATE_HOLDED, true);
                $order->addStatusToHistory($order->getStatus(), $message, false);
                $order->save();
            }

            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Magento cron to sync Amazon orders
     */
    public function cron()
    {
        if ($this->_getApi()->getConfig()->isAsync()) {

            $orderCollection = Mage::getModel('sales/order_payment')
                ->getCollection()
                ->join(array('order'=>'sales/order'), 'main_table.parent_id=order.entity_id', 'state')
                ->addFieldToFilter('method', 'amazon_payments')
                ->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_PENDING_PAYMENT) // Async
                ;

            foreach ($orderCollection as $orderRow) {
                $order = Mage::getModel('sales/order')->load($orderRow->getParentId());
                $this->syncOrderStatus($order);
            }
        }
    }
}
