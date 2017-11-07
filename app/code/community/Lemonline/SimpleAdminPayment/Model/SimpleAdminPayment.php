<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 */


class Lemonline_SimpleAdminPayment_Model_SimpleAdminPayment extends Mage_Payment_Model_Method_Abstract
{

    protected $_code  = 'simpleadminpayment';
    
    protected $_canUseCheckout = false;
    protected $_canUseForMultishipping  = false;
    
    /**
     * (non-PHPdoc)
     * @see app/code/core/Mage/Payment/Model/Method/Mage_Payment_Model_Method_Abstract#authorize($payment, $amount)
     */
	public function authorize(Varien_Object $payment, $total) {
    	if($this->getConfigData('capture_auto') && $payment->getOrder()->canInvoice()) {
			$invoice = $payment->getOrder()->prepareInvoice();
    		$invoice->register();
    		$payment->getOrder()->addRelatedObject($invoice);
    	}
		if($this->getConfigData('shipment_auto') && $payment->getOrder()->canShip()) {
			$shipment = $payment->getOrder()->prepareShipment();
    		$shipment->register();
    		$payment->getOrder()->addRelatedObject($shipment);
    	}
    	return $this;
    }

}
