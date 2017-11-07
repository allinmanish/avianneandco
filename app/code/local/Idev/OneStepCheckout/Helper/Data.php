<?php

class Idev_OneStepCheckout_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function setCustomerComment($observer)
    {
		$enable_comments = Mage::getStoreConfig('checkout/onestepcheckout/enable_comments');
		
		if($enable_comments)	{
			$orderComment = $this->_getRequest()->getPost('onestepcheckout_comments');
			$orderComment = trim($orderComment);
	
			if ($orderComment != "")
			{
				$observer->getEvent()->getOrder()->setOnestepcheckoutCustomercomment($orderComment);
			}
		}
    }
}
