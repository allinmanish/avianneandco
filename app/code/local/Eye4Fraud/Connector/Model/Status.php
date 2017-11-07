<?php
/**
 * Model of single fraud status for one order
 *
 * @category   Eye4Fraud
 * @package    Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_Status extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'eye4fraud_connector_status';

    protected function _construct()
    {
        $this->_init('eye4fraud_connector/status');
        $this->_dataSaveAllowed = false;
    }

    /**
     * Retrieve status from remote server and save model
     * @return $this
     */
	public function retrieveStatus(){
        $fraudData = Mage::helper('eye4fraud_connector')->getOrderStatus($this->getData('order_id'));
        
        if (in_array($fraudData['StatusCode'], array('D','F'))) {
        		$templateId = 54;
    			$sender = array('name' => 'Avianne & Co. Customer Service', 'email' => 'service@avianneandco.com');
    			
    			$order = Mage::getModel('sales/order')->loadByIncrementId($this->getData('order_id'));
    			$storeId = Mage::app()->getStore()->getId();
    			$address = $order->getBillingAddress();
    			$email = $order->customer_email;
    			$emailName = ($order->customer_firstname ? $order->customer_firstname : $address->firstname) . ' ' . ($order->customer_lastname ? $order->customer_lastname : $address->lastname);
    			$vars = array(
    					'customername'		=> ($order->customer_firstname ? $order->customer_firstname : $address->firstname) . ' ' . ($order->customer_lastname ? $order->customer_lastname : $address->lastname),
    					'ordernumber'		=> $order->increment_id
    			);
    			Mage::getModel('core/email_template')->addBcc('avianneandco@gmail.com')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);
        }
        
        if($fraudData['error']){
            $this->setData('error', true);
        }

        $this->setData('status', $fraudData['StatusCode']);
        $this->setData('description', $fraudData['Description']);
        $this->setData('updated_at', Mage::getModel('core/date')->date('Y-m-d H:i:s'));
        /**
         * A little hack to restore order_id field after model was saved
         */
        $tmp_order_id = $this->getData('order_id');
        $this->save();
        $this->setData('order_id',$tmp_order_id);
        return $this;
    }

    /**
     * Set or get flag is object new
     * @param null $flag
     * @return bool
     */
    public function isObjectNew($flag=null){
        $this->getResource()->setNewFlag(true);
        return parent::isObjectNew($flag);
    }

    protected function _beforeSave(){
        parent::_beforeSave();
        $saveStatuses = Mage::helper('eye4fraud_connector')->getFinalStatuses();
        if(in_array($this->getData('status'), $saveStatuses)) $this->_dataSaveAllowed = true;
        $cron_enabled = Mage::helper('eye4fraud_connector')->getConfig('cron_settings/enabled');
        if($cron_enabled)  $this->_dataSaveAllowed = true;
        if($this->isEmpty()){
            /** @var Eye4Fraud_Connector_Model_Status $statusObject */
            $statusObject = Mage::getModel('eye4fraud_connector/status');
            $statusObject->load($this->getData('order_id'));
            if(!$statusObject->isEmpty()){
                Mage::helper("eye4fraud_connector")->log('Order #'.$this->getData('order_id').' already inserted');
                $this->setData($statusObject->getData());
                $this->_dataSaveAllowed = false;
            }
        }
        return $this;
    }
}
