<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Results extends Mage_Core_Model_Abstract{

	public function _construct(){
		parent::_construct();
		$this->_init('webforms/results');
	}
	
	public function addFieldArray(){
		$data = $this->getData();
		$field_array = array();
		foreach($data as $key => $value){
			if(strstr($key,'field_')){
				$field_array[str_replace('field_','',$key)] = $value;
			}
		}
		$this->setData('field',$field_array);
		return $this;
	}
	
	public function getCustomer(){
		if(!$this->getCustomerId()) return;
		$customer = Mage::getModel('customer/customer')->load($this->getCustomerId());
		return $customer;
	}
	
	public function sendEmail($recipient='admin',$contact = false){
		$webform = Mage::getModel('webforms/webforms')->load($this->getWebformId());
		if(!Mage::registry('webform'))
			Mage::register('webform',$webform);
		
		$emailSettings = $webform->getEmailSettings();
		
		// for admin
		$sender = Array(
			'email' => $this->getReplyTo($recipient),
		);
		if($this->getCustomer())
			$sender['name'] = $this->getCustomer()->getName();
			
		if(!$this->getCustomerId() && $recipient != 'customer'){
			$sender['name'] = $sender['email'];
		}
			
		// for customer
		if($recipient == 'customer'){
			$sender['name'] = Mage::app()->getStore($this->getStoreId())->getFrontendName();
			$contact_array = $this->getContactArray();
			
			// send letter from selected contact
			if($contact_array){
				$sender = $contact_array;
			}
		}
		
		$subject = $this->getEmailSubject($recipient);
		
		$email = $emailSettings['email'];
		
		//for customer
		if($recipient == 'customer'){
			$email = $this->getCustomerEmail();
		}
		
		$name = Mage::app()->getStore($this->getStoreId())->getFrontendName();
		
		if($recipient == 'contact'){
			if(empty($contact['email'])) return;
			$email = $contact['email'];
			$name = $contact['name'];
		}

		$vars = Array(
			'webform_subject' => $subject,
			'webform_name'=>$webform->getName(),
			'webform_result' =>$this->toHtml($recipient),
			'result' => $this->getTemplateResultVar(),
		);
			
		$vars['noreply'] = Mage::helper('webforms')->__('Please, don`t reply to this e-mail!');

		$storeId = Mage::app()->getStore($this->getStoreId())->getId(); 
		$templateId = 'webforms_results';
		if($webform->getEmailTemplateId()){
			$templateId = $webform->getEmailTemplateId();
		}
		if($recipient == 'customer'){
			$templateId = 'webforms_results_customer';
			if($webform->getEmailCustomerTemplateId()){
				$templateId = $webform->getEmailCustomerTemplateId();
			}
		}
		if(strstr($email,',') && $recipient == 'admin'){
			$email_array = explode(',',$email);
			foreach($email_array as $email){
				$success = Mage::getModel('core/email_template')
					->setTemplateSubject($subject)
					->sendTransactional($templateId, $sender, trim($email), $name, $vars, $storeId);
			}
		} else {
			$success = Mage::getModel('core/email_template')
				->setTemplateSubject($subject)
				->sendTransactional($templateId, $sender, $email, $name, $vars, $storeId);
		}
		return $success;
	}
	
	public function getEmailSubject($recipient='admin'){
		$webform = Mage::getModel('webforms/webforms')->load($this->getWebformId());
		$webform_name = $webform->getName();
		$store_name = Mage::app()->getStore($this->getStoreId())->getFrontendName();
		
		//get subject for customer
		if($recipient == 'customer'){
			return Mage::helper('webforms')->__("You have submitted '%s' form on %s website",$webform_name,$store_name);
		}
		
		//get default subject for admin
		$subject = Mage::helper('webforms')->__("Web-form '%s' submitted",$webform_name);
		
		//iterate through fields and build subject
		$subject_array = array();
		$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id',$this->getWebformId())->addFilter('email_subject',1);
		foreach($fields as $field){
			foreach($this->getData() as $key=>$value){
				if($key == 'field_'.$field->getId() && $value){
					$subject_array[]= $value;
				}
			}
		}
		if(count($subject_array)>0){
			$subject = implode(" / ",$subject_array);
		}
		return $subject;
	}
	
	public function getCustomerName(){
		if(Mage::helper('customer')->isLoggedIn()){
			return Mage::helper('customer')->getCustomerName();
		}

		$customer_name = array();
		$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id',$this->getWebformId());
		foreach($fields as $field){
			foreach($this->getData() as $key=>$value){
				$value = trim($value);
				if($key == 'field_'.$field->getId() && $value){
					if(
						$field->getCode() == 'name' || 
						$field->getCode() == 'firstname' ||
						$field->getCode() == 'lastname' ||
						$field->getCode() == 'middlename'
					) $customer_name[] = $value;
				}
			}
		}
		if(count($customer_name) == 0) return Mage::helper('core')->__('Guest');
		return implode(' ',$customer_name);
		
	}
	
	public function getContactArray(){
		
		$fields = Mage::getModel('webforms/fields')->getCollection()
			->addFilter('webform_id',$this->getWebformId())
			->addFilter('type','select/contact');
		
		foreach($fields as $field){
			foreach($this->getData() as $key=>$value){
				if($key == 'field_'.$field->getId() && $value){
					return $field->getContactArray($value);
				}
			}
		}
		
		return false;
	}
	
	public function getTemplateResultVar(){
		$result = new Varien_Object();
		$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id',$this->getWebformId());
		foreach($fields as $field){
			foreach($this->getData() as $key=>$value){
				$value = trim($value);
				if($key == 'field_'.$field->getId() && $value){
					switch($field->getType()){
						case 'date': case 'datetime':
							$data_value = $field->formatDate($value);
							break;
						case 'image':
							$data_value = '<a style="text-decoration:none" href="'.$this->getDownloadLink($field->getId(),$value).'"><img src="'.$this->getThumbnail($field->getId(),$value,200).'"/></a>';
							break;
						case 'file':
							$data_value = '<a href="'.$this->getDownloadLink($field->getId(),$value).'">'.$value.'</a>';
							break;
						case 'stars':
							$data_value = $value.' / '.$field->getStarsCount();
							break;
						default:
							$data_value = nl2br($value);
							break;
					}
					$data = new Varien_Object( array(
						'value' => $data_value,
						'name' => $field->getName(),
						'result_label' => $field->getResultLabel(),
					) );
					$result->setData($field->getId(),$data);
					if($field->getCode()){
						$result->setData($field->getCode(),$data);
					}
				}
			}
		}
		return $result;
	}
	
	public function getReplyTo($recipient='admin'){
		$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id',$this->getWebformId())->addFilter('type','email');
		
		$reply_to = false;
		
		foreach($this->getData() as $key=>$value){
			if($key == 'field_'.$fields->getFirstItem()->getId()) {
				$reply_to = $value;
			}
		}
		if(!$reply_to){
			if(Mage::helper('customer')->isLoggedIn()){
				$reply_to = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
			}
			else{
				$reply_to = Mage::getStoreConfig('trans_email/ident_general/email');
			}
		}
		if($recipient == 'customer'){
			if(Mage::getStoreConfig('webforms/email/email_reply_to'))
				$reply_to = Mage::getStoreConfig('webforms/email/email_reply_to');
			else
				$reply_to = Mage::getStoreConfig('trans_email/ident_general/email');
		}
		return $reply_to;
	}
	
	public function getCustomerEmail(){
		$fields = Mage::getModel('webforms/fields')->getCollection()->addFilter('webform_id',$this->getWebformId())->addFilter('type','email');
		
		foreach($this->getData() as $key=>$value){
			if($key == 'field_'.$fields->getFirstItem()->getId()) {
				$customer_email = $value;
			}
		}
		if(!$customer_email){
			if(Mage::helper('customer')->isLoggedIn()){
				$customer_email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
			}
		}
		return $customer_email;
	}
	
	public function getFileSizeText($field_id,$filename){
		$filename = Varien_File_Uploader::getCorrectFileName($filename);
		$loc = $this->getFileFullPath($field_id,$filename);
		$size = @filesize($loc);
		$sizes = array(" bytes", " kb", " mb", " gb", " tb", " pb", " eb", " zb", " yb");
		if ($size == 0) { return('n/a'); } else {
		return (round($size/pow(1024, ($i = floor(log($size, 1024)))), $i > 1 ? 2 : 0) . $sizes[$i]); }
	}
	
	public function getFileFullPath($field_id,$filename){
		return $path = $this->getFilePath($field_id) . $filename;
	}
	
	public function getFilePath($field_id){
		return $path = Mage::getBaseDir('media') . DS . 'webforms' . DS . $this->getId() . DS . $field_id . DS;
	}
	
	// path relative to Media folder
	public function getRelativePath($field_id,$filename){
		return 'webforms/'.$this->getId().'/'.$field_id.'/'.rawurlencode(Varien_File_Uploader::getCorrectFileName($filename));
	}
	
	public function getThumbnail($field_id, $filename, $width, $height = null){
		
		$filename = Varien_File_Uploader::getCorrectFileName($filename);
		
		$imageUrl = $this->getFileFullPath($field_id,$filename);
		
		$file_info = @getimagesize($imageUrl);
		
		if(!$file_info)
			return false;

		if(strstr($file_info["mime"],"bmp"))
			return false;
			
		if(file_exists($imageUrl))
			$imageObj = new Varien_Image($imageUrl);
		
		if(!$height && (float)substr(Mage::getVersion(),0,3)>1)
			$height = round($imageObj->getOriginalHeight() * ($width / $imageObj->getOriginalWidth()));
		
		$imageResized = $this->getFilePath($field_id)."thumb". DS . $width.'x'.$height.'_'.$filename; 
		
		if (!file_exists($imageResized)&&file_exists($imageUrl) || Mage::getStoreConfig('webforms/images/cache') == 0) {
			if((float)substr(Mage::getVersion(),0,3)>1){
				$imageObj->keepAspectRatio(true);
				$imageObj->keepTransparency(true);
			}
			$imageObj->resize($width, $height);
			$imageObj->save($imageResized);
		}
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "webforms/".$this->getId()."/". $field_id. "/thumb/" . $width . 'x' . $height . '_' . urlencode($filename);
	}
	
	public function getDownloadLink($field_id, $filename){
		$filename = Varien_File_Uploader::getCorrectFileName($filename);
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'webforms/'.$this->getId().'/'.$field_id.'/'.rawurlencode($filename);
	}
	
	public function toHtml($recipient = 'admin', $options = array()){
		$webform = Mage::getModel('webforms/webforms')->load($this->getWebformId());
		
		if(!isset($options['header'])){
			$options['header'] = $webform->getAddHeader();
		}
		if(!isset($options['skip_fields'])){
			$options['skip_fields'] = array();
		}
		
		$html = "";
		$store_group = Mage::app()->getStore($this->getStoreId())->getFrontendName();
		$store_name = Mage::app()->getStore($this->getStoreId())->getName();
		if($recipient == 'admin'){
			if($store_group)
				$html .= Mage::helper('webforms')->__('Store group').": ".$store_group."<br />";
			if($store_name)
				$html .= Mage::helper('webforms')->__('Store name').": ".$store_name."<br />";
			$html .= Mage::helper('webforms')->__('Customer').": ".$this->getCustomerName()."<br />";
			$html .= Mage::helper('webforms')->__('IP').": ".$this->getIp()."<br />";
		}
		$html .= Mage::helper('webforms')->__('Date').": ". Mage::helper('core')->formatDate($this->getCreatedTime(), 'medium', true)."<br />";
		$html .= "<br />";
		
		$head_html = "";
		if($options['header']) $head_html = $html;
		
		$html = "";
		
		$fields_to_fieldsets = Mage::getModel('webforms/webforms')->load($this->getWebformId())->getFieldsToFieldsets(true);
		
		foreach($fields_to_fieldsets as $fieldset){
			
			$k=false;
			$field_html= "";
			foreach($fieldset['fields'] as $field){

				$value= $this->getData('field_'.$field->getId());

				if(strlen(trim($value))){
					if(!in_array($field->getType(),$options['skip_fields']) && $field->getResultDisplay()!= 'off'){
						$field_name = $field->getName();
						if(strlen(trim($field->getResultLabel()))>0)
							$field_name = $field->getResultLabel();
						if($field->getResultDisplay()!='value') $field_html.= '<b>'.$field_name.'</b><br/>';
						$filename = $value;
						switch($field->getType()){
							case 'date': case 'datetime':
								$value = $field->formatDate($value);
								break;
							case 'stars':
								$value = $value.' / '.$field->getStarsCount();
								break;
							case 'file':
								if(strlen($value)>1)
									$value = '<a href="'.$this->getDownloadLink($field->getId(),$filename).'">'.$filename.'</a> <small>['.$this->getFileSizeText($field->getId(),$filename).']</small>';
								break;
							case 'image':
								if(strlen($value)>1)
									$value = '<a style="text-decoration:none" href="'.$this->getDownloadLink($field->getId(),$filename).'"><img src="'.$this->getThumbnail($field->getId(),$filename,Mage::getStoreConfig('webforms/images/email_thumbnail_width'),Mage::getStoreConfig('webforms/images/email_thumbnail_height')).'"/></a>';
								break;
							default :
								$value= nl2br(htmlspecialchars($value));
								break;
						}
						$k=true;
						$field_html.=$value."<br /><br />";
					}
				}

			}
			if(!empty($fieldset['name']) && $k && $fieldset['result_display'] == 'on')
				$field_html= '<h2>'.$fieldset['name'].'</h2>'.$field_html;
			$html.= $field_html;
		}
		return $head_html.$html;
		
	}
	
	public function toXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag=false, $addCdata=true){
		
		$webform = Mage::getModel('webforms/webforms')->load($this->getWebformId());
		
		if($webform->getCode())
			$this->setData('webform_code', $webform->getCode());
		
		foreach($this->getData() as $key => $value){
			if(strstr($key,'field_')){
				$field = Mage::getModel('webforms/fields')->load(str_replace('field_','',$key));
				if(!empty($field) && $field->getCode()){
					$this->setData($field->getCode(),$value);
				}
			}
		}
		return parent::toXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
	}
}
?>
