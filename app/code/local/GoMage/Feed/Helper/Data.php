<?php
 /**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2012 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.0
 * @since        Class available since Release 1.0
 */

class GoMage_Feed_Helper_Data extends Mage_Core_Helper_Abstract{
	
	protected $attribute_collection = null;
	protected $attribute_options = null;
	protected $output_types = null;
	
    public function getConfigData($node){
		return Mage::getStoreConfig('gomage_feed/'.$node);
	}
	
	public function getAllStoreDomains(){
		
		$domains = array();
    	
    	foreach (Mage::app()->getWebsites() as $website) {
    		
    		$url = $website->getConfig('web/unsecure/base_url');
    		
    		if($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))){    		
    			$domains[] = $domain;    		
    		}
    		
    		$url = $website->getConfig('web/secure/base_url');
    		
    		if($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))){    		
    			$domains[] = $domain;    		
    		}    		
    	}    	
    	return array_unique($domains);    			
	}
	
	public function getAvailabelWebsites(){
		return $this->_w();
	}
	
	public function getAvailavelWebsites(){
		return $this->_w();
	}
		
	protected function _w(){
    
        if(!Mage::getStoreConfig('gomage_activation/feed/installed') || 
           (intval(Mage::getStoreConfig('gomage_activation/feed/count')) > 10))
		{
			return array();
		}
		            		
		$time_to_update = 60*60*24*15;
		
		$r = Mage::getStoreConfig('gomage_activation/feed/ar');
		$t = Mage::getStoreConfig('gomage_activation/feed/time');
		$s = Mage::getStoreConfig('gomage_activation/feed/websites');
		
		$last_check = str_replace($r, '', Mage::helper('core')->decrypt($t));
		
		$allsites = explode(',', str_replace($r, '', Mage::helper('core')->decrypt($s)));
		$allsites = array_diff($allsites, array(""));
			
		if(($last_check+$time_to_update) < time()){
			$this->a(Mage::getStoreConfig('gomage_activation/feed/key'), 
			         intval(Mage::getStoreConfig('gomage_activation/feed/count')),
			         implode(',', $allsites));
		}
		
		return $allsites;
		
	}
	
	public function a($k, $c = 0, $s = ''){
		
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_downloadable/key/check'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'key='.urlencode($k).'&sku=feed-pro&domains='.urlencode(implode(',', $this->getAllStoreDomains())));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        $content = curl_exec($ch);
        
        $r	= Zend_Json::decode($content);
        $e = Mage::helper('core');
        if(empty($r)){
        	
        	$value1 = Mage::getStoreConfig('gomage_activation/feed/ar');
        	
	        $groups = array(
	        	'feed'=>array(
	        		'fields'=>array(
	        			'ar'=>array(
	        				'value'=>$value1
	        			),
	        			'websites'=>array(
	        				'value'=>(string)Mage::getStoreConfig('gomage_activation/feed/websites')
	        			),
	        			'time'=>array(
	        				'value'=>(string)$e->encrypt($value1.(time()-(60*60*24*15-1800)).$value1)
	        			),
	        			'count'=>array(
	        				'value'=>$c+1)
	        		)
	        	)
        	);
        	
	        Mage::getModel('adminhtml/config_data')
	                ->setSection('gomage_activation')
	                ->setGroups($groups)
	                ->save();
        	
	        Mage::getConfig()->reinit();
            Mage::app()->reinitStores();        
	                
        	return;
        }
        
        $value1 = '';
        $value2 = '';
        
        
        
        if(isset($r['d']) && isset($r['c'])){
    		$value1 = $e->encrypt(base64_encode(Zend_Json::encode($r)));
        
        
        if (!$s) $s = Mage::getStoreConfig('gomage_activation/feed/websites');
        
        $s = array_slice(explode(',', $s), 0, $r['c']);
        
        $value2 = $e->encrypt($value1.implode(',', $s).$value1);
        
        }
        $groups = array(
        	'feed'=>array(
        		'fields'=>array(
        			'ar'=>array(
        				'value'=>$value1
        			),
        			'websites'=>array(
        				'value'=>(string)$value2
        			),
        			'time'=>array(
        				'value'=>(string)$e->encrypt($value1.time().$value1)
        			),
        			'installed'=>array(
        				'value'=>1
        			),
        			'count'=>array(
        				'value'=>0)
        			
        		)
        	)
        );
        
        Mage::getModel('adminhtml/config_data')
                ->setSection('gomage_activation')
                ->setGroups($groups)
                ->save();
                
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();        
        
	}
	
	public function ga(){
		return Zend_Json::decode(base64_decode(Mage::helper('core')->decrypt(Mage::getStoreConfig('gomage_activation/feed/ar'))));
	}
    
	public function getAttributeCollection(){

		if (is_null($this->attribute_collection)){
			$this->attribute_collection = Mage::getResourceModel('eav/entity_attribute_collection')
				->setItemObjectClass('catalog/resource_eav_attribute')
				->setEntityTypeFilter(Mage::getResourceModel('catalog/product')->getTypeId())
				->addFieldToFilter('attribute_code', array('nin' => array('gallery', 'media_gallery')));
		}	
					
		return $this->attribute_collection;
	}
	
	public function getAttributeOptionsArray(){

		if (is_null($this->attribute_options)){
			$this->attribute_options = array();
			
			$this->attribute_options['Product Id'] = array('code'=>"entity_id", 'label' => "Product Id");
	    	$this->attribute_options['Is In Stock'] = array('code'=>"is_in_stock" , 'label' =>  "Is In Stock");
	    	$this->attribute_options['Qty'] = array('code'=>"qty" , 'label' =>  "Qty");
	    	$this->attribute_options['Image'] = array('code'=>"image" , 'label' =>  "Image");
	    	$this->attribute_options['URL'] = array('code'=>"url" , 'label' =>  "URL");    	
	    	$this->attribute_options['Category'] = array('code'=>"category", 'label' =>  "Category");
	    	$this->attribute_options['Final Price'] = array('code'=>"final_price", 'label' =>  "Final Price");
	    	$this->attribute_options['Store Price'] = array('code'=>"store_price", 'label' =>  "Store Price");    	
	    	$this->attribute_options['Image 2'] = array('code'=>"image_2", 'label' =>  "Image 2");
	    	$this->attribute_options['Image 3'] = array('code'=>"image_3", 'label' =>  "Image 3");
	    	$this->attribute_options['Image 4'] = array('code'=>"image_4", 'label' =>  "Image 4");
	    	$this->attribute_options['Image 5'] = array('code'=>"image_5", 'label' =>  "Image 5");
	    	$this->attribute_options['Category > SubCategory'] = array('code'=>"category_subcategory", 'label' => "Category > SubCategory");    	
					
			$custom_attributes = Mage::getResourceModel('gomage_feed/custom_attribute_collection');
			
			foreach($custom_attributes as $attribute){				
				$label = '* '.$attribute->getName();				
				$this->attribute_options[$label] = array('code'=>sprintf('custom:%s', $attribute->getCode()), 'label'=>$label);				
			}
					
			foreach($this->getAttributeCollection() as $attribute){
				if($attribute->getFrontendLabel()){
					$this->attribute_options[$attribute->getFrontendLabel()] = array('code'=>$attribute->getAttributeCode(), 'label'=>($attribute->getFrontendLabel() ? $attribute->getFrontendLabel() : $attribute->getAttributeCode()));
				}			
			}
			
			ksort($this->attribute_options);

		}
		
		return $this->attribute_options;
		
	}
	
	public function getAttributeSelect($i, $current = null, $active = true){		
		$options = array();		
		$options[] = "<option value=''>Not Set</option>";		
		foreach($this->getAttributeOptionsArray() as $attribute){			
			extract($attribute);			
			$selected = '';			
			if($code == $current){
				$selected = 'selected="selected"';
			}			
			$options[] = "<option value=\"{$code}\" {$selected}>{$label}</option>";			
		}		
		return '<select style="width:260px;display:'.($active ? 'block' : 'none').'" id="mapping-'.$i.'-attribute-value" name="field['.$i.'][attribute_value]">'.implode('', $options).'</select>';		
	}
	
	public function getSystemSections()
	{	    
	    $data = array(); 	    
	    $fileDir = Mage::getBaseDir('media') . DS . 'productsfeed' . DS . 'examples';	    
    	if ($handle = opendir($fileDir)) 
    	{
            while (false !== ($dir = readdir($handle))) {                
                if ($dir != '.' && $dir != '..')
                {
                    if (is_dir($fileDir . DS . $dir) && ($sub_handle = opendir($fileDir . DS . $dir)))
                    {
                        $data[$dir] = array();
                        while (false !== ($file = readdir($sub_handle))) {  
                            if ($file != '.' && $file != '..') {
                                $data[$dir][] = $file;        
                            }
                        }
                        closedir($sub_handle);
                    }
                }                
            }        
            closedir($handle);
        }        
        return $data;        
	}
	
	public function getOutputTypes(){
		if (is_null($this->output_types)){
			$this->output_types = array(
				array('code' => '', 'label' => $this->__('Default')),
				array('code' => 'int', 'label' => $this->__('Integer')),
				array('code' => 'float', 'label' => $this->__('Float')),
				array('code' => 'striptags', 'label' => $this->__('Striptags')),
				array('code' => 'htmlspecialchars', 'label' => $this->__('Encode special chars')),
				array('code' => 'htmlspecialchars_decode', 'label' => $this->__('Decode special chars')),
				array('code' => 'delete_space', 'label' => $this->__('Delete Space')),
				array('code' => 'big_to_small', 'label' => $this->__('Big to small')),
			);
		}
		
		return $this->output_types;
	}
	
	public function getOutputTypeSelect($i, $values = ''){		
		
		$values = explode(',', $values);
		$multiple = (count($values) > 1 ? 'multiple="multiple"' : '');
		$options = array();							
			
		foreach($this->getOutputTypes() as $output_type){			
			extract($output_type);			
			$selected = '';			
			if(in_array($code, $values)){
				$selected = 'selected="selected"';
			}			
			$options[] = "<option value=\"{$code}\" {$selected}>{$label}</option>";			
		}

		$select_id = 'field_'.$i.'_output_type';
		
		return '<select '.$multiple.' id="'.$select_id.'" name="field['.$i.'][output_type][]">'.implode('', $options).'</select><a class="gfp-toggle-multi" href="javascript:void(0)" onclick="gfp_toggle_multi(this, \''.$select_id.'\')">'.(count($values)>1 ? '-' : '+').'</a>';		
	}
     
}
