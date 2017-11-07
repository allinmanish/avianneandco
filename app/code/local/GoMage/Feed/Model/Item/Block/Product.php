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

class GoMage_Feed_Model_Item_Block_Product extends GoMage_Feed_Model_Item_Block {
	
	public function writeTempFile($curr_page = 0, $length = 50, $filename = '') {
		
		$collection = $this->getFeed()->getProductsCollection('', $curr_page, $length);
		
		$content = @file_get_contents(Mage::helper('gomage_feed/generator')->getBaseDir() . DS . 'feed-' . $this->getFeed()->getId() . '-xml-product-block-template.tmp');
		$template_attributes = $this->getAllVars($content);
		
		$codes = array();
		
		foreach ($template_attributes as $attribute_code) {
			$codes[] = $attribute_code;
		}
		
		$custom_attributes = Mage::getResourceModel('gomage_feed/custom_attribute_collection');
		$custom_attributes->load();
		
		foreach ($custom_attributes as $_attribute) {
			$options = Zend_Json::decode($_attribute->getData('data'));
			if ($options && is_array($options)) {
				$_attribute->setOptions($options);
				foreach ($options as $option) {
					if (isset($option['value_type_attribute']) && $option['value_type_attribute']) {
						foreach ($option['value_type_attribute'] as $_value_type_attribute) {
							$codes[] = $_value_type_attribute['attribute'];
						}
					}
					foreach ($option['condition'] as $_condition) {
						$codes[] = $_condition['attribute_code'];
					}
				}
			}
			else {
				$_attribute->setOptions(array());
			}
		}
		
		$attributes = Mage::getModel('eav/entity_attribute')->getCollection()->setEntityTypeFilter(Mage::getResourceModel('catalog/product')->getEntityType()->getData('entity_type_id'))->setCodeFilter($codes);
		$root_category = $this->getFeed()->getRootCategory();
		
		$this->getFeed()->addLog(date("F j, Y, g:i:s a") . ', page:' . $curr_page . ', items selected:' . count($collection) . "\n");
		
		foreach ($collection as $_product) {
			
			if ($this->getFeed()->getUseLayer() == GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO_WITH_CHILD) {
				if ($_product->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
					if (! $_product->isSalable()) {
						$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getFeed()->getId());
						$generate_info->addGeneratedRecords(1)->save();
						continue;
					}
				}
			}
			
			$product = new Varien_Object();
			
			$this->getFeed()->prepareProductCategory($_product, $root_category);
			
			foreach ($template_attributes as $attribute_code) {
				
				if (strpos($attribute_code, 'parent:') === 0) {
					$attribute_code_parent = trim(str_replace('parent:', '', $attribute_code));
					$parent_product = $this->getFeed()->getParentProduct($_product);
					if ($parent_product->getId()) {
						$value = $this->getFeed()->getProductAttributeValue($parent_product, $attribute_code_parent, $attributes, $custom_attributes);
					}
					else {
						$value = $this->getFeed()->getProductAttributeValue($_product, $attribute_code_parent, $attributes, $custom_attributes);
					}
				}
				else {
					$value = $this->getFeed()->getProductAttributeValue($_product, $attribute_code, $attributes, $custom_attributes);
				}
				
				if ($value && ! $product->getData($attribute_code)) {
					$product->setData($attribute_code, $value);
				}
			
			}
			
			$product->setDescription(strip_tags(preg_replace('/<br.*?>/s', "\r\n", $_product->getDescription())));
			$product->setShortDescription(strip_tags(preg_replace('/<br.*?>/s', "\r\n", $_product->getShortDescription())));
			
			$fp = fopen($this->getFeed()->getTempFilePath(), 'a');
			fwrite($fp, parent::setVars($content, $product) . "\r\n");
			fclose($fp);
			
			$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getFeed()->getId());
			if ($generate_info->getData('stopped')){
				return false;
			}
			$generate_info->addGeneratedRecords(1)->save();
		
		}
	
	}
	
	public function setVars($content = null, $dataObject = null, $remove_empty = false) {
		
		$template_temp_file = Mage::helper('gomage_feed/generator')->getBaseDir() . DS . 'feed-' . $this->getFeed()->getId() . '-xml-product-block-template.tmp';
		
		@file_put_contents($template_temp_file, $content);
		
		$collection = $this->getFeed()->getProductsCollection();
		$total_products = $collection->getSize();
		
		$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getFeed()->getId());
		$generate_info->setData('total_records', $total_products)->save();
		
		$per_page = intval($this->getFeed()->getIterationLimit());
		
		if ($per_page) {
			$pages = ceil($total_products / $per_page);
		}
		else {
			$pages = 1;
			$per_page = 0;
		}
		
		$this->getFeed()->addLog("started at:" . date("F j, Y, g:i:s a") . ", pages:{$pages}, per_page:{$per_page} \n", true);
		
		$store = $this->getFeed()->getStore();
		
		for($i = 0; $i < $pages; $i ++) {
			
			$feed_url = $store->getUrl('feed/index/index', array('id' => $this->getFeed()->getId(), 'start' => $i, 'length' => $per_page, '_nosid' => true));
			$contents = '';
			
			if (Mage::getStoreConfig('gomage_feedpro/configuration/system', $store) == GoMage_Feed_Model_Adminhtml_System_Config_Source_System::FOPEN) {
				if ($_fp = fopen($feed_url, 'r')) {
					while (! feof($_fp)) {
						$contents .= fread($_fp, 8192);
					}
					fclose($_fp);
				}
				else {
					$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getFeed()->getId());
					$generate_info->setError(Mage::helper('gomage_feed')->__('Cant open temp file'))->save();
					return false;
				}
			}
			else {
				try {
					$contents = Mage::helper('gomage_feed/generator')->getHttpClient()->setUri($feed_url)->request()->getBody();
				}
				catch (Exception $e) {
					$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getFeed()->getId());
					$generate_info->setError(Mage::helper('gomage_feed')->__('Cant open temp file'))->save();
					return false;
				}
			
			}
			
			$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getFeed()->getId());
			if ($generate_info->getData('stopped')){
				return false;
			}
			
			$response = Zend_Json::decode($contents);
			
			if (! isset($response['result']) || ! $response['result']) {
				$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getFeed()->getId());
				$generate_info->setError(Mage::helper('gomage_feed')->__('There was an error while generating file. Change "Number of Products" in the Advanced Settings.
                                                Try to change "Number of Products" in the Advanced Settings.
                                                For example: set "Number of Products" equal 50 or 100.'))->save();
				return false;
			
			}
		
		}
		
		$content = @file_get_contents($this->getFeed()->getTempFilePath());
		
		@unlink($template_temp_file);
		@unlink($this->getFeed()->getTempFilePath());
		
		return $content;
	
	}

}