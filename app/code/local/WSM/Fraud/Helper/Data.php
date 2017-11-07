<?php
 
class WSM_Fraud_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function blockFraudIp($ip) {
		Mage::log("Blocked: '" . $ip . "'", null, 'frauds/checkout.txt');
	}
}