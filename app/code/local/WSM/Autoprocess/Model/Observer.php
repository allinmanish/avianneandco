<?php

class Wsm_Autoprocess_Model_Observer {

	public function process() {
		$orders = Mage::getModel('sales/order')->getCollection()
		->addFieldToFilter('main_table.status', array('in' => array('fraud','processing')));
		$orders->getSelect()->join( array('order_grid' => 'sales_flat_order_grid'), 'order_grid.entity_id = main_table.entity_id', array('order_grid.fraud_status') )
		->where("main_table.entity_id > 17100")
		->where("order_grid.fraud_status IN(1,4, 2)");

		foreach ($orders as $order) {
			$orderId = $order->getId();
			$fullOrder = Mage::getModel('sales/order')->load($orderId);
			
			if( $order->getState() != 'processing' ||  $order->getStatus() != 'processing' ) {
				$fullOrder->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
				$fullOrder->setStatus('processing', false);
				$fullOrder->save();
			}
			
			try {
				if (in_array($order->getFraudStatus(), array(1,4))) {
					if(!$fullOrder->canInvoice()) {
						Mage::log(Mage::helper('core')->__('Cannot create an invoice.'), null, 'autoprocess.log');
						continue;
					}
					$invoice = Mage::getModel('sales/service_order', $fullOrder)->prepareInvoice();
					if (!$invoice->getTotalQty()) {
						Mage::log(Mage::helper('core')->__('Cannot create an invoice without products.'), null, 'autoprocess.log');
						continue;
					}
					$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
					$invoice->register();
					$transactionSave = Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder());
					$transactionSave->save();
				} elseif(in_array($order->getFraudStatus(), array(2))) {
					// 					if(!$fullOrder->canVoidPayment()) {
					// 						Mage::throwException(Mage::helper('core')->__('Cannot void payment.'));
					// 					}
					if (!$fullOrder->canCancel()) {
						Mage::log(Mage::helper('core')->__('Cannot cancel order.'), null, 'autoprocess.log');
						continue;
					}
					// 					$fullOrder->getPayment()->void(
					// 							new Varien_Object() // workaround for backwards compatibility
					// 					);
					$fullOrder->cancel();
					$fullOrder->save();
				}
			} catch (Mage_Core_Exception $e) {
				Mage::logException($e);
			}
		}
	}

}
