<?php

if(mageFindClassFile("Some_Other_Class")){
    /** @noinspection PhpUndefinedClassInspection */
    class Eye4Fraud_Connector_Model_Payflowpro_Parent extends Some_Other_Class{}
}
else{
    class Eye4Fraud_Connector_Model_Payflowpro_Parent extends Mage_Paypal_Model_Payflowpro{}
}

/**
 * Payflow Pro payment gateway model
 *
 * @category    Eye4Fraud
 * @package     Eye4Fraud_Connector
 * @author      Mikhail Valiushko
 */

class Eye4Fraud_Connector_Model_Payflowpro extends  Eye4Fraud_Connector_Model_Payflowpro_Parent
{
    protected $_responseData = array();

    /**
     * Post request to gateway and return response
     *
     *
     * @param Varien_Object $request
     * @return Varien_Object
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