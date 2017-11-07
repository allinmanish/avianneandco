<?php
/**
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2012 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Adminhtml_Webforms_Edit_Tab_Settings
	extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareLayout(){
		
		parent::_prepareLayout();
	}	
	
	protected function _prepareForm()
	{
		$model = Mage::getModel('webforms/webforms');
		$form = new Varien_Data_Form();
		$this->setForm($form);
	
		$fieldset = $form->addFieldset('webforms_general',array(
			'legend' => Mage::helper('webforms')->__('General Settings')
		));
		
		$fieldset->addField('registered_only', 'select', array(
			'label'     => Mage::helper('webforms')->__('Registered customers only'),
			'title'     => Mage::helper('webforms')->__('Registered customers only'),
			'name'      => 'registered_only',
			'required'  => false,
			'values'   => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));

		$fieldset->addField('survey', 'select', array(
			'label'     => Mage::helper('webforms')->__('Survey mode'),
			'title'     => Mage::helper('webforms')->__('Survey mode'),
			'name'      => 'survey',
			'required'  => false,
			'note' => Mage::helper('webforms')->__('Survey mode allows filling up the form only one time'),
			'values'   => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));
		
		$fieldset->addField('approve', 'select', array(
			'label'     => Mage::helper('webforms')->__('Enable approval'),
			'title'     => Mage::helper('webforms')->__('Enable approval'),
			'name'      => 'approve',
			'required'  => false,
			'note' => Mage::helper('webforms')->__('Enable approval of results'),
			'values'   => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));

		$fieldset->addField('redirect_url', 'text', array(
			'label'     => Mage::helper('webforms')->__('Redirect URL'),
			'title'     => Mage::helper('webforms')->__('Redirect URL'),
			'name'      => 'redirect_url',
			'note' => Mage::helper('webforms')->__('Redirect to specified url after successful submission'),
		));

		$fieldset = $form->addFieldset('webforms_email',array(
			'legend' => Mage::helper('webforms')->__('E-mail Settings')
		));

		$fieldset->addField('send_email', 'select', array(
			'label'     => Mage::helper('webforms')->__('Send results by e-mail'),
			'title'     => Mage::helper('webforms')->__('Send results by e-mail'),
			'name'      => 'send_email',
			'required'  => false,
			'values'   => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));
		
		$fieldset->addField('duplicate_email', 'select', array(
			'label'     => Mage::helper('webforms')->__('Duplicate results by e-mail to customer'),
			'title'     => Mage::helper('webforms')->__('Duplicate results by e-mail to customer'),
			'name'      => 'duplicate_email',
			'required'  => false,
			'values'   => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));
		
		$fieldset->addField('email','text',array(
			'label' => Mage::helper('webforms')->__('Notification e-mail address'),
			'note' => Mage::helper('webforms')->__('If empty default notification e-mail address will be used. You can set multiple addresses comma-separated'),
			'name' => 'email'
		));
		
		$fieldset->addField('add_header', 'select', array(
			'label'     => Mage::helper('webforms')->__('Add header to the message'),
			'title'     => Mage::helper('webforms')->__('Add header to the message'),
			'name'      => 'add_header',
			'note' => Mage::helper('webforms')->__('Add header with Store Group, IP and other information to the message'),
			'values'   => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));

		$fieldset->addField('email_template_id', 'select', array(
			'label'     => Mage::helper('webforms')->__('E-mail template'),
			'title'     => Mage::helper('webforms')->__('E-mail template'),
			'name'      => 'email_template_id',
			'required'  => false,
			'note' => Mage::helper('webforms')->__('E-mail template for notification letters'),
			'values'   => Mage::getModel('webforms/webforms')->getTemplatesOptions(),
		));
		
		$fieldset->addField('email_customer_template_id', 'select', array(
			'label'     => Mage::helper('webforms')->__('Customer e-mail template'),
			'title'     => Mage::helper('webforms')->__('Customer e-mail template'),
			'name'      => 'email_customer_template_id',
			'required'  => false,
			'note' => Mage::helper('webforms')->__('E-mail template for customers notification letters'),
			'values'   => Mage::getModel('webforms/webforms')->getTemplatesOptions('customer'),
		));
		
		
		$fieldset = $form->addFieldset('webforms_captcha',array(
			'legend' => Mage::helper('webforms')->__('reCaptcha Settings')
		));

		$fieldset->addField('captcha_mode', 'select', array(
			'label'     => Mage::helper('webforms')->__('Captcha mode'),
			'title'     => Mage::helper('webforms')->__('Captcha mode'),
			'name'      => 'captcha_mode',
			'required'  => false,
			'note' => Mage::helper('webforms')->__('Default value is set in Forms Settings'),
			'values'   => Mage::getModel('webforms/captcha_mode')->toOptionArray(true),
		));
		
		$fieldset = $form->addFieldset('webforms_files',array(
			'legend' => Mage::helper('webforms')->__('Files Settings')
		));
		
		$fieldset->addField('files_upload_limit', 'text', array(
			'label'     => Mage::helper('webforms')->__('Files upload limit'),
			'title'     => Mage::helper('webforms')->__('Files upload limit'),
			'name'      => 'files_upload_limit',
			'class' => 'validate-number',
			'note' => Mage::helper('webforms')->__('Maximum upload file size in kB'),
		));
		
		$fieldset = $form->addFieldset('webforms_images',array(
			'legend' => Mage::helper('webforms')->__('Images Settings')
		));
		
		$fieldset->addField('images_upload_limit', 'text', array(
			'label'     => Mage::helper('webforms')->__('Images upload limit'),
			'title'     => Mage::helper('webforms')->__('Images upload limit'),
			'class' => 'validate-number',
			'name'      => 'images_upload_limit',
			'note' => Mage::helper('webforms')->__('Maximum upload image size in kB'),
		));
		
		
		Mage::dispatchEvent('webforms_adminhtml_webforms_edit_tab_settings_prepare_form', array('form' => $form, 'fieldset' => $fieldset));

		if (!Mage::registry('webforms_data')->getId()) {
			Mage::registry('webforms_data')->setData('send_email',1);
		}

		if(Mage::registry('webforms_data')->getData('files_upload_limit') == 0){
			Mage::registry('webforms_data')->setData('files_upload_limit','');
		}
		
		if(Mage::registry('webforms_data')->getData('images_upload_limit') == 0){
			Mage::registry('webforms_data')->setData('images_upload_limit','');
		}
		
		if(Mage::getSingleton('adminhtml/session')->getWebFormsData())
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getWebFormsData());
			Mage::getSingleton('adminhtml/session')->setWebFormsData(null);
		} elseif(Mage::registry('webforms_data')){
			$form->setValues(Mage::registry('webforms_data')->getData());
		}
		
		
		
		return parent::_prepareForm();
	}
}  
?>
