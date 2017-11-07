<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Webforms extends Mage_Core_Model_Abstract{

	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
	protected $_fields_to_fieldsets = array();
	protected $_hidden = array();
	
	public function _getFieldsToFieldsets(){
		return $this->_fields_to_fieldsets;
	}
	
	public function _setFieldsToFieldsets($fields_to_fieldsets){
		$this->_fields_to_fieldsets = $fields_to_fieldsets;
		return $this;
	}
	
	public function _getHidden(){
		return $this->_hidden;
	}
	
	public function _setHidden($hidden){
		$this->_hidden = $hidden;
		return $this;
	}
	
	public function _construct(){
		parent::_construct();
		$this->_init('webforms/webforms');
	}
	
	public function getAvailableStatuses(){
		$statuses = new Varien_Object(array(
			self::STATUS_ENABLED => Mage::helper('webforms')->__('Enabled'),
			self::STATUS_DISABLED => Mage::helper('webforms')->__('Disabled'),
		));

		Mage::dispatchEvent('webforms_statuses', array('statuses' => $statuses));

		return $statuses->getData();
		
	}
	
	public function toOptionArray(){
		$collection = $this->getCollection()->addFilter('is_active',self::STATUS_ENABLED)->addOrder('name','asc');
		$option_array = array();
		foreach($collection as $webform)
			$option_array[]= array('value'=>$webform->getId(), 'label' => $webform->getName());
		return $option_array;
	}
	
	public function getFieldsetsOptionsArray(){
		$collection = Mage::getModel('webforms/fieldsets')->getCollection()->addFilter('webform_id',$this->getId());
		$collection->getSelect()->order('position asc');
		$options = array(0 =>'...');
		foreach($collection as $o){
			$options[$o->getId()]= $o->getName();
		}
		return $options;
	}
	
	public function getTemplatesOptions($type = 'admin'){
		$template_code = 'webforms_results';
		if($type == 'customer'){
			$template_code = 'webforms_results_customer';
		}
		$options = array(0 => Mage::helper('webforms')->__('Default'));
		if((float)substr(Mage::getVersion(),0,3)>1.3)
			$templates = Mage::getModel('core/email_template')->getCollection()->addFilter('orig_template_code',$template_code);
		else
			$templates = Mage::getResourceSingleton('core/email_template_collection');
		foreach($templates as $template){
			$options[$template->getTemplateId()] = '['.$template->getTemplateId().']'.$template->getTemplateCode();
		}
		return $options;
	}
	
	public function getEmailSettings(){
		$settings["email_enable"] = $this->getSendEmail();
		$settings["email"] = Mage::getStoreConfig('webforms/email/email');
		if($this->getEmail())
			$settings["email"] = $this->getEmail();
		return $settings;
	}
	
	public function getFieldsToFieldsets($all = false){
		//get form fieldsets
		$fieldsets = Mage::getModel('webforms/fieldsets')->getCollection()
			->addFilter('webform_id',$this->getId());
		
		if(!$all)
			$fieldsets->addFilter('is_active', self::STATUS_ENABLED);
			
		$fieldsets->getSelect()->order('position asc');
		
		//get form fields
		$fields = Mage::getModel('webforms/fields')->getCollection()
			->addFilter('webform_id',$this->getId());
		
		if(!$all){
			$fields->addFilter('is_active', self::STATUS_ENABLED);
		}
			
		$fields->getSelect()->order('position asc');
		
		//fields to fieldsets
		//make zero fieldset
		$fields_to_fieldsets = array();
		$hidden = array();
		foreach($fields as $field){
			if($field->getFieldsetId() == 0){
				if($all || $field->getType() != 'hidden'){
					$fields_to_fieldsets[0]['fields'][] = $field;
				}elseif($field->getType() == 'hidden'){
					$hidden[]= $field;
				}
			}
		}
		
		foreach($fieldsets as $fieldset){
			foreach($fields as $field){
				if($field->getFieldsetId() == $fieldset->getId()){
					if($all || $field->getType() != 'hidden'){
						$fields_to_fieldsets[$fieldset->getId()]['fields'][] = $field;
					}elseif($field->getType() == 'hidden'){
						$hidden[]= $field;
					}
				}
			}
			if(!empty($fields_to_fieldsets[$fieldset->getId()]['fields'])){
				$fields_to_fieldsets[$fieldset->getId()]['name'] = $fieldset->getName();
				$fields_to_fieldsets[$fieldset->getId()]['result_display'] = $fieldset->getResultDisplay();
			}
		}
		
		$this->_setFieldsToFieldsets($fields_to_fieldsets);
		$this->_setHidden($hidden);
		
		return $fields_to_fieldsets;
		
	}
	
	public function useCaptcha(){
		$useCaptcha = true;
		if($this->getCaptchaMode() != 'default'){
			$captcha_mode = $this->getCaptchaMode();
		} else {
			$captcha_mode = Mage::getStoreConfig('webforms/captcha/mode');
		}
		if($captcha_mode == "off" || !Mage::helper('webforms')->captchaAvailable())
			$useCaptcha = false;
		if(Mage::helper('customer')->isLoggedIn() && $captcha_mode == "auto")
			$useCaptcha = false;
		if($this->getData('disable_captcha'))
			$useCaptcha = false;
		return $useCaptcha;
	}
	
	public function duplicate(){
		// duplicate form
		$form = Mage::getModel('webforms/webforms')
			->setData($this->getData())
			->setId(null)
			->setName($this->getName().' '.Mage::helper('webforms')->__('(new copy)'))
			->setIsActive(false)
			->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())
			->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())
			->save();
		
		// duplicate fieldsets and fields
		$fields_to_fieldsets = $this->getFieldsToFieldsets(true);
		foreach($fields_to_fieldsets as $fieldset_id => $fieldset){
			if($fieldset_id){
				$fs = Mage::getModel('webforms/fieldsets')->load($fieldset_id);
				$new_fieldset = Mage::getModel('webforms/fieldsets')
					->setData($fs->getData())
					->setId(null)
					->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())
					->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())
					->setWebformId($form->getId())
					->save();
				$new_fieldset_id = $new_fieldset->getId();
			} else {
				$new_fieldset_id = 0;
			}
			foreach($fieldset['fields'] as $field){
				$new_field = Mage::getModel('webforms/fields')
					->setData($field->getData())
					->setId(null)
					->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())
					->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())
					->setWebformId($form->getId())
					->setFieldsetId($new_fieldset_id)
					->save();
			}
		}
		
		return $form;
	}
	
	protected function getAjaxFiles(){
		$files = array();
		
		$postData = Mage::app()->getRequest()->getPost();
		
		foreach($postData as $field_name => $value){
			if(strstr($field_name,'file_'))	{
				$name  = explode('/',$value);
				$tmp_name = Mage::getBaseDir('base').DS.'js'.DS.'webforms'.DS.'upload'.DS.'files'.DS.$name[0].DS.$name[1];
				$size = filesize($tmp_name);
				$files[$field_name]= array(
					'name' => $name[1],
					'tmp_name' => $tmp_name,
					'size' => $size
				);
			}
		}
		return $files;
	}
	
	protected function getUploadedFiles(){			
		return array_merge($_FILES,$this->getAjaxFiles());
	}
	
	public function validatePostResult(){
		
		if(Mage::registry('webforms_errors_flag_'.$this->getId())) return Mage::registry('webforms_errors_'.$this->getId());
		
		$errors = array();
				
		// check captcha
		if($this->useCaptcha()){
			if(Mage::app()->getRequest()->getPost('recaptcha_response_field')){
				$verify = Mage::helper('webforms')->getCaptcha()->verify(Mage::app()->getRequest()->getPost('recaptcha_challenge_field'),Mage::app()->getRequest()->getPost('recaptcha_response_field'));
				if(!$verify->isValid()){
					$errors[]= Mage::helper('webforms')->__('Verification code was not correct. Please try again.');
				}
			} else {
				$errors[]= Mage::helper('webforms')->__('Verification code was not correct. Please try again.');
			}
		}
		
		// check files
		$files = $this->getUploadedFiles();
		foreach($files as $field_name=>$file){
			if(isset($file['name']) && file_exists($file['tmp_name'])){
				$field_id = str_replace('file_','',$field_name);
				$postData['field'][$field_id] = Varien_File_Uploader::getCorrectFileName($file['name']);
				$field = Mage::getModel('webforms/fields')->load($field_id);
				$filesize = round($file['size'] / 1024);
				$images_upload_limit = Mage::getStoreConfig('webforms/images/upload_limit');
				if($this->getImagesUploadLimit() > 0){
					$images_upload_limit = $this->getImagesUploadLimit();
				}
				$files_upload_limit = Mage::getStoreConfig('webforms/files/upload_limit');
				if($this->getFilesUploadLimit() > 0){
					$files_upload_limit = $this->getFilesUploadLimit();
				}
				if($field->getType() == 'image'){
					// check file size
					if( $filesize > $images_upload_limit && $images_upload_limit > 0){
						$errors[] = Mage::helper('webforms')->__('Uploaded image %s (%s kB) exceeds allowed limit: %s kB',$file['name'],$filesize, $images_upload_limit );
					}
					
					// check that file is valid image
					if(!@getimagesize($file['tmp_name'])){
						$errors[]=Mage::helper('webforms')->__('Unsupported image compression: %s',$file['name']);
					}
				} else {
					// check file size
					if($filesize > $files_upload_limit && $files_upload_limit > 0){
						$errors[]=Mage::helper('webforms')->__('Uploaded file %s (%s kB) exceeds allowed limit: %s kB',$file['name'],$filesize,$files_upload_limit);
					}
					
				}
				$allowed_extensions = $field->getAllowedExtensions();
				// check for allowed extensions
				if(count($allowed_extensions)){
					preg_match("/\.([^\.]+)$/", $file['name'], $matches);
					$file_ext = strtolower($matches[1]);
					// check file extension
					if(!in_array($file_ext, $allowed_extensions)){
						$errors[]=Mage::helper('webforms')->__('Uploaded file %s has none of allowed extensions: %s',$file['name'],implode(', ',$allowed_extensions));
					}
				}
				// check for valid filename
				if(!preg_match("/^[a-zA-Z0-9_\s-\.]+$/",$file['name'])){
					$errors[] = Mage::helper('webforms')->__('Uploaded file %s has non-latin characters in the name',$file['name']);
				}				
			}
		}
		
		$validate = new Varien_Object(array('errors' => $errors));
		
		Mage::dispatchEvent('webforms_validate_post_result',array('webform' => $this, 'validate' => $validate));
				
		Mage::register('webforms_errors_flag_'.$this->getId(),true);
		Mage::register('webforms_errors_'.$this->getId(),$validate->getData('errors'));
		
		return $validate->getData('errors');	
	}
	
	
	public function savePostResult($config = array()){
		try{
			$postData = Mage::app()->getRequest()->getPost();
			if(!empty($config['prefix'])){
				$postData = Mage::app()->getRequest()->getPost($config['prefix']);
			}
			$result = Mage::getModel('webforms/results');
			if(!empty($postData['result_id'])){
				$result->load($postData['result_id'])->addFieldArray();
			}
			
			$errors = $this->validatePostResult();
			if(count($errors)){
				foreach($errors as $error){
					Mage::getSingleton('core/session')->addError($error);
				}
				return;
			}
				
			$session_validator = Mage::getSingleton('customer/session')->getData('_session_validator_data');
			$iplong = ip2long($session_validator['remote_addr']);
			$iplong = ip2long(Mage::helper('webforms')->getRealIp());
			
			$files = $this->getUploadedFiles();
			foreach($files as $field_name=>$file){
				$field_id = str_replace('file_','',$field_name);
				if($file['name']){
					$postData['field'][$field_id] = Varien_File_Uploader::getCorrectFileName($file['name']);
				}
				if(!empty($postData['delete_file_'.$field_id])){
					if($result->getData('field_'.$field_id)){
						//delete the file
						@unlink($result->getFileFullPath($field_id,$result->getData('field_'.$field_id)));
					}
					$postData['field'][$field_id] = '';
				}
			}

			$result->setData('field',$postData['field'])
				->setWebformId($this->getId())
				->setStoreId(Mage::app()->getStore()->getId())
				->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
				->setCustomerIp($iplong)
				->setStatus('Pending Deposit')
				->save();
				
			// upload files from $_FILE array
			foreach($_FILES as $field_name=>$file){
				$field_id = str_replace('file_','',$field_name);
				if(isset($file['name']) && file_exists($file['tmp_name'])){
					try {
						$uploader = new Varien_File_Uploader($field_name);
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						$path = $result->getFilePath($field_id);
						$uploader->save($path, $file['name']);
					}
					catch(Exception $e){
						
					}
				}
			}
			// upload Ajax files
			$ajax_files = $this->getAjaxFiles();
			foreach($ajax_files as $field_name=>$file){
				$field_id = str_replace('file_','',$field_name);
				if(isset($file['name']) && file_exists($file['tmp_name'])){
					$path = $result->getFilePath($field_id);
					@mkdir($path,0777,true);
					rename($file['tmp_name'],$path.Varien_File_Uploader::getCorrectFileName($file['name']));
				}
			}
				
			Mage::dispatchEvent('webforms_result_submit',array('result'=>$result,'webform'=>Mage::registry('webform')));
			
// 			$emailSettings = $this->getEmailSettings();
			
// 			if($emailSettings['email_enable']){
				
// 				$result = Mage::getModel('webforms/results')->load($result->getId());
// 				$result->sendEmail();
// 				if($this->getDuplicateEmail()){
// 					$result->sendEmail('customer');
// 				}
// 			}
			
// 			// email contact
// 			$fields = Mage::getModel('webforms/fields')->getCollection()
// 				->addFilter('webform_id',$result->getWebformId())
// 				->addFilter('type','select/contact');
// 			foreach($fields as $field){
// 				foreach($result->getData() as $key=>$value){
// 					if($key == 'field_'.$field->getId() && $value){
// 						$result->sendEmail('contact',$field->getContactArray($value));
// 					}
// 				}
// 			}
			
			return $result->getId();
		} catch (Exception $e){
			Mage::getSingleton('core/session')->addError($e->getMessage());
			return false;
		}		
	}
}
?>
