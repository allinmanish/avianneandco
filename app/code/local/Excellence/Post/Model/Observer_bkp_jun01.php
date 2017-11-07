<?php
ini_set("SMTP", "192.168.50.16");
ini_set("smtp_port", "27");

class Excellence_Post_Model_Observer {
	private $from_date = 7; // Process orders not older than 7 days
	
	public function buildQuery($data) {
		if(function_exists('http_build_query') && ini_get('arg_separator.output')=='&') return
		http_build_query($data);
		$tmp = array();
		foreach($data as $key=>$val) $tmp[] = rawurlencode($key) . '=' . rawurlencode($val);
		return implode('&', $tmp);
	}
	public function postSomething(Varien_Event_Observer $observer) {
		$parms = Mage::app()->getFrontController()->getRequest()->getParams();
		extract($parms);
		$payment;
        $order = $observer->getEvent()->getOrder();
        if($order->getFraudProcessed()){
        	return;
        }
        if($order->getPayment()->getMethod()=='authorizenet') {
	        $customer_id = $order->getCustomerId();
			$coupon_code = $order->getCouponCode();
			
			
// 			$getaway_result = $order->getState() == 'processing' ? 'approve' : 'decline';
// 			$getaway_error = $order->getState() == 'processing' ? '' : 'An error occurred during processing. Please try again.';
// 			$getaway_cvv = $order->getState() == 'processing' ? 'M' : 'N';
// 			$getaway_avs = Mage::getModel('core/session')->getData('excellence_post_avs', true);
			
			$comments = $order->getStatusHistoryCollection();
			foreach ($comments as $comment) {
				if (is_null($comment->getStatus())) {
					preg_match('/ - (.*?)\./is', $comment->getComment(), $matches);
					$transaction_result = $matches[1];
				}
			}
			
			if (!empty($transaction_result) && $transaction_result == 'successful') {
				$getaway_result = 'approve';
				$getaway_error = '';
				$getaway_cvv = 'M';
			} else {
				$getaway_result = 'decline';
				$getaway_error = 'An error occurred during processing. Please try again.';
				$getaway_cvv = 'N';
			}
			$getaway_avs = Mage::getModel('core/session')->getData('excellence_post_avs', true);
			$cc_num = Mage::getModel('core/session')->getData('excellence_post_mask', true);
			if (empty($cc_num)) {
				return;
			}
			//Get Shipping Detail
			$shipping=array();
			$_shippingAddress = $order->getShippingAddress();
	        $_billingAddress = $order->getBillingAddress();
			$items = $order->getAllItems();
			$itemcount = count($items);
			$name = array();
			$unitPrice = array();
			$sku = array();
			$ids = array();
			$qty = array();
			foreach ($items as $itemId => $item) {
				$name[] = $item->getName();
				$unitPrice[]=$item->getPrice();
				$sku[]=$item->getSku();
				$ids[]=$item->getProductId();
				$qty[]=$item->getQtyToInvoice();
			}
			$shipping_street = $_shippingAddress->getStreet();
			$billing_street = $_billingAddress->getStreet();
	        $exp = str_pad($order->getPayment()->getCcExpMonth(),2,'0',STR_PAD_LEFT) . substr($order->getPayment()->getCcExpYear(), -2);
	        $message = Mage::getModel('giftmessage/message');
	        $gift_message_id = $order->getGiftMessageId();
	        $message->load((int)$gift_message_id);
	
	        $postdata = array(
					// Command Fields
					"xKey" => "1897864691f644e48face201afadcdf6",
					"xVersion" => "4.5.4",
					"xSoftwareName" => "Magento",
					"xSoftwareVersion" => "1.9.2.1",
					"xCommand" => "Fraud:Submit",
					// Order Info
					"xOrderType" => "Internet",
					"xOrderID" => $order->getIncrementId(),
		          	"xAmount" => $order->getGrandTotal(),
		          	"xName" => $order->getCustomerName(),  // Customer Name
		          	"xOrderDate" => $order->getCreatedAt(),
		          	"xIP" => $_SERVER["REMOTE_ADDR"],
					"xExistingCustomer" => empty($customer_id) ? "N" : "Y",
					"xCoupon" => empty($coupon_code) ? "" : $order->getCouponCode(),
					// Misc Info
					"xCustomerComments" => substr($order->getCustomerNote(),0,250),
					"xComments" => substr($message->getData('message'),0,250),
					"xGatewayResult" => $getaway_result,
					"xGatewayError" => $getaway_error,
					// Bill To Info
					"xBillFirstName" => $_billingAddress->getFirstname(),
					"xBillLastName" => $_billingAddress->getLastname(),
					"xBillCompany" => $_billingAddress->getCompany(),
					"xBillStreet" => implode(" ",$billing_street),
					"xBillState" => $_billingAddress->getRegion(),
					"xBillCity" => $_billingAddress->getCity(),
					"xBillZip" => $_billingAddress->getPostcode(),
					"xBillPhone" => $_billingAddress->getTelephone(),
					"xBillCountry" => $_billingAddress->getCountry(),
					// Ship To Info
					"xShipMethod" => $order->getShippingMethod(),
					"xShipAmount" => $order->getShippingAmount(),
		          	"xShipFirstName" => $_shippingAddress->getFirstname(),
		          	"xShipLastName" => $_shippingAddress->getLastname(),
		          	"xShipCompany" => $_shippingAddress->getCompany(),
		          	"xShipStreet" => implode(" ",$shipping_street),
// 		          	"xShipStreet" => $shipping_street[0],
		          	"xShipState" => $_shippingAddress->getRegion(),
		          	"xShipCity" => $_shippingAddress->getCity(),
		          	"xShipZip" => $_shippingAddress->getPostcode(),
		          	"xShipPhone" => $_shippingAddress->getTelephone(),
		          	"xShipCountry" => $_shippingAddress->getCountry(),
					"xShipEmail" => $order->getCustomerEmail(),
	
					// CC Info
		          	"xCardNum" => $cc_num,
					"xGatewayCVV" => $getaway_cvv,
					"xGatewayAVS" => $getaway_avs,
			);
			$data = $this->buildQuery($postdata);
			$result = $this->_execRequest("https://x1.cardknox.com/gateway", $data);
			
			$xRefNum = $result['xRefNum'];
			Mage::log(" save_after ".$xRefNum ." order id: ".$order->getId() . " cc: ".$cc_num . " data: " . serialize($postdata), null, 'fraud.log');
			$this->_updateFraudStatus($xRefNum, $order->getId(), $order);
			$order->setFraudProcessed(true);
        }
    }
	public function postSomething2(Varien_Event_Observer $observer) {
		$parms=Mage::app()->getFrontController()->getRequest()->getParams();
        $order = $observer->getEvent()->getOrder();
		Mage::getModel('core/session')->setData('excellence_post_mask', substr($order->getPayment()->getCcNumber(), 0, 6) . "XXXXXX" . substr($order->getPayment()->getCcNumber(), -4));
    }
    
