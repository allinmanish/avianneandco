<?php
class WSM_Fraud_Model_Observer
{

    /**
     * Log fraud checkout attempts
     * @param $observer
     */
    public function logFraud($observer)
    {
        $e = $observer->getEvent()->getError();
        
        if ($e->getMessage() == "Gateway error: This transaction has been declined.") {
        	$remoteAddr = Mage::helper('core/http')->getRemoteAddr();
        	$log = Mage::getModel('wsm_fraud/fraud')->load($remoteAddr, 'ip');
        	if (empty($log->getId())) {
        		$log->setIp($remoteAddr);
        		$log->setCount(1);
        		$log->setLastAttempt(Mage::getSingleton('core/date')->gmtDate());
			    $log->save();
        	} elseif($log->getCount()>5) {
        		$helper = Mage::helper('wsm_fraud');
        		$helper->blockFraudIp($remoteAddr);
        	} else {
        		$log->setCount($log->getCount()+1);
        		$log->setLastAttempt(Mage::getSingleton('core/date')->gmtDate());
        		$log->save();
        	}
        }
        
        return;
    }
    
    /**
     * Cleanup fraud logs / 24 hours /
     * @param $observer
     */
    public function cleanLog() {
    	$date = new Zend_Date(Mage::getSingleton('core/date')->timestamp());
    	$date->subDay('1');
    	$dd = $date->toString('y-MM-dd HH:mm:ss');
    	$logs = Mage::getModel('wsm_fraud/fraud')->getCollection();
    	$logs->addFieldToFilter('last_attempt', array(
    			'lteq'=>$dd
    	));
    	foreach ($logs as $log) {
    		$log->delete();
    	}
    }
    
}
