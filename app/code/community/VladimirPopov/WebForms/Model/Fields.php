<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2012 Vladimir Popov
 */

class VladimirPopov_WebForms_Model_Fields extends Mage_Core_Model_Abstract{
	
	protected $img_regex = '/{{img ([\w\/\.]+)}}/';
	protected $php_regex = '/<\?php(.*?)\?>/';

	public function _construct()
	{
		parent::_construct();
		$this->_init('webforms/fields');
	}
	
	public function getFieldTypes()
	{
		$types = new Varien_Object(array(
			"text" 				=> Mage::helper('webforms')->__('Text'),
			"email" 			=> Mage::helper('webforms')->__('Text / E-mail'),
			"number" 			=> Mage::helper('webforms')->__('Text / Number'),
			"url" 				=> Mage::helper('webforms')->__('Text / URL'),
			"textarea"			=> Mage::helper('webforms')->__('Textarea'),
			"wysiwyg"			=> Mage::helper('webforms')->__('HTML Editor'),
			"select" 			=> Mage::helper('webforms')->__('Select'),
			"select/radio" 		=> Mage::helper('webforms')->__('Select / Radio'),
			"select/checkbox" 	=> Mage::helper('webforms')->__('Select / Checkbox'),
			"select/contact" 	=> Mage::helper('webforms')->__('Select / Contact'),
			"date" 				=> Mage::helper('webforms')->__('Date'),
			"datetime" 			=> Mage::helper('webforms')->__('Date / Time'),
			"stars" 			=> Mage::helper('webforms')->__('Star Rating'),
			"file" 				=> Mage::helper('webforms')->__('File Upload'),
			"image" 			=> Mage::helper('webforms')->__('Image Upload'),
			"html" 				=> Mage::helper('webforms')->__('Html Block'),
			"hidden"			=> Mage::helper('webforms')->__('Hidden'),
		));
		
		// add more field types
		Mage::dispatchEvent('webforms_fields_types', array('types' => $types));

		return $types->getData();
		
	}
	
	public function getSelectOptions($clean = true){
		$field_value = $this->getValue();
		$options = explode("\n",$field_value);
		$options = array_map('trim',$options);
		$select_options = array();
		foreach($options as $o){
			if($this->getType() == 'select/contact'){
				if($clean){
					$contact = $this->getContactArray($o);
					$o = $contact['name'];
				}
			} 
			$select_options[$this->getCheckedOptionValue($o)] = $this->getCheckedOptionValue($o);
		} 
		return $select_options;
	}
	
	public function getResultsOptions(){
		$query = $this->getResource()->getReadConnection()
			->select('value')
			->from($this->getResource()->getTable('webforms/results_values'),array('value'))
			->where('field_id = '.$this->getId())
			->order('value asc')
			->distinct();
		$results = $this->getResource()->getReadConnection()->fetchAll($query);
		$options = array();
		foreach($results as $result){
			$options[$result['value']] =  $result['value'];
		}
		return $options;
	}
	
	public function getSizeTypes(){
		$types = new Varien_Object(array(
			"standard" => Mage::helper('webforms')->__('Standard'),
			"wide" => Mage::helper('webforms')->__('Wide'),
		));
		
		// add more size types
		Mage::dispatchEvent('webforms_fields_size_types', array('types' => $types));

		return $types->getData();
		
	}
	
	public function getAllowedExtensions(){
		if($this->getType() == 'image')
			return array('jpg','jpeg','gif','png');
		if($this->getType() == 'file'){
			$allowed_extensions = explode("\n",trim($this->getValue()));
			$allowed_extensions = array_map('trim',$allowed_extensions);
			$allowed_extensions = array_map('strtolower',$allowed_extensions);
			$filtered_result = array();
			foreach($allowed_extensions as $ext){
				if(strlen($ext)>0){
					$filtered_result[]= $ext;
				}
			}
			return $filtered_result;
		}
		return;
	}
	
	public function getStarsCount(){
		//return default value
		$options = $this->getOptionsArray();
		$value =0;
		if(!empty($options[0]))$value = (int)$options[0]['value'];
		if($value>0) return $value;
		return 5;
	}
	
	public function getStarsOptions(){
		$count = $this->getStarsCount();
		$options = array();
		for($i=1;$i<=$count;$i++){
			$options[$i] = $i;
		}
		return $options;
	}
	
	public function getDateType(){
		$type = "medium";
		$allowed_types = array('short','medium','long','full');
		$value = trim($this->getValue());
		if(in_array($value,$allowed_types)){
			$type = $value;
		}
		return $type;
	}
	
