<?php
/**
 *	OneStepCheckout main block
 *	@author Jone Eide <mail@onestepcheckout.com>
 *	@copyright Jone Eide <mail@onestepcheckout.com>
 *
 */
 
 
class Idev_OneStepCheckout_Block_Checkout extends Mage_Checkout_Block_Onepage_Abstract	{

    var $formErrors;
	var $settings;
	var $log = array();

	private function _loadConfig()
	{	
		$this->settings = Mage::helper('onestepcheckout/checkout')->loadConfig();
	}
	
	private function _checkCountry()
	{
		$onepage = $this->getOnepage();
		$quote = $onepage->getQuote();
		$shipping = $quote->getShippingAddress();
		$billing = $quote->getBillingAddress();
		$default_country = false;
		$country_id = $shipping->getCountryId();
		
		if(!$country_id || $country_id == '')	{
			// No country saved at this point
			
            
            
			$default_country = false;
			
			if( $this->settings['enable_geoip'] )	{
				
				$geoip = Net_GeoIP::getInstance($this->settings['geoip_database']);
					
				try	{
					$default_country = $geoip->lookupCountryCode($_SERVER['REMOTE_ADDR']);
					$this->log[] = 'Set country based on GeoIP (result: ' . $default_country . ')';
				} catch(Exception $e)	{
					$default_country = false;
					$this->log[] = 'GeoIP threw exception: ' . $e->getMessage();
				}

				
			}
			
			if(!$default_country)	{
				if( $this->settings['default_country'] )	{
					$default_country = $this->settings['default_country'];
					$this->log[] = 'Set country based on default country settings (result: ' . $default_country . ')';
				}
				else	{
					$default_country = 'US'; // Last resort
					$this->log[] = 'Set country to US as a last resort';
				}
			}
		
			if($default_country)	{
            	//$shipping->setCountryId($default_country)->save();
            	
				$shipping->setCountryId($default_country)->setCollectShippingRates(true)->save();
				$billing->setCountryId($default_country)->save();
			}

			/* Hack to set same shipping as billing by default */
            
            
			$shipping->setSameAsBilling(true)->save();
			
			
		}
		else	{
			$this->log[] = 'Country already saved, don\'t touch it';
		}
	}

	public function _getDefaultShippingMethod()
	{
		if($this->settings['default_shipping_method'] != '')	{
			return $this->settings['default_shipping_method'];
		}
		else	{
			$check_single = $this->_checkSingleShippingMethod();
			if($check_single)	{
				return $check_single;
			}
		}
	}

	private function _checkShippingMethod()
	{
		$onepage = $this->getOnepage();
		$quote = $this->getOnepage()->getQuote();
		$method = $quote->getShippingAddress()->getShippingMethod();
		
		if(!$method || $method == '') 	{
			// If no pre-set shipping method
			if( $this->settings['default_shipping_method'] != '' )	{

				$onepage->saveShippingMethod( $this->settings['default_shipping_method'] );
				$this->log[] = 'Set shipping method based on default settings (set to: ' . $this->settings['default_shipping_method'] . ')';
			}
			else	{
				$method = $this->_checkSingleShippingMethod();
				
				if($method)	{
					$onepage->saveShippingMethod($method);
					$this->log[] ='Set shipping method to ' . $method . ' because it was only option available';
				}
				else	{
					$this->log[] = 'No shipping method to set as default, leave blank'; 
				}
			}
		}
		else	{
			$this->log[] = 'Shipping method already saved, don\'t touch it';
		}
	}
	
	private function _checkPaymentMethod()
	{
	
		$onepage = $this->getOnepage();
		$method = $onepage->getQuote()->getPayment()->getMethod();
				
		if(!$method || $method == '')	{
						
			
			if( $this->settings['default_payment_method'] != '' )	{
				
				
				$payment = array('method' => $this->settings['default_payment_method'] );
				
				try {
					$result = $onepage->savePayment($payment);
				}
				catch (Mage_Payment_Exception $e) {
					if ($e->getFields()) {
						$result['fields'] = $e->getFields();
					}
					$result['error'] = $e->getMessage();
				}
				catch (Exception $e) {
					$result['error'] = $e->getMessage();
				}	

							
				
				if(isset($result['error']))	{
					$this->log[] = 'Unable to set default payment method (' . $this->settings['default_payment_method'] . ') with error: ' . $result['error'];
				}
				else	{
					$this->log[] = 'Set payment method based on default settings (set to: ' . $this->settings['default_payment_method'] . ')';
				}			
			}
			else	{
				$this->log[] = 'No payment method set as default, leave blank';
			}
		}
		else	{
			$this->log[] = 'Payment method set already, don\'t touch';
		}
	}
	
	
	private function _checkSingleShippingMethod()
	{
        $rates = $this->getOnepage()->getQuote()->getShippingAddress()->getShippingRatesCollection();
		$rateCodes = array();
		
		foreach($rates as $rate)    {
			if(!in_array($rate->getCode(), $rateCodes)) {
				$rateCodes[] = $rate->getCode();
			}
		}
		
		if(count($rateCodes) == 1)  {
			return $rateCodes[0];
		}
		
		return false;
	}
	
