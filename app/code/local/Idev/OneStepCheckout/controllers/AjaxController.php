<?php

class Idev_OneStepCheckout_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        die('ajaxcontroller!');
        
        $this->loadLayout();
        $this->renderLayout();
    }
    
    private function _isEmailRegistered($email)
    {
        $model = Mage::getModel('customer/customer');
        $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
        
        if($model->getId() == NULL)    {
            return false;
        }
        
        return true;    
    }
    
    public function check_emailAction()
    {
        $validator = new Zend_Validate_EmailAddress();
        $email = $this->getRequest()->getPost('email', false);
        
        $data = array('result'=>'invalid');
        
        
        if($email && $email != '')  {
            if(!$validator->isValid($email))    {
                
            }
            else    {
                
                // Valid email, check for existance
                if($this->_isEmailRegistered($email))   {
                    $data['result'] = 'exists';
                }
                else    {
                    $data['result'] = 'clean';
                }
            }
        }
        
        $this->getResponse()->setBody(Zend_Json::encode($data));
    }
    
    public function forgotPasswordAction()
    {
        $email = $this->getRequest()->getPost('email', false);
        
        if (!Zend_Validate::is($email, 'EmailAddress')) { 
            $result = array('success'=>false);
        }
        else    {
            
            $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);            
            
            if ($customer->getId()) {
                try {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();
                    $result = array('success'=>true);
                }
                catch (Exception $e){
                    $result = array('success'=>false, 'error'=>$e->getMessage());
                }
            }
            else {
                $result = array('success'=>false, 'error'=>'notfound');
            }            
        }
        
        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    public function loginAction()
    {
        $username = $this->getRequest()->getPost('onestepcheckout_username', false);
        $password = $this->getRequest()->getPost('onestepcheckout_password', false);
        $session = Mage::getSingleton('customer/session');
        
        $result = array(
            'success' => false
        );
        
        if($username && $password) {
            try {
                $session->login($username, $password); 
            } catch(Exception $e)   {
                $result['error'] = $e->getMessage();
            }
            
            if(!isset($result['error']))    {
                
                //$quote = Mage::getSingleton('checkout/type_onepage')->getQuote();
                //$quote->collectTotals()->save();
                
                
                $result['success'] = true;
            }
        }
        else    {
            $result['error'] = 'Please enter a username and password.';
        }
        
        $this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
	
	public function save_billingAction()
	{
		$billing_data = $this->getRequest()->getPost('billing');
		$shipping_data = $this->getRequest()->getPost('shipping');
        $shipping_method = $this->getRequest()->getPost('shipping_method', false);
		
		$helper = Mage::helper('onestepcheckout/checkout');
		
		$helper->load_exclude_data($billing_data);
		$helper->load_exclude_data($shipping_data);
		
		if(isset($billing_data['use_for_shipping']) && $billing_data['use_for_shipping'] == '1')	{
			$shipping_address_source = $billing_data;
		}
		else	{
			$shipping_address_source = $shipping_data;
		}
		
		$this->_getOnepage()->getQuote()->getShippingAddress()
			->setCountryId($shipping_address_source['country_id'])
			->setCity($shipping_address_source['city'])
			->setPostcode($shipping_address_source['postcode'])
			->setRegionId($shipping_address_source['region_id'])
			->setRegion($shipping_address_source['region'])
			->setFirstname($shipping_address_source['firstname'])
			->setLastname($shipping_address_source['lastname'])
			->setCompany($shipping_address_source['company'])
			->setFax($shipping_address_source['fax'])
			->setTelephone($shipping_address_source['telephone'])
			->setStreet($shipping_address_source['street'][0])
			->save()
			->setCollectShippingRates(true);
            
            
      
        
        	
		$this->_getOnepage()->getQuote()->getBillingAddress()
			->setCountryId($billing_data['country_id'])
			->setCity($billing_data['city'])
			->setPostcode($billing_data['postcode'])
			->setRegionId($billing_data['region_id'])
			->setRegion($billing_data['region'])
			->setFirstname($billing_data['firstname'])
			->setLastname($billing_data['lastname'])
			//->setEmail($billing_data['email'])
			->setCompany($billing_data['company'])
			->setFax($billing_data['fax'])
			->setTelephone($billing_data['telephone'])
			->setStreet($billing_data['street'][0])
			->save();
            
        if(isset($billing_data['email']))   {
            $this->_getOnepage()->getQuote()->getBillingAddress()->setEmail($billing_data['email'])->save();
        }
        
		$customerAddressId = $billing_data['address_id'];		
		$result = $this->_getOnepage()->saveBilling($billing_data, $customerAddressId);
		
		if($helper->differentShippingAvailable())	{
			if(!isset($billing_data['use_for_shipping']) || $billing_data['use_for_shipping'] != '1')	{
				$shipping_address_id = $shipping_data['address_id'];
				$shipping_result = $this->_getOnepage()->saveShipping($shipping_data, $shipping_address_id);
			}
		}

        if($shipping_method && $shipping_method != '')  {
            $this->_getOnepage()->saveShippingMethod($shipping_method);
        }  
		
		$this->_getOnepage()->getQuote()->collectTotals()->save();
				
		//echo '<pre>' . print_r($billing,1) . '</ >';
		$this->loadLayout(false);
		$this->renderLayout();			
		
	}
	
	public function set_methods_separateAction()
	{
		$shipping_method = $this->getRequest()->getPost('shipping_method');
		
		if($shipping_method != '')	{
			$result = $this->_getOnepage()->saveShippingMethod($shipping_method);			
		}
		
		$payment_method = $this->getRequest()->getPost('payment_method');
		
		if($payment_method != '')	{		
			try	{
				$payment = $this->getRequest()->getPost('payment', array());
				$payment['method'] = $payment_method;
				$payment_result = $this->_getOnepage()->savePayment($payment);			
			}
			catch(Exception $e)	{
				//die('Error: ' . $e->getMessage());
				// Silently fail for now
			}
		}
		
		$this->loadLayout(false);
		$this->renderLayout();	
	}	
	
	public function set_methodsAction()
	{
		$shipping_method = $this->getRequest()->getPost('shipping_method');
		
		if($shipping_method != '')	{
			$result = $this->_getOnepage()->saveShippingMethod($shipping_method);			
		}
		
		$payment_method = $this->getRequest()->getPost('payment_method');
		
		if($payment_method != '')	{		
			try	{
				$payment = $this->getRequest()->getPost('payment', array());
				$payment['method'] = $payment_method;
				$payment_result = $this->_getOnepage()->savePayment($payment);			
			}
			catch(Exception $e)	{
				//die('Error: ' . $e->getMessage());
				// Silently fail for now
			}
		}
		
		$this->loadLayout(false);
		$this->renderLayout();	
	}
	
	public function set_payment_methodAction()
	{
		$payment_method = $this->getRequest()->getPost('payment_method');
		$payment = array('method' => $payment_method);
		$result = $this->_getOnepage()->savePayment($payment);
			
		$this->loadLayout(false);
		$this->renderLayout();	
	}	

	public function set_shipping_methodAction()
	{
		$shipping_method = $this->getRequest()->getPost('shipping_method');
		$result = $this->_getOnepage()->saveShippingMethod($shipping_method);
				
		$this->loadLayout(false);
		$this->renderLayout();	
	}
	
	
	private function _getOnepage()
	{
		return Mage::getSingleton('checkout/type_onepage');
	}
	
	
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

	
	public function registerAction()
	{
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        if ($this->getRequest()->isPost()) {
            $errors = array();
			
			

            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }
            
            $lastOrderId = $this->_getOnepage()->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($lastOrderId);
            $billing = $order->getBillingAddress();
            
            $customer->setData('firstname', $billing->getFirstname());
            $customer->setData('lastname', $billing->getLastname());
            $customer->setData('email', $order->getCustomerEmail());
            

            foreach (Mage::getConfig()->getFieldset('customer_account') as $code=>$node) {
                //echo $code . ' -> ' . $node . '<br/>';
                if ($node->is('create') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }
            
            // print_r($customer->toArray());
            

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }

            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                $address = Mage::getModel('customer/address')
                    ->setData($this->getRequest()->getPost())
                    ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                    ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false))
                    ->setId(null);
                $customer->addAddress($address);

                $errors = $address->validate();
                if (!is_array($errors)) {
                    $errors = array();
                }
            }

			$result = array(
                'success' => false,
                'message' => false,
                'error' => false,
            );
            

            try {
                $validationCustomer = $customer->validate();
                if (is_array($validationCustomer)) {
                    $errors = array_merge($validationCustomer, $errors);
                }
                $validationResult = count($errors) == 0;
				
                //var_dump($validationResult);
                
                if (true === $validationResult) {
                   
                    $customer->save();
                    
                    $result['success'] = true;

                    if ($customer->isConfirmationRequired()) {
                        
                        $customer->sendNewAccountEmail('confirmation', $this->_getSession()->getBeforeAuthUrl());
                        $this->_getSession()->addSuccess($this->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.',
                            Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())
                        ));
                        
                        $result['message'] = 'email_confirmation';
                        
                        //$this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                        //return;
                    }
                    else {
                        $this->_getSession()->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);
                        
                        $result['message'] = 'customer_logged_in';
                    }
                    
                    // Add the last order to this account
                    $order->setCustomerId($customer->getId());
                    $order->setCustomerIsGuest(false);
                    $order->setCustomerGroupId($customer->getGroupId());
                    $order->save();    
                    
                    // Dispatch event to trigger downloadable products
                    /*
                    $items = $order->getItemsCollection();

                    foreach($items as $item)    {
                        Mage::dispatchEvent('sales_order_item_save_after', array('item'=>$item));
                    } */
                                    
                    
                    
                } else {
                    $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            //$this->_getSession()->addError($errorMessage);
                        }
                        
                        $result['error'] = 'validation_failed';
                        $result['errors'] = $errors;
                        
                    }
                    else {
                        //$this->_getSession()->addError($this->__('Invalid customer data'));
                        $result['error'] = 'invalid_customer_data';
                    }
                }
            }
            catch (Mage_Core_Exception $e) {

                $result['error'] = $e->getMessage();
                
                //$this->_getSession()->addError($e->getMessage())
                //    ->setCustomerFormData($this->getRequest()->getPost());
            }
            catch (Exception $e) {
                
                $result['error'] = $e->getMessage();
                
                //$this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                //    ->addException($e, $this->__('Can\'t save customer'));
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
        
        //
        //$result['error'] = 'redirect_to_create'
		///die('About to redirect to create');

        //$this->_redirectError(Mage::getUrl('*/*/create', array('_secure'=>true)));
    }

	
    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess($this->__('Thank you for registering with %s', Mage::app()->getStore()->getName()));

        $customer->sendNewAccountEmail($isJustConfirmed ? 'confirmed' : 'registered');

        $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }	
	
	
	
	
	
	
	
	
	
	
}

?>