	public function getDateFormat(){
		$format = Mage::app()->getLocale()->getDateFormat($this->getDateType());
		if($this->getType() == 'datetime'){
			$format = Mage::app()->getLocale()->getDateTimeFormat($this->getDateType());
		}
		return $format;
	}
	
	public function getDateStrFormat(){
		$str_format =  Varien_Date::convertZendToStrftime($this->getDateFormat(), true, true);
		return $str_format;
	}
	
	public function getDbDateFormat(){
		$format = "Y-m-d";
		if($this->getType() == 'datetime'){
			$format = "Y-m-d H:i:s";
		}
		return $format;
	}
	
	public function formatDate($value){
		if(strlen($value)>0){
			$format = $this->getDateStrFormat();
			if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
				$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
			}				
			return strftime($format,strtotime($value));
		}
		return;
	}
	
	public function isCheckedOption($value){
		$customer_value = $this->getData('customer_value');
		if($customer_value){
			$customer_values_array = explode("\n",$customer_value);
			foreach($customer_values_array as $val){
				if(trim($val) == $this->getCheckedOptionValue($value)){
					return true;			
				}
			}
			return false;
		}
		if(substr($value,0,1) == '^')
			return true;
		return false;
	}
	
	public function isNullOption($value){
		if(substr($value,0,2) == '^^')
			return true;
		return false;
	}
	
	public function getCheckedOptionValue($value){
		$value = preg_replace($this->img_regex,"",$value);
		if($this->isNullOption($value))
			return trim(substr($value,2));
		if(substr($value,0,1)=='^')
			return trim(substr($value,1));
		return trim($value);
	}
	
	public function getOptionsArray(){
		$options = array();
		$values = explode("\n",$this->getFilter()->filter($this->getValue()));
		foreach($values as $val){
			$image_src = false;
			$matches = array();
			preg_match($this->img_regex,$val,$matches);
			if(!empty($matches[1])){
				$image_src = Mage::app()->getStore()->getUrl(dirname('media/'.$matches[1])).basename($matches[1]);
			}
			if(trim($val)){
				$options[] = array(
					'value' => $this->getCheckedOptionValue($val),
					'label' => $this->getCheckedOptionValue($val),
					'null' => $this->isNullOption($val),
					'checked' => $this->isCheckedOption($val),
					'image_src' => $image_src,
				);
			}
		}
		return $options;
	}
	
	public function getContactArray($value){
		preg_match('/(\w.+) <([^<]+?)>/',$value,$matches);
		if(!empty($matches[1]) && !empty($matches[2]))
			return array("name" => trim($matches[1]) , "email" => trim($matches[2]));
		return false;
	}
	
	public function getContactValueById($id){
		$options = $this->getOptionsArray();
		if(!empty($options[$id]['value']))
			return $options[$id]['value'];
		return false;
	}
	
	public function getHiddenFieldValue(){
		$result = $this->getData('result');
		$customer_value = $result ? $result->getData('field_'.$this->getId()) : false;
		if($customer_value) return $customer_value;
		
		preg_match($this->php_regex,$this->getValue(),$matches);
		if($matches[1]){
			ob_start();
			try{
				eval($matches[1]);
			} catch (Exception $e){
				
			}
			return ob_get_clean();
		}
		
		return $this->getValue();
	}
	
	public function getFilter(){
		$filter = new Varien_Filter_Template_Simple();
		
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if($customer->getDefaultBillingAddress()){
			foreach($customer->getDefaultBillingAddress()->getData() as $key=>$value)
				$filter->setData($key,$value);
		}
		$filter->setData('firstname',$customer->getFirstname());
		$filter->setData('lastname',$customer->getLastname());
		$filter->setData('email',$customer->getEmail());
		return $filter;		
	}
	
	public function toHtml()
	{
		$html="";
		
		$filter = $this->getFilter();	
		
		// apply custom filter
		Mage::dispatchEvent('webforms_fields_tohtml_filter',array('filter'=>$filter));
		
		$field_id="field[".$this->getId()."]";
		$field_name = $field_id;
		$field_value = $filter->filter($this->getValue());
		$result = $this->getData('result');
		$customer_value = $result ? $result->getData('field_'.$this->getId()) : false;
		$this->setData('customer_value',$customer_value);
		$field_type = $this->getType();
		$field_class="input-text";
		$field_style = "";
		$validate = "";
		if($this->getRequired())
			$field_class.=" required-entry";
		if($field_type == "email")
			$field_class.= " validate-email";
		if($field_type == "number")
			$field_class.= " validate-number";
		if($field_type == "url")
			$field_class.= " validate-clean-url";
		if($this->getCssClass()){
			$field_class.=' '.$this->getCssClass();
		}
		if($this->getData('validate_length_min') || $this->getData('validate_length_max')){
			$field_class.=' validate-length';
		}
		if($this->getData('validate_length_min')){
			$field_class.=' minimum-length-'.$this->getData('validate_length_min');
		}
		if($this->getData('validate_length_max')){
			$field_class.=' maximum-length-'.$this->getData('validate_length_max');
		}
		if($this->getData('validate_regex')){
			$field_class.=' validate-field-'.$this->getId();
		}
		if($this->getCssStyle()){
			$field_style = $this->getCssStyle();
		}
		$tinyMCE = false;
		$showTime = false;
		$config = array(
			'field' => $this,
			'field_id' => $field_id,
			'field_name' => $field_name,
			'field_class' => $field_class,
			'field_style' => $field_style,
			'field_value' => $field_value,
			'result' => $result,
			'show_time' => 'false',
			'customer_value' => $customer_value,
			'template' => 'webforms/fields/text.phtml'				
		);
		
		switch($field_type){
			case 'textarea': 
				if($customer_value){
					$config['field_value'] = $customer_value;
				}
				$config['template'] = 'webforms/fields/textarea.phtml';
				break;
			case 'wysiwyg':
				if($customer_value){
					$config['field_value'] = $customer_value;
				}
				$config['template'] = 'webforms/fields/wysiwyg.phtml';
				break;
			case 'select': 
				$config['field_options'] = $this->getOptionsArray();
				$config['template'] = 'webforms/fields/select.phtml';
				break;
			case 'select/contact': 
				$config['field_options'] = $this->getOptionsArray();
				$config['template'] = 'webforms/fields/select_contact.phtml';
				break;
			case 'select/radio':
				$config['field_class'] = $this->getCssClass();
				$config['field_options'] = $this->getOptionsArray();
				$config['template'] = 'webforms/fields/select_radio.phtml';
				break;
			case 'select/checkbox':
				$config['field_class'] = $this->getCssClass();
				$config['field_options'] = $this->getOptionsArray();
				$config['template'] = 'webforms/fields/select_checkbox.phtml';
				break;
			case 'stars':
				$config['field_options'] = $this->getOptionsArray();
				$config['template'] = 'webforms/fields/stars.phtml';
				break; 
			case 'image': case 'file':
				$config['field_id'] = 'file_'.$this->getId();
				$config['field_name'] = $config['field_id'];
				$config['template'] = 'webforms/fields/file.phtml';				
				break;
			case 'html':
				$config['template'] = 'webforms/fields/html.phtml';
				break;
			case 'datetime':
				$config['show_time'] = 'true';
				$showTime = true;
			case 'date': case 'datetime':
				if($customer_value){
					// format customer value
					$config['customer_value'] = Mage::helper('core')->formatDate($customer_value,$this->getDateType(),$showTime);
				}
				$config['template'] = 'webforms/fields/date.phtml';
				break;
			case 'hidden':
				$config['template'] = 'webforms/fields/hidden.phtml';
				break;
			default: 
				if($customer_value){
					$config['field_value'] = $customer_value;
				}
				$config['template'] = 'webforms/fields/text.phtml';
				break;
		}
		
		$html = Mage::app()->getLayout()->createBlock('core/template',$field_name,$config)->toHtml();
		
		if($this->getData('validate_regex')){
			$validate_message = str_replace("'","\'",$this->getData('validate_message'));
			$html.="<script>Validation.add('validate-field-{$this->getId()}','{$validate_message}',function(v){var r = new RegExp('{$this->getData('validate_regex')}');return Validation.get('IsEmpty').test(v) || r.test(v);})</script>";
		}
		
		// apply custom field type
		$html_object = new Varien_Object(array('html'=>$html));
		Mage::dispatchEvent('webforms_fields_tohtml_html',array('field'=>$this,'html_object'=>$html_object));
		
		return $html_object->getHtml();
	}
	
	public function duplicate(){
		// duplicate field
		$field = Mage::getModel('webforms/fields')
			->setData($this->getData())
			->setId(null)
			->setName($this->getName().' '.Mage::helper('webforms')->__('(new copy)'))
			->setIsActive(false)
			->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())
			->setUpdateTime(Mage::getSingleton('core/date')->gmtDate())
			->save();
		
		return $field;
	}
	
}
?>