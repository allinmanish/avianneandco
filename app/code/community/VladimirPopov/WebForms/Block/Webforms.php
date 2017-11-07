<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author		Vladimir Popov
 * @copyright  	Copyright (c) 2012 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Webforms
	extends Mage_Core_Block_Template
{
	protected $_webform;

	public function getWebform() { return $this->_webform; }

	public function setWebform($webform)
	{
		$this->_webform = $webform;
		return $this;
	}

	protected function _toHtml()
	{
		if ((float)substr(Mage::getVersion(), 0, 3) <= 1.3 && Mage::helper('webforms')->getMageEdition() != 'EE')
		{
			if ($this->getTemplate() == 'webforms/default.phtml' && $this->getData('nolegacy') != '0') { $this->setTemplate('webforms/legacy.phtml'); }
		}
		
		if (!Mage::registry('webforms_preview'))
			$this->initForm();
		
		return parent::_toHtml();
	}

	protected function initForm()
	{

		$show_success = false;
		$data = $this->getFormData();

		//get form data
		$webform = Mage::getModel('webforms/webforms')->load($data['webform_id']);
		$this->setWebform($webform);

		//proccess texts
		if ((float)substr(Mage::getVersion(), 0, 3) > 1.3 && Mage::helper('webforms')->getMageEdition() != 'EE')
		{
			$webform->setDescription(Mage::helper('cms')->getPageTemplateProcessor()->filter($webform->getDescription()));
			$webform->setSuccessText(Mage::helper('cms')->getPageTemplateProcessor()->filter($webform->getSuccessText()));
		}

		if (!Mage::registry('webform'))
			Mage::register('webform', $webform);

		if (intval($this->getData('results')) == 1)
			$this->getResults();

		if ($webform->getSurvey())
		{
			$collection = Mage::getModel('webforms/results')->getCollection();

			if (Mage::helper('customer')->isLoggedIn())
				$collection->addFilter('webform_id', $data['webform_id'])->addFilter('customer_id', Mage::getSingleton('customer/session')->getCustomerId());
			else
			{
				$session_validator = Mage::getSingleton('customer/session')->getData('_session_validator_data');
				$collection->addFilter('customer_ip', ip2long($session_validator['remote_addr']));
			}
			$count = $collection->count();

			if ($count > 0) { $show_success = true; }
		}

		if (Mage::getSingleton('core/session')->getWebformsSuccess() == $data['webform_id'] || $show_success)
		{
			Mage::register('show_success', true);
			Mage::getSingleton('core/session')->setWebformsSuccess();
		}

		if ($webform->getRegisteredOnly() && !Mage::helper('customer')->isLoggedIn() && !$this->getData('results'))
		{
			Mage::getSingleton('customer/session')->setBeforeAuthUrl($this->getRequest()->getRequestUri());
			$login_url = Mage::helper('customer')->getLoginUrl();
			$status = 301;

			if (Mage::getStoreConfig('webforms/general/login_redirect'))
			{
				$login_url = $this->getUrl(Mage::getStoreConfig('webforms/general/login_redirect'));

				if (strstr(Mage::getStoreConfig('webforms/general/login_redirect'), '://'))
					$login_url = Mage::getStoreConfig('webforms/general/login_redirect');
			}
			Mage::app()->getFrontController()->getResponse()->setRedirect($login_url, $status);
		}

		Mage::register('fields_to_fieldsets', $webform->getFieldsToFieldsets());

		//use captcha
		Mage::register('use_captcha', $webform->useCaptcha());

		//proccess the result
		if ($this->getRequest()->getParam('submitWebform_' . $data['webform_id']))
		{
			//validate captcha
			if (Mage::registry('use_captcha'))
			{
				if ($this->getRequest()->getParam('recaptcha_response_field'))
				{
					$verify = Mage::helper('webforms')->getCaptcha()->verify($this->getRequest()->getParam('recaptcha_challenge_field'), $this->getRequest()->getParam('recaptcha_response_field'));

					if ($verify->isValid()) { $success = $webform->savePostResult(); }
					else
					{
						Mage::getSingleton('core/session')->addError($this->__('Verification code was not correct. Please try again.'));
						Mage::register('captcha_invalid', true);
					}
				}
				else
				{
					Mage::getSingleton('core/session')->addError($this->__('Verification code was not correct. Please try again.'));
					Mage::register('captcha_invalid', true);
				}
			}
			else { $success = $webform->savePostResult(); }

			if ($success) { Mage::getSingleton('core/session')->setWebformsSuccess($data['webform_id']); }
			//redirect after successful submission
			$url = Mage::helper('core/url')->getCurrentUrl();

			if (!$success && $this->getTemplate() != 'webforms/legacy.phtml')
				Mage::app()->getFrontController()->getResponse()->setRedirect($url);

			if ($webform->getRedirectUrl())
			{
				if (strstr($webform->getRedirectUrl(), '://'))
					$url = $webform->getRedirectUrl();
				else
					$url = $this->getUrl($webform->getRedirectUrl());
			}
			Mage::register('redirect_url', $url);

			if ($success)
				Mage::app()->getFrontController()->getResponse()->setRedirect($url);
		}
		
		return $this;
	}

	// check that form is available for direct access
	public function isDirectAvailable()
	{
		$available = new Varien_Object();
		$available->setData('status', true);

		Mage::dispatchEvent('webforms_direct_available', array
		(
			'available' => $available,
			'form_data' => $this->getFormData()
		));

		return $available->getData('status');
	}

	public function getNotAvailableMessage()
	{
		$message = $this->__('Web-form is not active.');

		if (Mage::registry('webform')->getIsActive() && !$this->isDirectAvailable())
			$message = $this->__('Web-form is not available for direct access.');
		return $message;
	}

	public function getFormData()
	{
		$data = $this->getRequest()->getParams();

		if (isset($data['id'])) { $data['webform_id'] = $data['id']; }

		if ($this->getData('webform_id')) { $data['webform_id'] = $this->getData('webform_id'); }

		if (empty($data['webform_id'])) { $data['webform_id'] = Mage::getStoreConfig('webforms/contacts/webform'); }
		return $data;
	}

	protected function _prepareLayout()
	{
		if ((float)substr(Mage::getVersion(), 0, 3) <= 1.4)
			error_reporting(E_ERROR);

		Mage::helper('webforms')->addAssets($this->getLayout());

		parent::_prepareLayout();

		if (Mage::registry('webforms_preview')){
			
			$this->initForm();
			
			if ($this->getLayout()->getBlock('head'))
				$this->getLayout()->getBlock('head')->setTitle($this->getWebform()->getName());
		}
	}

	public function getCaptcha() { return Mage::helper('webforms')->getCaptcha(); }

	public function getEnctype()
	{
		if (Mage::registry('fields_to_fieldsets'))
		{
			foreach (Mage::registry('fields_to_fieldsets') as $fieldset)
			{
				foreach ($fieldset['fields'] as $field)
				{
					if ($field->getType() == 'file' || $field->getType() == 'image') { return 'multipart/form-data'; }
				}
			}
		}
		return 'application/x-www-form-urlencoded';
	}

	public function getResults()
	{
		$data = $this->getData();

		$webform = Mage::registry('webform');

		//get results
		$page_size = $data["page_size"];
		$current_page = (int)$this->getRequest()->getParam('p');

		if (!$current_page)
			$current_page = 1;
		$from = $current_page * $page_size;
		$results = Mage::getModel('webforms/results')->getCollection()->addFilter('webform_id', $webform->getId())->addFilter('approved', 1)->setPageSize($page_size)->setCurPage($current_page);
		$results->getSelect()->order('created_time desc');

		$last_page = $results->getLastPageNumber();

		$page_url = $this->getUrl(Mage::getSingleton('cms/page')->getData('identifier'));

		echo get_class($page_url);

		if ($current_page < $last_page) { $prev_url = $page_url . "?p=" . ($current_page + 1); }

		if ($current_page > 1) { $next_url = $page_url . "?p=" . ($current_page - 1); }

		Mage::register('prev_url', $prev_url);
		Mage::register('next_url', $next_url);
		Mage::register('current_page', $current_page);
		Mage::register('results', $results);
	}

	protected function _construct()
	{
		Mage::unregister('webform');
		Mage::unregister('fields_to_fieldsets');
		Mage::unregister('prev_url');
		Mage::unregister('next_url');
		Mage::unregister('current_page');
		Mage::unregister('results');
		Mage::unregister('redirect_url');
		Mage::unregister('use_captcha');
		Mage::unregister('captcha_invalid');
		parent::_construct();
	}

	public function isAjax() { return Mage::getStoreConfig('webforms/general/ajax'); }

	public function getFormAction()
	{
		if ($this->isAjax())
		{
			$secure = false;

			if (isset($_SERVER['HTTPS'])) { $secure = $_SERVER['HTTPS']; }
			return $this->getUrl('webforms/index/iframe', array ('_secure' => $secure));
		}
		return Mage::helper('core/url')->getCurrentUrl();
	}
}
?>