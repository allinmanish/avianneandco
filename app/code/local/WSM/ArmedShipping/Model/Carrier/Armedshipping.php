<?php
/**
* International Shipping method adapter
*/
class WSM_ArmedShipping_Model_Carrier_Armedshipping extends Mage_Shipping_Model_Carrier_Abstract
{

  protected $_code = 'armedshipping';
 
  /**
   * Collect rates for this shipping method based on information in $request
   *
   * @param Mage_Shipping_Model_Rate_Request $data
   * @return Mage_Shipping_Model_Rate_Result
   */
  public function collectRates(Mage_Shipping_Model_Rate_Request $request)
  {
    // skip if not enabled
    if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')) {
        return false;
    }
    // get necessary configuration values
    $handling = Mage::getStoreConfig('carriers/'.$this->_code.'/handling_fee');
 
    // this object will be returned as result of this method
    // containing all the shipping rates of this method
    $result = Mage::getModel('shipping/rate_result');
 

      // create new instance of method rate
      $method = Mage::getModel('shipping/rate_result_method');
 
      // record carrier information
      $method->setCarrier($this->_code);
      $method->setCarrierTitle(Mage::getStoreConfig('carriers/'.$this->_code.'/title'));
 
      // record method information
      $method->setMethod("ARMED_SHIPPING");
      $method->setMethodTitle("USPS APO/FPO Priority International");
 
      // rate cost is optional property to record how much it costs to vendor to ship
      $method->setCost(0);
      $method->setPrice(0+$handling);
 
      // add this rate to the result
      $result->append($method);

    return $result;
  }
 
  /**
   * This method is used when viewing / listing Shipping Methods with Codes programmatically
   */
  public function getAllowedMethods() {
    return array($this->_code => $this->getConfigData('name'));
  }
}