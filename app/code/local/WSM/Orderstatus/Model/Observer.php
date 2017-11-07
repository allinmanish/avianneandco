<?php

class WSM_Orderstatus_Model_Observer {
	
	public function process() {
		
// 		$collection = Mage::getResourceModel('sales/order_grid_collection')
// 		->addFieldToFilter('main_table.method', 'authorizenet');
// 		$collection->getSelect()->join( array('order_payment' => 'sales_flat_order_payment'), 'order_payment.parent_id = main_table.entity_id' )
// 		->where("order_payment.method LIKE 'authorizenet'");
		
		$transactions = Mage::getModel('sales/order_payment')->getCollection()
		->addFieldToFilter('main_table.method', 'authorizenet');
		
		$query = $transactions->getSelect()->join( array('transaction' => 'sales_payment_transaction'), 'transaction.payment_id = main_table.entity_id' )
		->where("transaction.created_at >= '" . date('Y-m-d H:i:s', strtotime('-31 days')) . "'")
		->where("transaction.is_closed != 1")
		->where("transaction.txn_type NOT IN ('void')");
		
		foreach ($transactions as $trans) {
			
			$orderId = $trans->getOrderId();
			$transactionId = $trans->getTxnId();
			$transactionDetails = $this->_getTransactionDetails($transactionId);
			$transactionStatus = $transactionDetails->getTransactionStatus();
			
			try {
				$resource = Mage::getSingleton('core/resource');
				$writeConnection = $resource->getConnection('core_write');
				$table = $resource->getTableName('sales/order_grid');
					
				$query = "UPDATE {$table} SET int_status = '{$transactionStatus}', last_edit = NOW() WHERE entity_id = {$orderId}";
				$writeConnection->query($query);
				Mage::log('Order #' . $orderId . " was updated. Trans status: " . $transactionStatus, null, 'orderstatus.log');
				
				if (in_array($transactionStatus, array('voided','refundSettledSuccessfully','refundPendingSettlement'))) {
					$order = Mage::getModel('sales/order');
					$order->load($orderId);
				
					if($order->canCancel()) {
						$order->cancel();
						$order->save();
						Mage::log('Order #' . $orderId . " was canceled. Trans status: " . $transactionStatus, null, 'orderstatus.log');
					}
				}
			} catch (Mage_Core_Exception $e) {
				Mage::logException($e);
			}
		}
    }
    
    protected function _getTransactionDetails($transactionId) {
    	$paygate = Mage::getModel('paygate/authorizenet');
    		
    	$requestBody = sprintf(
    			'<?xml version="1.0" encoding="utf-8"?>'
    			. '<getTransactionDetailsRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'
    			. '<merchantAuthentication><name>%s</name><transactionKey>%s</transactionKey></merchantAuthentication>'
    			. '<transId>%s</transId>'
    			. '</getTransactionDetailsRequest>',
    			$paygate->getConfigData('login'),
    			$paygate->getConfigData('trans_key'),
    			$transactionId
    			);
    		
    	$client = new Varien_Http_Client();
    	$uri = $paygate->getConfigData('cgi_url_td');
    	$uri = $uri ? $uri : $paygate::CGI_URL_TD;
    	$client->setUri($uri);
    	$client->setConfig(array('timeout'=>45));
    	$client->setHeaders(array('Content-Type: text/xml'));
    	$client->setMethod(Zend_Http_Client::POST);
    	$client->setRawData($requestBody);
    		
    	$debugData = array(
    			'url' => $uri,
    			'request' => $requestBody
    	);
    		
    	try {
    		$responseBody = $client->request()->getBody();
    		$debugData['result'] = $responseBody;
    		libxml_use_internal_errors(true);
    		$responseXmlDocument = new Varien_Simplexml_Element($responseBody);
    		libxml_use_internal_errors(false);
    	} catch (Exception $e) {
    		$debugData['exception'] = $e->getMessage();
//     		$paygate->_debug($debugData);
    		Mage::throwException(Mage::helper('paygate')->__('Transaction status fetching error.'));
    	}
    		
//     	$paygate->_debug($debugData);
    	
    	return $this->_parseTransactionDetailsXmlResponseToVarienObject($responseXmlDocument);
    }
    
    protected function _parseTransactionDetailsXmlResponseToVarienObject(Varien_Simplexml_Element $responseXmlDocument)
    {
    	$response = new Varien_Object;
    	$responseTransactionXmlDocument = $responseXmlDocument->transaction;
    	//main fields for generating order status:
    	$response
    	->setResponseCode((string)$responseTransactionXmlDocument->responseCode)
    	->setResponseReasonCode((string)$responseTransactionXmlDocument->responseReasonCode)
    	->setTransactionStatus((string)$responseTransactionXmlDocument->transactionStatus)
    	;
    	//some additional fields:
    	isset($responseTransactionXmlDocument->responseReasonDescription) && $response->setResponseReasonDescription((string)$responseTransactionXmlDocument->responseReasonDescription);
    	isset($responseTransactionXmlDocument->FDSFilterAction)           && $response->setFdsFilterAction((string)$responseTransactionXmlDocument->FDSFilterAction);
    	isset($responseTransactionXmlDocument->FDSFilters)                && $response->setFdsFilters(serialize($responseTransactionXmlDocument->FDSFilters->asArray()));
    	isset($responseTransactionXmlDocument->transactionType)           && $response->setTransactionType((string)$responseTransactionXmlDocument->transactionType);
    	isset($responseTransactionXmlDocument->submitTimeUTC)             && $response->setSubmitTimeUtc((string)$responseTransactionXmlDocument->submitTimeUTC);
    	isset($responseTransactionXmlDocument->submitTimeLocal)           && $response->setSubmitTimeLocal((string)$responseTransactionXmlDocument->submitTimeLocal);
    
    	return $response;
    }
    
}