    public function _updateFraudStatus($xRefNum, $order_id, $order) {
    	Mage::log(" _updateFraudStatus ".$xRefNum ." order id: ".$order_id, null, 'fraud.log');
    	$postdata = array(
    			"xKey" => "1897864691f644e48face201afadcdf6",
    			"xVersion" => "4.5.2",
    			"xSoftwareName" => "Magento",
    			"xSoftwareVersion" => "1.9.2.1",
    			"xCommand" => "report:transaction",
    			"xRefNum" => $xRefNum,
    	);
    	$data = $this->buildQuery($postdata);
    	$orderData = $this->_execRequest("https://x1.cardknox.com/report", $data);
    	$reportData = json_decode($orderData["xReportData"]);
    		
    	$resource = Mage::getSingleton('core/resource');
    	$writeConnection = $resource->getConnection('core_write');
    	$table = $resource->getTableName('sales/order_grid');
    	try {
    		$query = "UPDATE {$table} SET fraud_status = '{$reportData->xStatus}', ref_num = '{$xRefNum}', last_edit = NOW() WHERE entity_id = {$order_id}";
    		if (in_array($reportData->xStatus, array(2,3))) {
    			$templateId = 53;
    			$sender = array('name' => 'Avianne & Co. Customer Service', 'email' => 'service@avianneandco.com');
    			$storeId = Mage::app()->getStore()->getId();
//     			if($order_id==14310) {
//     				die(var_dump( ($order) ));
//     			}
    			$address = $order->getBillingAddress();
    			$email = $order->customer_email;
    			$emailName = ($order->customer_firstname ? $order->customer_firstname : $address->firstname) . ' ' . ($order->customer_lastname ? $order->customer_lastname : $address->lastname);
    			$vars = array(
    					'customername'		=> ($order->customer_firstname ? $order->customer_firstname : $address->firstname) . ' ' . ($order->customer_lastname ? $order->customer_lastname : $address->lastname),
    					'ordernumber'		=> $order->increment_id
    			);
    			Mage::getModel('core/email_template')->addBcc('avianneandco@gmail.com')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);
    		} elseif($reportData->xStatus == 8) {
    			$baseDir = Mage::getBaseDir();
    			$pathToLog = $baseDir . DS . 'fraud.log';
    			$msg =  date('r') . ": #" . $order->increment_id . " xResponseError: " . $reportData->xResponseError . " | xRefNum: " . $xRefNum . "\n";
    			
    			file_put_contents($pathToLog, $msg, FILE_APPEND);
    		}
    		$writeConnection->query($query);
    	} catch (Exception $e) {
//     		Mage::log("[".date("c")."] CronJob: ".__CLASS__."::".__METHOD__." Id:".$order_id." Ref:".$xRefNum, null, 'fraud.log');
    	}
    }
    
    public function _parseResponse( $result, $header_size ) {
    	$res_string = mb_substr( $result, $header_size );
    	$data = array();
    	parse_str( $res_string, $data );
    	
    	return $data;
    }
    
    public function _execRequest($url, $data) {
    	$ch = curl_init($url);
    	if(!is_resource($ch))
    	{
    		return;
    	}
    	curl_setopt($ch,CURLOPT_HEADER, 1);
    	curl_setopt($ch,CURLOPT_POST, 1);
    	curl_setopt($ch,CURLOPT_TIMEOUT, 45);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    	$result = curl_exec($ch);
    	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    	curl_close($ch);
    	
    	return $this->_parseResponse($result, $header_size);
    }

