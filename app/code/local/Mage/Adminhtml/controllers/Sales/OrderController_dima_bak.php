<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales orders controller
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Array of actions which can be processed without secret key validation
     *
     * @var array
     */
    protected $_publicActions = array('view', 'index');

    /**
     * Additional initialization
     *
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Mage_Sales');
    }

    /**
     * Init layout, menu and breadcrumb
     *
     * @return Mage_Adminhtml_Sales_OrderController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales/order')
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Orders'), $this->__('Orders'));
        return $this;
    }

    /**
     * Initialize order model instance
     *
     * @return Mage_Sales_Model_Order || false
     */
    protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($id);

        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This order no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        Mage::register('sales_order', $order);
        Mage::register('current_order', $order);
        return $order;
    }

    /**
     * Orders grid
     */
    public function indexAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Orders'));

        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Order grid
     */
    public function gridAction()
    {
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * View order detale
     */
    public function viewAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Orders'));

        $order = $this->_initOrder();
        if ($order) {

            $isActionsNotPermitted = $order->getActionFlag(
                Mage_Sales_Model_Order::ACTION_FLAG_PRODUCTS_PERMISSION_DENIED
            );
            if ($isActionsNotPermitted) {
                $this->_getSession()->addError($this->__('You don\'t have permissions to manage this order because of one or more products are not permitted for your website.'));
            }

            $this->_initAction();

            $this->_title(sprintf("#%s", $order->getRealOrderId()));

            $this->renderLayout();
        }
    }

    /**
     * Notify user
     */
    public function emailAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->sendNewOrderEmail();
                $historyItem = Mage::getResourceModel('sales/order_status_history_collection')
                    ->getUnnotifiedForInstance($order, Mage_Sales_Model_Order::HISTORY_ENTITY_NAME);
                if ($historyItem) {
                    $historyItem->setIsCustomerNotified(1);
                    $historyItem->save();
                }
                $this->_getSession()->addSuccess($this->__('The order email has been sent.'));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Failed to send the order email.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
    }
    /**
     * Cancel order
     */
    public function cancelAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->cancel()
                    ->save();
                $this->_getSession()->addSuccess(
                    $this->__('The order has been cancelled.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order has not been cancelled.'));
                Mage::logException($e);
            }
            $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
        }
    }

    /**
     * Hold order
     */
    public function holdAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->hold()
                    ->save();
                $this->_getSession()->addSuccess(
                    $this->__('The order has been put on hold.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order was not put on hold.'));
            }
            $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
        }
    }
    
    /**
     * Print gift message
     */
    public function printgiftAction()
    {
    	if ($order = $this->_initOrder()) {
	    	$this->loadLayout();     
			$this->renderLayout();
    	}
    }
    
    /**
     * Print appraisal
     */
    public function printapprAction()
    {
    	if ($order = $this->_initOrder()) {
	    	$this->loadLayout();     
			$this->renderLayout();
    	}
    }
    
    /**
     * Print mini appraisal
     */
    public function printminiapprAction()
    {
    	if ($order = $this->_initOrder()) {
	    	$this->loadLayout();     
			$this->renderLayout();
    	}
    }
    
    /**
     * Print invoice
     */
    public function printinvAction()
    {
    	if ($order = $this->_initOrder()) {
    		$this->loadLayout();
    		$this->renderLayout();
    	}
    }

    /**
     * Print work order
     */
    public function printworkorderAction()
    {
    	if ($order = $this->_initOrder()) {
	    	$this->loadLayout();     
			$this->renderLayout();
    	}
    }
    
    /**
     * Print wax order
     */
    public function printwaxAction()
    {
    	if ($order = $this->_initOrder()) {
	    	$this->loadLayout();     
			$this->renderLayout();
    	}
    }
    
    /**
     * Custom appraisal
     */
    public function custapprAction()
    {    	
        $this->loadLayout();
    	$this->renderLayout();
    }
    
    /**
     * Custom appraisal save action
     */
    public function custapprshowAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    }
    
    /**
     * Send Custom Message
     */
    public function sendcustommessageAction()
    {
    	if ($this->getRequest()->isXmlHttpRequest()) {
	    	
	    	$templateId = 48;
	    	$templateName = 'Custom Message';
	    	
	    	$CMto = $this->getRequest()->getParam('CMto', false);
	    	$CMmessage = $this->getRequest()->getParam('CMmessage', false);
	    	$CMsubject = $this->getRequest()->getParam('CMsubject', false);
	    	
	    	if (!$CMto || !$CMmessage) 
	    	{
	    		$this->_getSession()->addError($this->__('The ' . $templateName . ' has not been sent. Some of the parameters are missing.'));
	    	} 
	    	else 
	    	{
				$sender = array('name' => 'Avianne & Co. Customer Service', 'email' => 'service@avianneandco.com');
		
				//recepient
				$email = $CMto;
				$emailName = '';
				
				$vars = array(
					'CMsubject' => trim($CMsubject),
					'CMmessage' => trim(nl2br($CMmessage)) . '<br><a href="http://www.avianneandco.com"><img src="http://www.avianneandco.com/images/email-logo.jpg" alt="www.AvianneAndCo.com"></a>'
				);
				
				$storeId = Mage::app()->getStore()->getId();
				
				try {
					Mage::getModel('core/email_template')->addBcc('avianneandco@gmail.com')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);
					$this->_getSession()->addSuccess($this->__('The ' . $templateName . ' email has been sent.'));
				}
				catch (Mage_Core_Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
				catch (Exception $e) {
					$this->_getSession()->addError($this->__('The ' . $templateName . ' has not been sent.'));
				}
	    	} 
	    }
    }

    /**
     * Send Return Shipped Message
     */
    public function sendreturnshippedAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $templateId = 52;
            $templateName = 'Return Shipped';
            
            $RSto = $this->getRequest()->getParam('RSto', false);
            $RStracking = $this->getRequest()->getParam('RStracking', false);
            // $RSmessage = $this->getRequest()->getParam('RSmessage', false);
            $RSsubject = $this->getRequest()->getParam('RSsubject', false);



            if (!$RSto || !$RStracking) 
            {
                $this->_getSession()->addError($this->__('The ' . $templateName . ' has not been sent. Some of the parameters are missing!'));
            } 
            else 
            {
                $sender = array('name' => 'Avianne & Co. Customer Service', 'email' => 'service@avianneandco.com');
        
                //recepient
                $email = $RSto;
                $emailName = '';
                
                $order = $this->_initOrder();

                $vars = array(
                    'RSsubject'     => $RSsubject,
                    'RStracking'    => $RStracking,
                    'customername'  => ($order->customer_firstname ? $order->customer_firstname : $address->firstname) . ' ' . ($order->customer_lastname ? $order->customer_lastname : $address->lastname),
                    'ordernumber'   => $order->increment_id,
                );
                
                $storeId = Mage::app()->getStore()->getId();

                try {
                    // Mage::getModel('core/email_template')->addBcc('avianneandco@gmail.com')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);
                    Mage::getModel('core/email_template')->addBcc('d.pelogenko@wsmintl.com')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);
                    $this->_getSession()->addSuccess($this->__('The ' . $templateName . ' email has been sent.'));
                }
                catch (Mage_Core_Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
                catch (Exception $e) {
                    $this->_getSession()->addError($this->__('The ' . $templateName . ' has not been sent.'));
                }
            } 
        }
    }

    /**
     * Send Backorder Email
     */
    public function sendbackorderemailAction()
    {
    	$order = $this->_initOrder();
    	
    	$address = $order->getBillingAddress();
    	
    	$items = $order->getAllItems();

    	$product_title = array();
		foreach ($items as $item) 
		{
			$product_title[] = $item->name;
		}
		$product_title = implode(', ', $product_title);
		
		$type = $this->getRequest()->getParam('type', 'short');
		
		switch ($type) {
            case 'short':
                $templateId = 40;
                $templateName = 'Short Backorder';
                break;
            case 'adorn':
                $templateId = 41;
                $templateName = 'Adorn Backorder';
                break;
            case 'medium':
                $templateId = 42;
                $templateName = 'Medium Backorder';
                break;
            case 'authret':
                $templateId = 43;
                $templateName = 'Authorize Return';
                break;
            case 'tracknum':
                $templateId = 44;
                $templateName = 'Tracking Number';
                break;
            case 'idi':
                $templateId = 45;
                $templateName = 'IDI Backorder';
                break;
            case 'long':
            case 'backorder':
                $templateId = 46;
                $templateName = 'Backorder';
                break;
            case 'discontinued':
                $templateId = 47;
                $templateName = 'Discontinued Item';
                break;
            case 'returnreceived':
                $templateId = 51;
                $templateName = 'Return Received';
                break;
		}
		
		$sender = array('name' => 'Avianne & Co. Customer Service', 'email' => 'service@avianneandco.com');

		//recepient
		$email = $order->customer_email;
		$emailName = ($order->customer_firstname ? $order->customer_firstname : $address->firstname) . ' ' . ($order->customer_lastname ? $order->customer_lastname : $address->lastname);
		
		$ran = $order->increment_id;
		$ran_add = strrchr($order->increment_id, '-');
		if ($ran_add !== false) {
			$ran = substr($ran, 0, strlen($ran_add)*-1);
		}
		
		$vars = array(
			  'customername'		=> ($order->customer_firstname ? $order->customer_firstname : $address->firstname) . ' ' . ($order->customer_lastname ? $order->customer_lastname : $address->lastname)
			, 'customerfirstname'	=> ($order->customer_firstname ? $order->customer_firstname : $address->firstname)
			, 'ordernumber'		=> $order->increment_id
			, 'producttitle'	=> $product_title
			, 'ran'				=> 'RAN' . substr($ran, -4) . $ran_add
			, 'trackingnumber'	=> false
		);
		
		if ($type == 'backorder') {
			$vars['days'] = $this->getRequest()->getParam('days', 4);
		}
		
		$shipmentCollection = Mage::getResourceModel('sales/order_shipment_collection')->setOrderFilter($order)->load();
		foreach ($shipmentCollection as $shipment){
			foreach($shipment->getAllTracks() as $tracknum) {
				$vars['trackingnumber'] = $tracknum->getNumber();
			}
		}
        
		$storeId = Mage::app()->getStore()->getId();
		
		try {
			Mage::getModel('core/email_template')->addBcc('avianneandco@gmail.com')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);
			$this->_getSession()->addSuccess(
				$this->__('The ' . $templateName . ' email has been sent.')
			);

            if ($type == 'returnreceived') {

                $resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                $table = $resource->getTableName('sales/order_grid');
                $query = "UPDATE {$table} SET int_status = 'return_received', last_edit = NOW() WHERE increment_id = " . $order->increment_id;
                $writeConnection->query($query);

                $order->setStatus("return_received");
                $history = $order->addStatusHistoryComment('Return Received on ' . date("M j, Y g:i:s A", time()-60*60*4), false);
                $history->setIsCustomerNotified(false);

                $this->_getSession()->addSuccess( $this->__('The Order Status has been changed to "Return Received".') );
                $order->save();
            }

		}
		catch (Mage_Core_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Exception $e) {
			$this->_getSession()->addError($this->__('The ' . $templateName . ' has not been sent.'));
		}
		$back = $this->getRequest()->getParam('back');

		if ($back) {
			$this->_redirect('*/sales_order/'.$back);
		} else {
			$this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
		}
    }

    /**
     * Unhold order
     */
    public function unholdAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $order->unhold()
                    ->save();
                $this->_getSession()->addSuccess(
                    $this->__('The order has been released from holding status.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order was not unheld.'));
            }
            $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
        }
    }

    /**
     * Manage payment state
     *
     * Either denies or approves a payment that is in "review" state
     */
    public function reviewPaymentAction()
    {
        try {
            if (!$order = $this->_initOrder()) {
                return;
            }
            $action = $this->getRequest()->getParam('action', '');
            switch ($action) {
                case 'accept':
                    $order->getPayment()->accept();
                    $message = $this->__('The payment has been accepted.');
                    break;
                case 'deny':
                    $order->getPayment()->deny();
                    $message = $this->__('The payment has been denied.');
                    break;
                case 'update':
                    $order->getPayment()
                        ->registerPaymentReviewAction(Mage_Sales_Model_Order_Payment::REVIEW_ACTION_UPDATE, true);
                    $message = $this->__('Payment update has been made.');
                    break;
                default:
                    throw new Exception(sprintf('Action "%s" is not supported.', $action));
            }
            $order->save();
            $this->_getSession()->addSuccess($message);
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Failed to update the payment.'));
            Mage::logException($e);
        }
        $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
    }

    /**
     * Add order comment action
     */
    public function addCommentAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                $response = false;
                $data = $this->getRequest()->getPost('history');
                $notify = isset($data['is_customer_notified']) ? $data['is_customer_notified'] : false;
                $visible = isset($data['is_visible_on_front']) ? $data['is_visible_on_front'] : false;

                $order->addStatusHistoryComment($data['comment'], $data['status'])
                    ->setIsVisibleOnFront($visible)
                    ->setIsCustomerNotified($notify);

                $comment = trim(strip_tags($data['comment']));

                $order->save();
                $order->sendOrderUpdateEmail($notify, $comment);

                $this->loadLayout('empty');
                $this->renderLayout();
            }
            catch (Mage_Core_Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $e->getMessage(),
                );
            }
            catch (Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Cannot add order history.')
                );
            }
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
        }
    }

    /**
     * Generate invoices grid for ajax request
     */
    public function invoicesAction()
    {
        $this->_initOrder();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/sales_order_view_tab_invoices')->toHtml()
        );
    }

    /**
     * Generate shipments grid for ajax request
     */
    public function shipmentsAction()
    {
        $this->_initOrder();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/sales_order_view_tab_shipments')->toHtml()
        );
    }

    /**
     * Generate creditmemos grid for ajax request
     */
    public function creditmemosAction()
    {
        $this->_initOrder();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/sales_order_view_tab_creditmemos')->toHtml()
        );
    }

    /**
     * Generate order history for ajax request
     */
    public function commentsHistoryAction()
    {
        $this->_initOrder();
        $html = $this->getLayout()->createBlock('adminhtml/sales_order_view_tab_history')->toHtml();
        /* @var $translate Mage_Core_Model_Translate_Inline */
        $translate = Mage::getModel('core/translate_inline');
        if ($translate->isAllowed()) {
            $translate->processResponseBody($html);
        }
        $this->getResponse()->setBody($html);
    }

    /**
     * Cancel selected orders
     */
    public function massCancelAction()
    {
        $orderIds = $this->getRequest()->getPost('order_ids', array());
        $countCancelOrder = 0;
        $countNonCancelOrder = 0;
        foreach ($orderIds as $orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->canCancel()) {
                $order->cancel()
                    ->save();
                $countCancelOrder++;
            } else {
                $countNonCancelOrder++;
            }
        }
        if ($countNonCancelOrder) {
            if ($countCancelOrder) {
                $this->_getSession()->addError($this->__('%s order(s) cannot be canceled', $countNonCancelOrder));
            } else {
                $this->_getSession()->addError($this->__('The order(s) cannot be canceled'));
            }
        }
        if ($countCancelOrder) {
            $this->_getSession()->addSuccess($this->__('%s order(s) have been canceled.', $countCancelOrder));
        }
        $this->_redirect('*/*/');
    }

    /**
     * Get Shipping Rates
     */
    public function getShippingRatesAction()
    {
        $orderId = $this->getRequest()->getPost('order_id');
        $__order = Mage::getModel('sales/order')->load($orderId);
        $quote = Mage::getModel('sales/quote')->load($__order->getQuoteId());
        $quotes = $quote->getShippingAddress();
        $quotes->setCountryId($__order->getShippingAddress()->getCountryId())->setRegionId($__order->getShippingAddress()->getRegionId())->setPostcode($__order->getShippingAddress()->getPostcode())->setCollectShippingRates(true);
        $rates = $quotes->collectShippingRates()->getGroupedAllShippingRates();
         
        $_allRates = array();
         
        foreach ($rates as $carrier) {
        	foreach ($carrier as $rate) {
        		$_allRates[] = array(
        				'title' => $rate->getCarrierTitle() . ' - ' . $rate->getMethodTitle(),
        				'code' => $rate->getCode()
        		);
        	}
        }
        $jsonData = json_encode($_allRates);
        
        $this->getResponse()->setHeader('Content-type', 'application/json');
		$this->getResponse()->setBody($jsonData);
    }
    
    /**
     * Get Shipping Rates
     */
    public function submitShippingForOrderAction()
    {
    	$orderId = $this->getRequest()->getPost('order_id');
    	$insuredValue = $this->getRequest()->getPost('insured_value');
    	$serviceType = $this->getRequest()->getPost('service_type');
    	$serviceTypeDesc = $this->getRequest()->getPost('service_type_desc');
    	
    	$fullOrder = Mage::getModel('sales/order')->load($orderId);
    	$fullOrder->setShippingDescription($serviceTypeDesc);
    	$fullOrder->setShippingMethod($serviceType);
    	$fullOrder->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
    	$fullOrder->setStatus('processing', false);
    	$fullOrder->save();
    	try {
    		if(!$fullOrder->canInvoice()) {
    			Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
    		}
    		$invoice = Mage::getModel('sales/service_order', $fullOrder)->prepareInvoice();
    		if (!$invoice->getTotalQty()) {
    			Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
    		}
    		
    		// Change to ONLINE
    		$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
    		$invoice->register();
    		$transactionSave = Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder());
    		$transactionSave->save();
    	} catch (Mage_Core_Exception $e) {
    		Mage::logException($e);
    	}
    	
    	try {
    		if(!$fullOrder->canShip()) {
    			Mage::throwException(Mage::helper('core')->__('Cannot ship this order.'));
    		}
    		$orderItems = $fullOrder->getItemsCollection();
    		$itemQty =  $orderItems->count();
    		$shipment = Mage::getModel('sales/service_order', $fullOrder)->prepareShipment($itemQty);
    		$shipment = new Mage_Sales_Model_Order_Shipment_Api();
    		$shipmentId = $shipment->create($fullOrder->getIncrementId());
    		
    		$shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentId);
    		
    		$carrier = $fullOrder->getShippingCarrier();
    		if ($carrier->isShippingLabelsAvailable()) {
    			
    			$items = array();
    			foreach ($orderItems as $orderItem) {
    				$items[] = array(
    						'qty' => $orderItem->getQty(),
    						'customs_value' => (int)$orderItem->getPrice(),
    						'price' => $orderItem->getPrice(),
    						'name' => $orderItem->getName(),
    						'weight' => $orderItem->getWeight(),
    						'product_id' => $orderItem->getProductId(),
    						'order_item_id' => $orderItem->getId(),
    				);
    			}
    			
	    		$shipment->setPackages(
	    				array(
	    						1 => array(
	    								'params' => array(
	    										'container' => 'FEDEX_PAK',
	    										'weight' => $fullOrder->getWeight(),
	    										'customs_value' => (int)$orderItem->getPrice(),
	    										'weight_units' => 'POUND',
	    										'dimension_units' => 'INCH',
	    										'delivery_confirmation' => 'DIRECT',
	    								),
	    								'items' => $items
	    						)
	    				)		
	    		);
	    		
	    		if (!empty($insuredValue) && is_numeric($insuredValue)) {
	    			$shipment->setInsuredAmount($insuredValue);
	    		}
	    		
	    		$response = Mage::getModel('shipping/shipping')->requestToShipment($shipment);
	    		if ($response->hasErrors()) {
	    			Mage::throwException($response->getErrors());
	    		}
	    		if (!$response->hasInfo()) {
	    			return false;
	    		}
	    		$labelsContent = array();
	    		$trackingNumbers = array();
	    		$info = $response->getInfo();
	    		foreach ($info as $inf) {
	    			if (!empty($inf['tracking_number']) && !empty($inf['label_content'])) {
	    				$labelsContent[] = $inf['label_content'];
	    				$trackingNumbers[] = $inf['tracking_number'];
	    			}
	    		}
	    		$outputPdf = $this->_combineLabelsPdf($labelsContent);
	    		$shipment->setShippingLabel($outputPdf->render());
	    		$carrierCode = $carrier->getCarrierCode();
	    		$carrierTitle = Mage::getStoreConfig('carriers/'.$carrierCode.'/title', $shipment->getStoreId());
	    		if ($trackingNumbers) {
	    			foreach ($trackingNumbers as $trackingNumber) {
	    				$track = Mage::getModel('sales/order_shipment_track')
	    				->setNumber($trackingNumber)
	    				->setCarrierCode($carrierCode)
	    				->setTitle($carrierTitle);
	    				$shipment->addTrack($track);
	    			}
	    		}
// 	    		$shipment->save();
	    		
	    		$shipment->sendEmail(true)
	    		->setEmailSent(true)
	    		->save();
	    		$historyItem = Mage::getResourceModel('sales/order_status_history_collection')
	    		->getUnnotifiedForInstance($shipment, Mage_Sales_Model_Order_Shipment::HISTORY_ENTITY_NAME);
	    		if ($historyItem) {
	    			$historyItem->setIsCustomerNotified(1);
	    			$historyItem->save();
	    		}
	    		
    		}
    	} catch (Mage_Core_Exception $e) {
    		Mage::logException($e);
    	}
    
    	exit($shipment->getId());
    }
    
    protected function _combineLabelsPdf(array $labelsContent)
    {
    	$outputPdf = new Zend_Pdf();
    	foreach ($labelsContent as $content) {
    		if (stripos($content, '%PDF-') !== false) {
    			$pdfLabel = Zend_Pdf::parse($content);
    			foreach ($pdfLabel->pages as $page) {
    				$outputPdf->pages[] = clone $page;
    			}
    		} else {
    			$page = $this->_createPdfPageFromImageString($content);
    			if ($page) {
    				$outputPdf->pages[] = $page;
    			}
    		}
    	}
    	return $outputPdf;
    }
    
    /**
     * Create Zend_Pdf_Page instance with image from $imageString. Supports JPEG, PNG, GIF, WBMP, and GD2 formats.
     *
     * @param string $imageString
     * @return Zend_Pdf_Page|bool
     */
    protected function _createPdfPageFromImageString($imageString)
    {
    	$image = imagecreatefromstring($imageString);
    	if (!$image) {
    		return false;
    	}
    
    	$xSize = imagesx($image);
    	$ySize = imagesy($image);
    	$page = new Zend_Pdf_Page($xSize, $ySize);
    
    	imageinterlace($image, 0);
    	$tmpFileName = sys_get_temp_dir() . DS . 'shipping_labels_'
    			. uniqid(mt_rand()) . time() . '.png';
    			imagepng($image, $tmpFileName);
    			$pdfImage = Zend_Pdf_Image::imageWithPath($tmpFileName);
    			$page->drawImage($pdfImage, 0, 0, $xSize, $ySize);
    			unlink($tmpFileName);
    			return $page;
    }
    
    /**
     * Hold selected orders
     */
    public function massHoldAction()
    {
        $orderIds = $this->getRequest()->getPost('order_ids', array());
        $countHoldOrder = 0;

        foreach ($orderIds as $orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->canHold()) {
                $order->hold()
                    ->save();
                $countHoldOrder++;
            }
        }

        $countNonHoldOrder = count($orderIds) - $countHoldOrder;

        if ($countNonHoldOrder) {
            if ($countHoldOrder) {
                $this->_getSession()->addError($this->__('%s order(s) were not put on hold.', $countNonHoldOrder));
            } else {
                $this->_getSession()->addError($this->__('No order(s) were put on hold.'));
            }
        }
        if ($countHoldOrder) {
            $this->_getSession()->addSuccess($this->__('%s order(s) have been put on hold.', $countHoldOrder));
        }

        $this->_redirect('*/*/');
    }

    /**
     * Unhold selected orders
     */
    public function massUnholdAction()
    {
        $orderIds = $this->getRequest()->getPost('order_ids', array());
        $countUnholdOrder = 0;
        $countNonUnholdOrder = 0;

        foreach ($orderIds as $orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->canUnhold()) {
                $order->unhold()
                    ->save();
                $countUnholdOrder++;
            } else {
                $countNonUnholdOrder++;
            }
        }
        if ($countNonUnholdOrder) {
            if ($countUnholdOrder) {
                $this->_getSession()->addError($this->__('%s order(s) were not released from holding status.', $countNonUnholdOrder));
            } else {
                $this->_getSession()->addError($this->__('No order(s) were released from holding status.'));
            }
        }
        if ($countUnholdOrder) {
            $this->_getSession()->addSuccess($this->__('%s order(s) have been released from holding status.', $countUnholdOrder));
        }
        $this->_redirect('*/*/');
    }

    /**
     * Change status for selected orders
     */
    public function massStatusAction()
    {

    }

    /**
     * Print documents for selected orders
     */
    public function massPrintAction()
    {
        $orderIds = $this->getRequest()->getPost('order_ids');
        $document = $this->getRequest()->getPost('document');
    }

    /**
     * Runner List Printout
     */
    public function runnerAction(){
		$this->loadLayout();     
		$this->renderLayout();
    }
    
    /**
     * Return List Printout
     */
    public function returnAction(){
        $this->loadLayout();     
        $this->renderLayout();
    }
    
    /**
     * Print invoices for selected orders
     */
    public function pdfinvoicesAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
                $invoices = Mage::getResourceModel('sales/order_invoice_collection')
                    ->setOrderFilter($orderId)
                    ->load();
                if ($invoices->getSize() > 0) {
                    $flag = true;
                    if (!isset($pdf)){
                        $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoices);
                    } else {
                        $pages = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoices);
                        $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                    }
                }
            }
            if ($flag) {
                return $this->_prepareDownloadResponse(
                    'invoice'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(),
                    'application/pdf'
                );
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
                $this->_redirect('*/*/');
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Print shipments for selected orders
     */
    public function pdfshipmentsAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
                $shipments = Mage::getResourceModel('sales/order_shipment_collection')
                    ->setOrderFilter($orderId)
                    ->load();
                if ($shipments->getSize()) {
                    $flag = true;
                    if (!isset($pdf)){
                        $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf($shipments);
                    } else {
                        $pages = Mage::getModel('sales/order_pdf_shipment')->getPdf($shipments);
                        $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                    }
                }
            }
            if ($flag) {
                return $this->_prepareDownloadResponse(
                    'packingslip'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(),
                    'application/pdf'
                );
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
                $this->_redirect('*/*/');
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Print creditmemos for selected orders
     */
    public function pdfcreditmemosAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
                $creditmemos = Mage::getResourceModel('sales/order_creditmemo_collection')
                    ->setOrderFilter($orderId)
                    ->load();
                if ($creditmemos->getSize()) {
                    $flag = true;
                    if (!isset($pdf)){
                        $pdf = Mage::getModel('sales/order_pdf_creditmemo')->getPdf($creditmemos);
                    } else {
                        $pages = Mage::getModel('sales/order_pdf_creditmemo')->getPdf($creditmemos);
                        $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                    }
                }
            }
            if ($flag) {
                return $this->_prepareDownloadResponse(
                    'creditmemo'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(),
                    'application/pdf'
                );
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
                $this->_redirect('*/*/');
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Print all documents for selected orders
     */
    public function pdfdocsAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
                $invoices = Mage::getResourceModel('sales/order_invoice_collection')
                    ->setOrderFilter($orderId)
                    ->load();
                if ($invoices->getSize()){
                    $flag = true;
                    if (!isset($pdf)){
                        $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoices);
                    } else {
                        $pages = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoices);
                        $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                    }
                }

                $shipments = Mage::getResourceModel('sales/order_shipment_collection')
                    ->setOrderFilter($orderId)
                    ->load();
                if ($shipments->getSize()){
                    $flag = true;
                    if (!isset($pdf)){
                        $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf($shipments);
                    } else {
                        $pages = Mage::getModel('sales/order_pdf_shipment')->getPdf($shipments);
                        $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                    }
                }

                $creditmemos = Mage::getResourceModel('sales/order_creditmemo_collection')
                    ->setOrderFilter($orderId)
                    ->load();
                if ($creditmemos->getSize()) {
                    $flag = true;
                    if (!isset($pdf)){
                        $pdf = Mage::getModel('sales/order_pdf_creditmemo')->getPdf($creditmemos);
                    } else {
                        $pages = Mage::getModel('sales/order_pdf_creditmemo')->getPdf($creditmemos);
                        $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                    }
                }
            }
            if ($flag) {
                return $this->_prepareDownloadResponse(
                    'docs'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf',
                    $pdf->render(), 'application/pdf'
                );
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
                $this->_redirect('*/*/');
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Atempt to void the order payment
     */
    public function voidPaymentAction()
    {
        if (!$order = $this->_initOrder()) {
            return;
        }
        try {
            $order->getPayment()->void(
                new Varien_Object() // workaround for backwards compatibility
            );
            $order->save();
            $this->_getSession()->addSuccess($this->__('The payment has been voided.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Failed to void the payment.'));
            Mage::logException($e);
        }
        $this->_redirect('*/*/view', array('order_id' => $order->getId()));
    }

    /**
     * Acl check for admin
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'hold':
                $aclResource = 'sales/order/actions/hold';
                break;
            case 'unhold':
                $aclResource = 'sales/order/actions/unhold';
                break;
            case 'email':
                $aclResource = 'sales/order/actions/email';
                break;
            case 'cancel':
                $aclResource = 'sales/order/actions/cancel';
                break;
            case 'view':
                $aclResource = 'sales/order/actions/view';
                break;
            case 'addcomment':
                $aclResource = 'sales/order/actions/comment';
                break;
            case 'creditmemos':
                $aclResource = 'sales/order/actions/creditmemo';
                break;
            case 'reviewpayment':
                $aclResource = 'sales/order/actions/review_payment';
                break;
            default:
                $aclResource = 'sales/order';
                break;

        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'orders.csv';
        $grid       = $this->getLayout()->createBlock('adminhtml/sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'orders.xml';
        $grid       = $this->getLayout()->createBlock('adminhtml/sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    /**
     * Order transactions grid ajax action
     *
     */
    public function transactionsAction()
    {
        $this->_initOrder();
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * Edit order address form
     */
    public function addressAction()
    {
        $addressId = $this->getRequest()->getParam('address_id');
        $address = Mage::getModel('sales/order_address')
            ->getCollection()
            ->addFilter('entity_id', $addressId)
            ->getItemById($addressId);
        if ($address) {
            Mage::register('order_address', $address);
            $this->loadLayout();
            // Do not display VAT validation button on edit order address form
            $addressFormContainer = $this->getLayout()->getBlock('sales_order_address.form.container');
            if ($addressFormContainer) {
                $addressFormContainer->getChild('form')->setDisplayVatValidationButton(false);
            }

            $this->renderLayout();
        } else {
            $this->_redirect('*/*/');
        }
    }

    /**
     * Save order address
     */
    public function addressSaveAction()
    {
        $addressId  = $this->getRequest()->getParam('address_id');
        $address    = Mage::getModel('sales/order_address')->load($addressId);
        $data       = $this->getRequest()->getPost();
        if ($data && $address->getId()) {
            $address->addData($data);
            try {
                $address->implodeStreetAddress()
                    ->save();
                $this->_getSession()->addSuccess(Mage::helper('sales')->__('The order address has been updated.'));
                $this->_redirect('*/*/view', array('order_id'=>$address->getParentId()));
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('sales')->__('An error occurred while updating the order address. The address has not been changed.')
                );
            }
            $this->_redirect('*/*/address', array('address_id'=>$address->getId()));
        } else {
            $this->_redirect('*/*/');
        }
    }

    /**
     * Button clicked - save info
     */
    public function clickedAction()
    {
    	$order_id = $this->getRequest()->getParam('order_id');
    	$button_id = $this->getRequest()->getParam('button_id');
    	 
    	$resource = Mage::getSingleton('core/resource');
    	$readConnection = $resource->getConnection('core_read');
    	$writeConnection = $resource->getConnection('core_write');
    
    	$table = $resource->getTableName('clicked_buttons');
    
    	$result = $readConnection->fetchRow('SELECT * FROM ' . $table . ' WHERE order_id = ' . $order_id);
    
    	if ($result)
    	{
    		$buttons = unserialize($result['buttons_clicked']);
    		$buttons[$button_id] = 1;
    		$buttons = serialize($buttons);
    			
    		$query = "UPDATE {$table} SET buttons_clicked = '{$buttons}' WHERE order_id = {$order_id}";
    		$writeConnection->query($query);
    	}
    	else
    	{
    		$buttons = array($button_id => 1);
    		$buttons = serialize($buttons);
    
    		$query = "INSERT INTO {$table} (order_id, buttons_clicked) VALUES ({$order_id}, '{$buttons}')";
    		$writeConnection->query($query);
    	}
    
    	die($button_id);
    }
    
    /**
     * Save shipping method
     */
    public function updateMethodAction() {
    	if ($this->getRequest()->isXmlHttpRequest()) {
    		$order_id = $this->getRequest()->getParam('order_id');
    		$method = $this->getRequest()->getParam('method');
    		$order = Mage::getModel('sales/order')->load($order_id);
    		$setShipment = $order->setShippingDescription($method)->save();
    
    		if ($setShipment) {
    			die($method);
    		} else {
    			die('Unable to change shipping method');
    		}
    	}
    }
    
    /**
     * Save Order from Sales Order page
     */
	public function savestatusAction() {
    	if ($this->getRequest()->isXmlHttpRequest()) {
    		$order_id = $this->getRequest()->getParam('id');
    		$color = $this->getRequest()->getParam('color','c87070');
    		$int_st = $this->getRequest()->getParam('int_st','incomplete');
    		$ext_st = $this->getRequest()->getParam('ext_st','processing');
    		$comment = $this->getRequest()->getParam('comment');
    
    		$resource = Mage::getSingleton('core/resource');
    		$writeConnection = $resource->getConnection('core_write');
    			
    		$table = $resource->getTableName('sales/order_grid');
    
    		$query = "UPDATE {$table} SET color = '{$color}', int_status = '{$int_st}', internalcomment = '{$comment}', last_edit = NOW() WHERE entity_id = {$order_id}";
    		$writeConnection->query($query);
    			
    			
    		$order = Mage::getModel('sales/order')->load($order_id);
    			
    		if($order->getState() == 'complete') {
    				$errors[] = "ID ".$order->getIncrementID().". You aren't able to change the status of completed orders.";
    			} elseif($ext_st=='complete') {
    				//$st = Mage_Sales_Model_Order::STATE_COMPLETE;
    				
    			    if ($order->getBaseTotalDue() != 0) {
        			    if (!$order->canInvoice()) {
        					$errors[] = "ID ".$order->getIncrementID().". You aren't able to change the status of this order directly. Please make sure to create shipment/invoice first";
        				} else {
        				    $savedQtys = array();
        				    $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($savedQtys);
        				    die( var_dump( $invoice->getTotalQty() ) );
        				    if (!$invoice->getTotalQty()) {
        				        $errors[] = "ID ".$order->getIncrementID().". You aren't able to change the status of this order directly. Please make sure to create shipment/invoice first.";
        				    } else {
        				        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
        				        $invoice->register();
        				        	
        				        $invoice->getOrder()->setCustomerNoteNotify(false);
        				        $invoice->getOrder()->setIsInProcess(true);
        				        	
        				        $transactionSave = Mage::getModel('core/resource_transaction')
        				        ->addObject($invoice)
        				        ->addObject($invoice->getOrder());
        				        	
        				        $transactionSave->save();
        				    }
        				}
    			    } else {
    			        $itemQty =  $order->getItemsCollection()->count();
                        $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($itemQty);
                        $shipment = new Mage_Sales_Model_Order_Shipment_Api();
                        $shipmentId = $shipment->create($order->getIncrementID());
    			    }
    			} elseif($ext_st=='canceled' || $int_st=='void') {
    				$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
    				try {
    					$order->save();
    				} catch (Exception $e) {
    					$errors[] = "ID ".$order->getIncrementID().". ".$e->getMessage();
    				}
    			} /*else {
    				$st = Mage_Sales_Model_Order::STATE_PROCESSING;
    				$order->setState($st, true, '', '');
    				try {
    					$order->save();
    				} catch (Exception $e) {
    					$errors[] = "ID ".$order->getIncrementID().". ".$e->getMessage();
    				}
    			}*/
    			
    		if(count($errors)) {
    	       echo implode("\n", $errors);
    		}	
    		
    		exit();
    	}
    }
    
    /**
     * Mass Save Orders from Sales Order page
     */
    public function masssavestatusAction() {
    	if ($this->getRequest()->isXmlHttpRequest()) {
    		$errors = array();
    		$resource = Mage::getSingleton('core/resource');
    		$writeConnection = $resource->getConnection('core_write');
    		$table = $resource->getTableName('sales/order_grid');
    
    		$data = $this->getRequest()->getParam('data');
    		$rows = json_decode($data);
    		foreach ($rows as $row) {
    			$order_id = $row->id;
    			$color = $row->color;
    			$int_st = $row->int_st;
    			$ext_st = $row->ext_st;
    			$comment = $row->comment;
    	   
    			$query = "UPDATE {$table} SET color = '{$color}', int_status = '{$int_st}', internalcomment = '{$comment}', last_edit = NOW() WHERE entity_id = {$order_id}";
    			$writeConnection->query($query);
    	   
    			$order = Mage::getModel('sales/order')->load($order_id);
    
//     			(var_dump( $order_id, $order->getState(), $ext_st, $int_st ));
    			if($order->getState() == 'complete') {
    				$errors[] = "ID ".$order->getIncrementID().". You aren't able to change the status of completed orders.";
    			} elseif($ext_st=='complete') {
    				//$st = Mage_Sales_Model_Order::STATE_COMPLETE;
    				if (!$order->canInvoice()) {
    					//$this->_getSession()->addError($this->__("You aren't able to change the status of this order directly"));
    					$errors[] = "ID ".$order->getIncrementID().". You aren't able to change the status of this order directly. Please make sure to create shipment first.";
    					continue;
    				}
    				 
    				$savedQtys = array();
    				$invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($savedQtys);
    				if (!$invoice->getTotalQty()) {
    					continue;
    				}
    				$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
    				$invoice->register();
    				 
    				$invoice->getOrder()->setCustomerNoteNotify(false);
    				$invoice->getOrder()->setIsInProcess(true);
    				 
    				$transactionSave = Mage::getModel('core/resource_transaction')
    				->addObject($invoice)
    				->addObject($invoice->getOrder());
    				 
    				$transactionSave->save();
    			} elseif($ext_st=='canceled' || $int_st=='void') {
    				$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
    				try {
    					$order->save();
    				} catch (Exception $e) {
    					$errors[] = "ID ".$order->getIncrementID().". ".$e->getMessage();
    				}
    			} /*else {
    				$st = Mage_Sales_Model_Order::STATE_PROCESSING;
    				$order->setState($st, true, '', '');
    				try {
    					$order->save();
    				} catch (Exception $e) {
    					$errors[] = "ID ".$order->getIncrementID().". ".$e->getMessage();
    				}
    			}*/
    		}
    
    		if(count($errors)) {
    			echo implode("\n", $errors);
    		}
    		exit();
    	}
    }
    
    
    public function savetodosAction() {
    	$resource = Mage::getSingleton('core/resource');
    	$writeConnection = $resource->getConnection('core_write');
    
    	$table = $resource->getTableName('todolists');
    
    	$name = $this->getRequest()->getParam('todoname');
    
    	$upd = $this->getRequest()->getParam('status');
    	foreach ($upd as $k=>$v) {
    		if ($k > 0) {
    			$query = "UPDATE {$table} SET status={$v} WHERE id={$k}";
    			$writeConnection->query($query);
    		}
    	}
    	 
    	$add = $this->getRequest()->getParam('todonew');
    	foreach ($add as $k=>$v) {
    		$query = "INSERT INTO {$table} (`name`, `description`, `status`) VALUES ('{$name}', '".addslashes($v)."', '{$upd[$k]}')";
    		$writeConnection->query($query);
    	}
    	 
    	$del = $this->getRequest()->getParam('removetodo');
    	if (count($del) > 0) {
    		$query = "DELETE FROM {$table} WHERE id IN (" . implode(',', $del) . ")";
    		$writeConnection->query($query);
    	}
    	 
    	$this->_getSession()->addSuccess($this->__('The ' . $name . ' To Do list has been updated.'));
    
    	$this->_redirect('*/*/');
    }
}