	private function _isLoggedInWithAddresses()
	{
		$helper = $this->helper('customer');
		if( $helper->isLoggedIn() && $helper->customerHasAddresses() )	{
			return true;
		}
		
		return false;
	}
	
	private function _isLoggedIn()
	{
		$helper = $this->helper('customer');
		if( $helper->isLoggedIn() )	{
			return true;
		}
		
		return false;
	
	}
	
	private function _checkLoggedInUser()
	{
		return;
	
		$helper = $this->helper('customer');
		
		if( $helper->isLoggedIn() && $helper->customerHasAddresses() )	{
			
			
			
			$customer = $helper->getCustomer();
			
			// Find the customer address id
			$address = $customer->getDefaultBillingAddress();
			$address_id = $address->getId();
			
			$data = array(
				'address_id' => $address->getId(),
				'firstname' => $address->getFirstname(),
				'lastname' => $address->getLastname(),
				'company' => $address->getCompany(),
				'street' => array($address->getStreet1(),$address->getStreet2()),
				'city' => $address->getCity(),
				'region_id' => $address->getRegionId(),
				'region' => $address->getRegion(),
				'country_id' => $address->getCountry(),
				'telephone' => $address->getTelephone(),
				'fax' => $address->getFax(),
				'use_for_shipping' => 1
			);
			
			$result = $this->getOnepage()->saveBilling($data, $address_id);
			$this->log[] = 'Set billing address to default address from account (ID: ' . $address_id . ')';

		}

	}
	
    public function __construct()
    {
		$this->email = false;
	
		$this->_loadConfig();
		
		if($this->settings['enable_geoip'])	{
			require_once 'Net/GeoIP.php';
		}
		
		if($this->_isLoggedIn())	{
			$helper = Mage::helper('customer');
			$customer = $helper->getCustomer();
			$this->email = $customer->getEmail();
		}

		$this->_checkShippingMethod();
		$this->_checkCountry();
		$this->_checkPaymentMethod();
		
		try	{
			$this->_handlePostData();	
		} catch(Exception $e)	{
			die('handlePostData died with: ' . $e->getMessage());
		}
    }	
	
	public function hasAjaxSaveBillingField($name)
	{
		$fields = explode(',', $this->settings['ajax_save_billing_fields']);
		
		if(in_array($name, $fields))	{
			return true;
		}
		
		return false;
	}
	
	
	public function sameAsBilling()
	{	
		if($_SERVER['REQUEST_METHOD'] == 'POST')	{
			if(!isset($_POST['billing']['use_for_shipping']))	{
				return false;
			}
			else	{
				if($_POST['billing']['use_for_shipping'] != '1')	{
					return false;
				}
			}
			
			return true;
		}
		
		$address = $this->getQuote()->getShippingAddress();
		
		if($this->getQuote()->getShippingAddress()->getSameAsBilling())	{
			return true;
		}
		else	{
			return false;
		}
	}
	
	public function differentShippingAvailable()
	{
		if($this->isVirtual())	{
			return false;
		}
		
		if($this->settings['enable_different_shipping'])	{
			return true;
		}
		
		return false;
	}

    public function isVirtual()
    {
        return $this->getOnepage()->getQuote()->isVirtual();
    }
	
	public function hasFormErrors()
	{
		if($this->hasShippingErrors() || $this->hasBillingErrors() || $this->hasMethodErrors())	{
			return true;
		}
		
		return false;
	}
	
	public function hasMethodErrors()
	{
		if(isset($this->formErrors['shipping_method']) && $this->formErrors['shipping_method'])	{
			return true;
		}
		
		if(isset($this->formErrors['payment_method']) && $this->formErrors['payment_method'])	{
			return true;
		}		
		
		if(isset($this->formErrors['payment_method_error']))	{
			return true;
		}
		
		
		if(isset($this->formErrors['terms_error']))	{
			return true;
		}
		
		return false;
	}
	