//     public function fetchFraudStatus() {
//     	$order_collection = Mage::getResourceModel('sales/order_grid_collection')
// 		->setIsCustomerMode(true)
// 		->addAttributeToFilter('fraud_status', array('in' => array('0','6','7','8')))
// 		->addAttributeToSelect('*')
// 		->getData();
		
// 		foreach ($order_collection as $order) {
// 			Mage::log("[".date("c")."] CronJob: ".__CLASS__."::".__METHOD__." Id:".$order["entity_id"]." Ref:".$order["ref_num"], null, 'fraud.log');
// 			$orderObj = Mage::getModel('sales/order')->load($order["entity_id"]);
// 			$this->_updateFraudStatus($order["ref_num"], $order["entity_id"], $orderObj);
// 		}
//     }

    public function fetchFraudStatus() {
    	$fromDate = date('Y-m-d H:i:s', strtotime('-'.$this->from_date.' days'));
    	$toDate = date('Y-m-d H:i:s');
    	 
    	$order_collection = Mage::getResourceModel('sales/order_grid_collection')
    	->setIsCustomerMode(true)
    	->addAttributeToFilter('fraud_status', array('in' => array('0','6','7','8','')))
    	->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
    	->addAttributeToSelect('*')
    	->getData();
    
    	foreach ($order_collection as $order) {
			Mage::log("[".date("c")."] CronJob: ".__CLASS__."::".__METHOD__." Id:".$order["entity_id"]." Ref:".$order["ref_num"], null, 'fraud.log');
    		$orderObj = Mage::getModel('sales/order')->load($order["entity_id"]);
    		$this->_updateFraudStatus($order["ref_num"], $order["entity_id"], $orderObj);
    	}
    }
    
//     public function manuallyFetchOrderStatus($order_id, $cc = '') {
//     	$order = Mage::getModel('sales/order')->load($order_id);
//     	$customer_id = $order->getCustomerId();
//     	$coupon_code = $order->getCouponCode();
//     	$getaway_result = $order->getState() == 'processing' ? 'approve' : 'decline';
//     	$getaway_error = $order->getState() == 'processing' ? '' : 'An error occurred during processing. Please try again.';
//     	$getaway_cvv = $order->getState() == 'processing' ? 'M' : 'N';
//     	$getaway_avs = true;
//     	$cc_num = $cc;
//     	//Get Shipping Detail
//     	$shipping=array();
//     	$_shippingAddress = $order->getShippingAddress();
//     	$_billingAddress = $order->getBillingAddress();
//     	$items = $order->getAllItems();
//     	$itemcount = count($items);
//     	$name = array();
//     	$unitPrice = array();
//     	$sku = array();
//     	$ids = array();
//     	$qty = array();
//     	foreach ($items as $itemId => $item) {
//     		$name[] = $item->getName();
//     		$unitPrice[]=$item->getPrice();
//     		$sku[]=$item->getSku();
//     		$ids[]=$item->getProductId();
//     		$qty[]=$item->getQtyToInvoice();
//     	}
//     	$shipping_street = $_shippingAddress->getStreet();
//     	$billing_street = $_billingAddress->getStreet();
//     	$exp = str_pad($order->getPayment()->getCcExpMonth(),2,'0',STR_PAD_LEFT) . substr($order->getPayment()->getCcExpYear(), -2);
//     	$message = Mage::getModel('giftmessage/message');
//     	$gift_message_id = $order->getGiftMessageId();
//     	$message->load((int)$gift_message_id);
    	
