<?php

/**
 * Rewrite class to catch PAYERSTATUS and ADDRESSSTATUS into payment additional info array.
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_PaypalUk_Api_Nvp extends Mage_PaypalUk_Model_Api_Nvp
{
    public function _construct(){
        parent::_construct();
        $this->_globalMap['PAYERSTATUS'] = 'payer_status';
        $this->_globalMap['ADDRESSSTATUS'] = 'address_status';
        $this->_paymentInformationResponse[] = 'PAYERSTATUS';
    }
}