	public function hasShippingErrors()
	{
		if(isset($this->formErrors['shipping_errors']))  {
			if(count($this->formErrors['shipping_errors']) == 0) {
				return false;
			}
			return true;
		}
		else    {
			return true;
		}
	}

    public function hasBillingErrors()
    {
        if(count($this->formErrors) > 0)   {
            if(isset($this->formErrors['billing_errors']))  {
                if(count($this->formErrors['billing_errors']) == 0) {

                    return false;
                }
                return true;
            }
            else    {
                return true;
            }
        }
        return false;
    }



	public function _handlePostData()
	{
		$this->formErrors = array(
			'billing_errors' => array(),
			'shipping_errors' => array(),
		);
		
		$post = $this->getRequest()->getPost();
		
		
		
		
		if(!$post) return;
		
		// Save billing information
		if( $this->_isLoggedInWithAddresses() && false )	{
			
			// User is logged in and has addresses
			
		}
		else	{
			
            $checkoutHelper = Mage::helper('onestepcheckout/checkout');
            
			$payment_data = $this->getRequest()->getPost('payment');

			
			$billing_data = $this->getRequest()->getPost('billing', array());
			$shipping_data = $this->getRequest()->getPost('shipping', array());
			
			if($this->differentShippingAvailable())	{
				$this->getQuote()->getShippingAddress()->setCountryId($shipping_data['country_id'])->setCollectShippingRates(true);
			}
			
		
			
			if(isset($billing_data['email']))	{
				$this->email = $billing_data['email'];
			}
			
			
			$checkoutHelper->load_exclude_data($billing_data);
			$checkoutHelper->load_exclude_data($shipping_data);
			
			
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
            $result = $this->getOnepage()->saveBilling($billing_data, $customerAddressId);

            
			if(isset($result['error']))	{
				$this->formErrors['billing_error'] = true;
				$this->formErrors['billing_errors'] = $checkoutHelper->_getAddressError($result, $billing_data);
				$this->log[] = 'Error saving billing details: ' . implode(', ', $this->formErrors['billing_errors']);
			}
			
			// Validate stuff that saveBilling doesn't handle
			if(!$this->_isLoggedIn())	{
                $validator = new Zend_Validate_EmailAddress();
				if(!$billing_data['email'] || $billing_data['email'] == '' || !$validator->isValid($billing_data['email']))	{
                    
                    if(is_array($this->formErrors['billing_errors']))   {
                        $this->formErrors['billing_errors'][] = 'email';
                    }
                    else    {
                        $this->formErrors['billing_errors'] = array('email');
                    }
                    
				    $this->formErrors['billing_error'] = true;
				    
				}
			}
			
			
			if($this->settings['enable_terms'])	{
				if(!isset($post['accept_terms']) || $post['accept_terms'] != '1')	{
					$this->formErrors['terms_error'] = true;
				}	
			}
			
			
			
			if($this->differentShippingAvailable())	{	
				if(!isset($billing_data['use_for_shipping']) || $billing_data['use_for_shipping'] != '1')	{
					$shipping_result = $this->getOnepage()->saveShipping($shipping_data, $customerAddressId);
					
					if(isset($shipping_result['error']))	{
						$this->formErrors['shipping_error'] = true;
						$this->formErrors['shipping_errors'] = $checkoutHelper->_getAddressError($shipping_result, $shipping_data, 'shipping');
					}
				}
			}
		}
		
		// Save shipping method
		$shipping_method = $this->getRequest()->getPost('shipping_method', '');
		if(!$this->isVirtual() )  {
			$result = $this->getOnepage()->saveShippingMethod($shipping_method);
			if(isset($result['error']))    {
				$this->formErrors['shipping_method'] = True;
			}
			else	{
				Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request'=>$this->getRequest(), 'quote'=>$this->getOnepage()->getQuote()));
			}
		}
		
		
		
		
		// Save payment method
		$payment = $this->getRequest()->getPost('payment', array());
		
		
		
		//echo '<pre>' . print_r($_POST,1) . '</pre>';
		//echo '<pre>' . print_r($payment,1) . '</pre>';
		
		try {
			$result = $this->getOnepage()->savePayment($payment);
		}
		catch (Mage_Payment_Exception $e) {
			if ($e->getFields()) {
				$result['fields'] = $e->getFields();
			}
			$result['error'] = $e->getMessage();
		}
		catch (Exception $e) {
			$result['error'] = $e->getMessage();	
		}

		if(isset($result['error'])) {
		
			if($result['error'] == 'Can not retrieve payment method instance')	{
				$this->formErrors['payment_method'] = true;
			}
			else	{
				$this->formErrors['payment_method_error']  = $result['error'];
			}
		}
		

		if(!$this->hasFormErrors())	{
			// Handle newsletter
			$subscribe_newsletter = $this->getRequest()->getPost('subscribe_newsletter');
			
			if($this->settings['enable_newsletter'])	{
				if($subscribe_newsletter && $subscribe_newsletter == '1' )	{
					Mage::getModel('newsletter/subscriber')->subscribe($this->email);
					$this->log[] = 'Subscribed e-mail address to newsletter';
				}
			}
			
			
			
			if( $this->_isLoggedIn() )	{
				// User is logged in
				// Place order as registered customer
				
				$this->_saveOrder();
				$this->log[] = 'Saving order as a logged in customer';
				
				
				
			}
			else	{
						
				if( $this->_isEmailRegistered() )	{
					// Place order as customer with same e-mail address
					$this->log[] = 'Save order on existing account with email address';
					
					//$customer = $this->_getCustomer();
					//$this->getQuote()->setCustomerId($customer->getId());
					
					// Attempt to save the order
					$this->getOnepage()->saveCheckoutMethod('guest');
					$this->_saveOrder();
					
					
				}
				else	{
					if( $this->settings['generate_customer_password'] && false)	{
						// Generate account, place order with the account
						$this->log[] = 'Generate account and save order as REGISTERED';
					}
					else	{
						// Save order as guest
						$this->log[] = 'Save order as GUEST';
						
						
						
						$this->getOnepage()->saveCheckoutMethod('guest');
						$this->_saveOrder();
						
					}
				}
			}
		}
	}
	