//     	$postdata = array(
//     			// Command Fields
//     			"xKey" => "1897864691f644e48face201afadcdf6",
//     			"xVersion" => "4.5.2",
//     			"xSoftwareName" => "Magento",
//     			"xSoftwareVersion" => "1.7.0.2",
//     			"xCommand" => "Fraud:Submit",
//     			// Order Info
//     			"xOrderType" => "Internet",
//     			"xOrderID" => $order->getIncrementId(),
//     			"xAmount" => $order->getGrandTotal(),
//     			"xName" => $order->getCustomerName(),  // Customer Name
//     			"xOrderDate" => $order->getCreatedAt(),
//     			"xIP" => $_SERVER["REMOTE_ADDR"],
//     			"xExistingCustomer" => empty($customer_id) ? "N" : "Y",
//     			"xCoupon" => empty($coupon_code) ? "" : $order->getCouponCode(),
//     			// Misc Info
//     			"xCustomerComments" => substr($order->getCustomerNote(),0,250),
// 				"xComments" => substr($message->getData('message'),0,250),
//     			"xGatewayResult" => $getaway_result,
//     			"xGatewayError" => $getaway_error,
//     			// Bill To Info
//     			"xBillFirstName" => $_billingAddress->getFirstname(),
//     			"xBillLastName" => $_billingAddress->getLastname(),
//     			"xBillCompany" => $_billingAddress->getCompany(),
//     			"xBillStreet" => implode(" ",$billing_street),
//     			"xBillState" => $_billingAddress->getRegion(),
//     			"xBillCity" => $_billingAddress->getCity(),
//     			"xBillZip" => $_billingAddress->getPostcode(),
//     			"xBillPhone" => $_billingAddress->getTelephone(),
//     			"xBillCountry" => $_billingAddress->getCountry(),
//     			// Ship To Info
//     			"xShipMethod" => $order->getShippingMethod(),
//     			"xShipAmount" => $order->getShippingAmount(),
//     			"xShipFirstName" => $_shippingAddress->getFirstname(),
//     			"xShipLastName" => $_shippingAddress->getLastname(),
//     			"xShipCompany" => $_shippingAddress->getCompany(),
//     			"xShipStreet" => $shipping_street[0],
//     			"xShipState" => $_shippingAddress->getRegion(),
//     			"xShipCity" => $_shippingAddress->getCity(),
//     			"xShipZip" => $_shippingAddress->getPostcode(),
//     			"xShipPhone" => $_shippingAddress->getTelephone(),
//     			"xShipCountry" => $_shippingAddress->getCountry(),
//     			"xShipEmail" => $order->getCustomerEmail(),
    	
//     			// CC Info
//     			"xCardNum" => $cc_num,
//     			"xGatewayCVV" => $getaway_cvv,
//     			"xGatewayAVS" => $getaway_avs,
//     	);
//     	$data = $this->buildQuery($postdata);
    	
//     	die(var_dump( $postdata ));
//     }

    public function _checkFraudStatus($xRefNum) {
    	$postdata = array(
    			"xKey" => "1897864691f644e48face201afadcdf6",
    			"xVersion" => "4.5.2",
    			"xSoftwareName" => "Magento",
    			"xSoftwareVersion" => "1.9.2.1",
    			"xCommand" => "report:transaction",
    			"xRefNum" => $xRefNum,
    	);
    	$data = $this->buildQuery($postdata);
    	$orderData = $this->_execRequest("https://x1.cardknox.com/report", $data);
    	$reportData = json_decode($orderData["xReportData"]);
    
    	return $reportData;
    }
}
