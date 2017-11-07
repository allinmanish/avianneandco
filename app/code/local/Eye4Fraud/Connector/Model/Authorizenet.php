<?php

if(mageFindClassFile("Some_Other_Class")){
    /** @noinspection PhpUndefinedClassInspection */
    class Eye4Fraud_Connector_Model_Authorizenet_Parent extends Some_Other_Class{}
}
else{
    class Eye4Fraud_Connector_Model_Authorizenet_Parent extends Mage_Paygate_Model_Authorizenet{}
}

/**
 * Extend Authorize.net payment instance to keep access to response data

 * @category    Eye4Fraud
 * @package     Eye4Fraud_Connector
 * @author      Mikhail Valiushko
 */
class Eye4Fraud_Connector_Model_Authorizenet extends Eye4Fraud_Connector_Model_Authorizenet_Parent
{
    /** @var Mage_Paygate_Model_Authorizenet_Result  */
    protected $_responseData = null;

    /**
     * Post request to gateway and return responce
     *
     * @param Mage_Paygate_Model_Authorizenet_Request|Varien_Object $request
     * @return Mage_Paygate_Model_Authorizenet_Result
     */
    protected function _postRequest(Varien_Object $request)
    {
        $this->_responseData = parent::_postRequest($request);
        return $this->_responseData;
    }

    /**
     * Return response data
     * @return array
     */
    public function getResponseData(){
        return $this->_responseData;
    }
}