	private function _getCustomer()
	{
		$model = Mage::getModel('customer/customer');
		$model->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($this->email);
		
		if($model->getId() == NULL)	{
			return false;
		}
			
		return $model;
	}
	
	private function _isEmailRegistered()
	{
		$model = Mage::getModel('customer/customer');
		$model->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($this->email);
		
		if($model->getId() == NULL)	{
			return false;
		}
		
		return true;	
	}

    public function canCheckout()
    {
		if($this->getQuote()->getItemsSummaryQty() == 0)	{
			return false;
		}
	
        return true;
    }
	
	private function _saveOrder()
	{	
		// Hack to fix weird Magento payment behaviour
		$payment = $this->getRequest()->getPost('payment', false);
		if($payment)	{
			$this->getOnepage()->getQuote()->getPayment()->importData($payment);
		}
		
		try	{
			$this->getOnepage()->saveOrder();
		} catch(Exception $e)	{
			die('Error: ' . $e->getMessage());
		}
		
		$redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
		
		if($redirectUrl)    {
			$redirect = $redirectUrl;
		}
		else    {
			$redirect = $this->getUrl('checkout/onepage/success');
			//$this->_redirect('checkout/onepage/success', array('_secure'=>true));    
		}
		
		Header('Location: ' . $redirect);
		exit();		
	}

    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }
    
    public function isUseBillingAddressForShipping()
    {
        if (($this->getQuote()->getIsVirtual())
            || !$this->getQuote()->getShippingAddress()->getSameAsBilling()) {
            return false;
        }
        return true;
    }

    public function getCountries()
    {
        return Mage::getResourceModel('directory/country_collection')->loadByStore();
    }

    public function getMethod()
    {
        return $this->getQuote()->getCheckoutMethod();
    }

    function getAddress() {
        if (!$this->isCustomerLoggedIn()) {
            return $this->getQuote()->getBillingAddress();
        } else {
            return Mage::getModel('sales/quote_address');
        }
    }

    public function getFirstname()
    {
        $firstname = $this->getAddress()->getFirstname();
        if (empty($firstname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getFirstname();
        }
        return $firstname;
    }

    public function getLastname()
    {
        $lastname = $this->getAddress()->getLastname();
        if (empty($lastname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getLastname();
        }
        return $lastname;
    }

    public function canShip()
    {
        return !$this->getQuote()->isVirtual();
    }
 
 
    public function getCountryHtmlSelect($type)
    {
		if($type == 'billing')	{
			$address = $this->getQuote()->getBillingAddress();
		}
		else	{
			$address = $this->getQuote()->getShippingAddress();
		}
	
        $countryId = $address->getCountryId();
        if (is_null($countryId)) {
            $countryId = Mage::getStoreConfig('general/country/default');
        }
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName($type.'[country_id]')
            ->setId($type.':country_id')
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('validate-select')
            ->setValue($countryId)
            ->setOptions($this->getCountryOptions());
        if ($type === 'shipping') {
            $select->setExtraParams('onchange="shipping.setSameAsBilling(false);"');
        }

        return $select->getHtml();
    }
 
}

