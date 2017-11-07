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

class GoMage_Feed_Model_Item extends Mage_Core_Model_Abstract {
	
	protected $_productCollection = null;
	protected $_categoryCollection = null;
	protected $_parentProductsCache = array();
	protected $_store = null;
	protected $product_image_width = null;
	protected $product_image_height = null;
	
	public function _construct() {
		parent::_construct();
		$this->_init('gomage_feed/item');
	}
	
	public function getCategoriesCollection() {
		if (is_null($this->_categoryCollection)) {
			$this->_categoryCollection = Mage::getResourceModel('catalog/category_collection')->addAttributeToSelect('name');
		}
		return $this->_categoryCollection;
	}
	
	public function getStore() {
		if (is_null($this->_store)) {
			$this->_store = Mage::getModel('core/store')->load($this->getStoreId());
		}
		return $this->_store;
	}
	
	public function getFileNameWithExt() {
		$filename = $this->getFilename();
		if (strpos($this->getFilename(), '.') === false) {
			if ($this->getFilenameExt()) {
				$filename .= '.' . $this->getFilenameExt();
			}
		}
		return $filename;
	}
	
	public function getParentProduct(Varien_Object $product, $collection = null) {
		
		if (! isset($this->_parentProductsCache[$product->getEntityId()])) {
			
			$connection = Mage::getSingleton('core/resource')->getConnection('read');
			$table = Mage::getSingleton('core/resource')->getTableName('catalog_product_relation');
			
			$parent_product = null;
			
			$parent_id = $connection->fetchOne('SELECT `parent_id` FROM ' . $table . ' WHERE `child_id` = ' . intval($product->getEntityId()));
			
			if ($parent_id > 0) {
				if ($collection) {
					$parent_product = $collection->getItemById($parent_id);
				}
				if (! $parent_product) {
					$parent_product = Mage::getModel('catalog/product')->load($parent_id);
				}
				
				$root_category = $this->getRootCategory();
				$this->prepareProductCategory($parent_product, $root_category);
				
				$this->_parentProductsCache[$product->getEntityId()] = $parent_product;
			}
			else {
				$this->_parentProductsCache[$product->getEntityId()] = new Varien_Object();
			}
		}
		
		return $this->_parentProductsCache[$product->getEntityId()];
	}
	
	public function getRootCategory() {
		$store = $this->getStore();
		$root_category = Mage::getModel('catalog/category')->load($store->getRootCategoryId());
		return $root_category;
	}
	
	public function getProductsCollection($filterData = '', $current_page = 0, $length = 50) {
		
		if (is_null($this->_productCollection) && $this->getId()) {
			
			$collection = Mage::getModel('gomage_feed/product_collection')->addAttributeToSelect('*');
			$collection->addStoreFilter($this->getStore());
			
			if ($length != 0) {
				$collection->setPage($current_page + 1, $length);
			}
			
			$collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())->addMinimalPrice()->addFinalPrice()->addTaxPercents()->addUrlRewrite($this->getRootCategory()->getId());
			
			if ($this->getVisibility()) {
				switch ($this->getVisibility()) {
					case Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE:
						$collection->setVisibility(array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE));
					break;
					case Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG:
						Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
					break;
					case Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH:
						Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);
					break;
					case Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH:
						Mage::getSingleton('catalog/product_visibility')->addVisibleInSiteFilterToCollection($collection);
					break;
					case 5:
						$collection->setVisibility(array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG));
					break;
					case 6:
						$collection->setVisibility(array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH));
					break;
				}
			}
			
			if (in_array($this->getUseLayer(), array(GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO, GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO_WITH_CHILD))) {
				$collection->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id');
				$collection->addAttributeToFilter('is_in_stock', array('eq' => Mage_CatalogInventory_Model_Stock::STOCK_IN_STOCK));
			}
			
			if ($this->getUseDisabled()) {
				Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
				$collection->addAttributeToFilter('status', array('in' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED));
			}
			
			if ($this->getFilter()) {
				
				$collection->setFilterType($this->getFilterType());
				
				$filter_values = json_decode($this->getFilter(), true);
				$filters = array();
				
				foreach (( array ) $filter_values as $filter) {
					
					$code = trim(@$filter['attribute_code']);
					$condition = trim(@$filter['condition']);
					$value = trim(@$filter['value']);
					
					if ($code && $condition) {
						if (! isset($filters[$code])) {
							$filters[$code] = array();
						}
						if (! isset($filters[$code][$condition])) {
							$filters[$code][$condition] = array();
						}
						$filters[$code][$condition][] = $value;
					}
				}
				
				$collection_filters = array();
				
				foreach ($filters as $code => $filter) {
					
					if ($code == 'qty') {
						$collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
					}
					
					foreach ($filter as $condition => $value) {
						if ($code == 'category_id') {
							foreach ($value as $_value) {
								$collection->prepareFeedCategoryFilter($condition, $_value);
							}
						}
						elseif ($code == 'product_type') {
							$all_product_type = Mage_Catalog_Model_Product_Type::getOptionArray();
							$all_product_type = array_keys($all_product_type);
							if (isset($filter['neq'])) {
								$all_product_type = array_diff($all_product_type, $filter['neq']);
							}
							if (isset($filter['eq'])) {
								$all_product_type = array_intersect($all_product_type, $filter['eq']);
							}
							
							$collection_filters[] = array('attribute' => 'type_id', 'condition' => 'in', 'value' => $all_product_type);
							
							break;
						}
						else {
							switch ($condition) {
								case 'eq':
									$condition = 'in';
								break;
								case 'neq':
									$condition = 'nin';
								break;
								case 'gt':
								case 'gteq':
									$value = max($value);
								break;
								case 'lt':
								case 'lteq':
									$value = min($value);
								break;
								case 'like':
									if (count($value) > 1) {
										$_attribute = array();
										foreach ($value as $_value) {
											$collection_filters[] = array('attribute' => $code, 'condition' => $condition, 'value' => $_value);
										}
										continue 2;
									}
									else {
										$value = $value[0];
									}
								break;
								case 'nlike':
									foreach ($value as $_value) {
										$collection_filters[] = array('attribute' => $code, 'condition' => $condition, 'value' => $_value);
									}
									continue 2;
								break;
							}
							
							$collection_filters[] = array('attribute' => $code, 'condition' => $condition, 'value' => $value);
						
						}
					}
				}
				if (isset($filters['category_id'])) {
					$collection->addFeedCategoryFilter();
				}
				
				if (! empty($collection_filters)) {
					if ($this->getFilterType() == GoMage_Feed_Model_Adminhtml_System_Config_Source_Filter::TOGETHER) {
						//Together
						foreach ($collection_filters as $collection_filter) {
							$collection->addAttributeToFilter($collection_filter['attribute'], array($collection_filter['condition'] => $collection_filter['value']));
						}
					}
					else {
						//Split
						$_collection_filters = array();
						foreach ($collection_filters as $collection_filter) {
							$_collection_filters[] = array('attribute' => $collection_filter['attribute'], $collection_filter['condition'] => $collection_filter['value']);
						}
						$collection->addAttributeToFilter($_collection_filters, null, 'left');
					}
				}
				else {
					$collection->applyFeedCategoryFilter();
				}
			}
			$this->_productCollection = $collection;
		}
		
		return $this->_productCollection;
	}
	
	public function save() {
		if (! $this->getFilename()) {
			$this->setFilename(preg_replace('/[^\w\d]/', '-', trim(strtolower($this->getName()))));
		}
		if ($this->getFilenameExt()) {
			$file_name = pathinfo($this->getFilename());
			$this->setFilename($file_name['filename']);
		}
		if ($id = Mage::getModel('gomage_feed/item')->load($this->getFilename(), 'filename')->getId()) {
			if ($id != $this->getId()) {
				throw new Mage_Core_Exception(Mage::helper('gomage_feed')->__('Filename "%s" exists', $this->getFilename()));
			}
		}
		return parent::save();
	}
	
	public function getTempFilePath() {
		$filename = 'feed-gen-data-' . $this->getId() . '.tmp';
		return Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $filename;
	}
	
	public function getProductImageWidth() {
		if (is_null($this->product_image_width)) {
			if (Mage::getStoreConfig('gomage_feedpro/imagesettings/enable', $this->getStore())) {
				$this->product_image_width = intval(Mage::getStoreConfig('gomage_feedpro/imagesettings/width', $this->getStore()));
			}
			else {
				$this->product_image_width = 0;
			}
		}
		return $this->product_image_width;
	}
	
	public function getProductImageHeight() {
		if (is_null($this->product_image_height)) {
			if (Mage::getStoreConfig('gomage_feedpro/imagesettings/enable', $this->getStore())) {
				$this->product_image_height = intval(Mage::getStoreConfig('gomage_feedpro/imagesettings/height', $this->getStore()));
			}
			else {
				$this->product_image_height = 0;
			}
		}
		return $this->product_image_height;
	}
	
	public function writeTempFile($start = 0, $length = 50, $filename = '') {
		
		if ($filename) {
			$filePath = Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $filename;
		}
		else {
			$filePath = $this->getTempFilePath();
		}
		$fileDir = Mage::helper('gomage_feed/generator')->getBaseDir();
		
		if (! file_exists($fileDir)) {
			mkdir($fileDir);
			chmod($fileDir, 0777);
		}
		
		if (is_dir($fileDir)) {
			
			if ($this->getType() == 'csv') {
				
				switch ($this->getDelimiter()) {
					case ('comma'):
					default:
						$delimiter = ",";
					break;
					case ('tab'):
						$delimiter = "\t";
					break;
					case ('colon'):
						$delimiter = ":";
					break;
					case ('space'):
						$delimiter = " ";
					break;
					case ('vertical pipe'):
						$delimiter = "|";
					break;
					case ('semi-colon'):
						$delimiter = ";";
					break;
				}
				
				switch ($this->getEnclosure()) {
					case (1):
					default:
						$enclosure = "'";
					break;
					case (2):
						$enclosure = '"';
					break;
					case (3):
						$enclosure = ' ';
					break;
					case (4):
						$enclosure = ' ';
					break;
				}
				
				$collection = $this->getProductsCollection('', $start, $length);
				
				$maping = json_decode($this->getContent());
				
				$codes = array();
				
				foreach ($maping as $col) {
					if ($col->type == 'attribute') {
						$codes[] = $col->attribute_value;
					}
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
				$root_category = $this->getRootCategory();
				
				$this->addLog(date("F j, Y, g:i:s a") . ', page:' . $start . ', items selected:' . count($collection) . "\n");
				
				foreach ($collection as $product) {
					
					if ($this->getUseLayer() == GoMage_Feed_Model_Adminhtml_System_Config_Source_Uselayer::NO_WITH_CHILD) {
						if ($product->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
							if (! $product->isSalable()) {
								$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId());
								$generate_info->addGeneratedRecords(1)->save();
								continue;
							}
						}
					}
					
					$fields = array();
					
					$this->prepareProductCategory($product, $root_category);
					
					foreach ($maping as $col) {
						
						$value = null;
						
						if ($col->type == 'attribute') {
							if ($col->attribute_value) {
								$value = $this->getProductAttributeValue($product, $col->attribute_value, $attributes, $custom_attributes);
							}
							else {
								$value = '';
							}
						}
						elseif ($col->type == 'parent_attribute') {
							if ($col->attribute_value) {
								$parent_product = $this->getParentProduct($product);
								if ($parent_product->getId()) {
									$value = $this->getProductAttributeValue($parent_product, $col->attribute_value, $attributes, $custom_attributes);
								}
								else {
									$value = $this->getProductAttributeValue($product, $col->attribute_value, $attributes, $custom_attributes);
								}
							}
							else {
								$value = '';
							}
						}
						else {
							$value = $col->static_value;
						}
						
						if ($output_type = $col->output_type) {
							$value = $this->applyValueFilter('format', $output_type, $value);
						}
						
						if (intval($this->getRemoveLb())) {
							$value = str_replace("\n", '', $value);
							$value = str_replace("\r", '', $value);
						}
						
						$value = $this->applyValueFilter('limit', $col->limit, $value);
						
						$fields[] = $col->prefix . $value . $col->sufix;
					
					}
					
					$fp = fopen($filePath, 'a');
					if ($this->getEnclosure() != 4) {
						fputcsv($fp, $fields, $delimiter, $enclosure);
					} else {
						$this->extended_fputcsv($fp, $fields, $delimiter);
					}
					fclose($fp);
					
					$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId());
					if ($generate_info->getData('stopped')) {
						return false;
					}
					$generate_info->addGeneratedRecords(1)->save();
				
				}
			}
			else {
				//XML FORMAT
				$rootBlock = Mage::getModel('gomage_feed/item_block', array('content' => $this->getContent(), 'feed' => $this));
				@file_put_contents($filePath, $rootBlock->render());
			}
		}
	}
	
	public function applyValueFilter($filter_name, $filter_value, $value) {
		
		switch (trim($filter_name)) {
			case 'format':
				$filter_value = explode(',', $filter_value);
				foreach ($filter_value as $filter) {
					switch (trim($filter)) {
						case 'float':
							$value = number_format(( float ) $value, 2, '.', '');
						break;
						case 'int':
							$value = intval($value);
						break;
						case 'striptags':
							$value = strip_tags($value);
							$value = trim($value);
						break;
						case 'htmlspecialchars':
							$encoding = mb_detect_encoding($value);
							$value = mb_convert_encoding($value, "UTF-8", $encoding);
							$value = htmlentities($value, null, "UTF-8");
						break;
						case 'htmlspecialchars_decode':
							$value = htmlspecialchars_decode($value);
						break;
						case 'delete_space':
							$value = str_replace(" ", "", $value);
						break;
						case 'big_to_small':
							$value = strtolower($value);
						break;
					}
				}
			break;
			case 'limit':
				if (intval($filter_value) && $value) {
					$value = substr($value, 0, intval($filter_value));
				}
			break;
		}
		
		return $value;
	
	}
	
	public function prepareProductCategory(&$product, $root_category) {
		$category = null;
		foreach ($product->getCategoryIds() as $id) {
			$_category = $this->getCategoriesCollection()->getItemById($id);
			if (is_null($category) || ($category && $_category && $category->getLevel() < $_category->getLevel())) {
				$category = $_category;
			}
		}
		
		if ($category) {
			$category_path = array($category->getName());
			$parent_id = $category->getParentId();
			
			if ($category->getLevel() > $root_category->getLevel()) {
				while ($_category = $this->getCategoriesCollection()->getItemById($parent_id)) {
					if ($_category->getLevel() <= $root_category->getLevel()) {
						break;
					}
					$category_path[] = $_category->getName();
					$parent_id = $_category->getParentId();
				}
			}
			
			$product->setCategory($category->getName());
			$product->setCategoryId($category->getEntityId());
			$product->setCategorySubcategory(implode(' > ', array_reverse($category_path)));
		
		}
		else {
			$product->setCategory('');
			$product->setCategorySubcategory('');
		}
	}
	
	public function getProductAttributeValue(&$product, $coll_attribute_value, $attributes, $custom_attributes) {
		
		$store = $this->getStore();
		$value = null;
		
		$image_width = $this->getProductImageWidth();
		$image_height = $this->getProductImageHeight();
		
		switch ($coll_attribute_value) {
			
			case ('price'):
				if (in_array($product->getTypeId(), array(Mage_Catalog_Model_Product_Type::TYPE_GROUPED, Mage_Catalog_Model_Product_Type::TYPE_BUNDLE)))
					$value = $store->convertPrice($product->getMinimalPrice(), false, false);
				else
					$value = $store->convertPrice($product->getPrice(), false, false);
			break;
			
			case ('store_price'):
				$value = $store->convertPrice($product->getFinalPrice(), false, false);
			break;
			
			case ('image'):
			case ('gallery'):
			case ('media_gallery'):
				
				if (! $product->hasData('product_base_image')) {
					$_prod = Mage::getModel('catalog/product')->load($product->getId());
					
					try {
						if ($image_width || $image_height) {
							$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image')->resize($image_width, $image_height);
						}
						else {
							$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image');
						}
					}
					catch (Exception $e) {
						$image_url = '';
					}
					$product->setData('product_base_image', $image_url);
					$value = $image_url;
				}
				else {
					$value = $product->getData('product_base_image');
				}
			
			break;
			case ('image_2'):
			case ('image_3'):
			case ('image_4'):
			case ('image_5'):
				if (! $product->hasData('media_gallery_images')) {
					$_prod = Mage::getModel('catalog/product')->load($product->getId());
					$product->setData('media_gallery_images', $_prod->getMediaGalleryImages());
				}
				$i = 0;
				foreach ($product->getMediaGalleryImages() as $_image) {
					$i ++;
					if (('image_' . $i) == $coll_attribute_value) {
						if ($image_width || $image_height) {
							$value = ( string ) Mage::helper('catalog/image')->init($product, 'image', $_image->getFile())->resize($image_width, $image_height);
						}
						else {
							$value = $_image['url'];
						}
					}
				}
			break;
			case ('thumbnail'):
			case ('small_image'):
				try {
					$_prod = Mage::getModel('catalog/product')->load($product->getId());
					if ($image_width || $image_height) {
						$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $coll_attribute_value)->resize($image_width, $image_height);
					}
					else {
						$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $coll_attribute_value);
					}
				}
				catch (Exception $e) {
					$image_url = '';
				}
				$value = $image_url;
			break;
			case ('url'):
				$value = $product->getProductUrl(false);
			break;
			case ('qty'):
				$stock_item = $product->getStockItem();
				if (! ($stock_item && $stock_item->getData('item_id'))) {
					$stock_item = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
				}
				if ($stock_item && $stock_item->getData('item_id')) {
					$product->setStockItem($stock_item);
					$value = ceil($stock_item->getQty());
				}
				else {
					$value = 0;
				}
			break;
			case ('category'):
				$value = $product->getCategory();
			break;
			default:
				
				if (strpos($coll_attribute_value, 'custom:') === 0) {
					
					$custom_code = trim(str_replace('custom:', '', $coll_attribute_value));
					
					if ($custom_attribute = $custom_attributes->getItemByColumnValue('code', $custom_code)) {
						
						$options = $custom_attribute->getOptions();
						
						foreach ($options as $option) {
							
							foreach ($option['condition'] as $condition) {
								
								switch ($condition['attribute_code']) {
									
									case ('product_type'):
										$attr_value = $product->getTypeId();
									break;
									
									case ('price'):
										if (in_array($product->getTypeId(), array(Mage_Catalog_Model_Product_Type::TYPE_GROUPED, Mage_Catalog_Model_Product_Type::TYPE_BUNDLE)))
											$attr_value = $store->convertPrice($product->getMinimalPrice(), false, false);
										else
											$attr_value = $store->convertPrice($product->getPrice(), false, false);
									break;
									
									case ('store_price'):
										$attr_value = $store->convertPrice($product->getFinalPrice(), false, false);
									break;
									
									case ('image'):
									case ('gallery'):
									case ('media_gallery'):
										
										if (! $product->hasData('product_base_image')) {
											$_prod = Mage::getModel('catalog/product')->load($product->getId());
											
											try {
												if ($image_width || $image_height) {
													$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image')->resize($image_width, $image_height);
												}
												else {
													$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image');
												}
											
											}
											catch (Exception $e) {
												$image_url = '';
											}
											$product->setData('product_base_image', $image_url);
											$attr_value = $image_url;
										
										}
										else {
											$attr_value = $product->getData('product_base_image');
										}
									
									break;
									case ('thumbnail'):
									case ('small_image'):
										try {
											$_prod = Mage::getModel('catalog/product')->load($product->getId());
											if ($image_width || $image_height) {
												$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $condition['attribute_code'])->resize($image_width, $image_height);
											}
											else {
												$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $condition['attribute_code']);
											}
										}
										catch (Exception $e) {
											$image_url = '';
										}
										$attr_value = $image_url;
									break;
									case ('url'):
										$attr_value = $product->getProductUrl(false);
									break;
									case ('qty'):
										$stock_item = $product->getStockItem();
										if (! ($stock_item && $stock_item->getData('item_id'))) {
											$stock_item = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
										}
										if ($stock_item && $stock_item->getData('item_id')) {
											$product->setStockItem($stock_item);
											$attr_value = ceil($stock_item->getQty());
										}
										else {
											$attr_value = 0;
										}
									break;
									case ('is_in_stock'):
										if ($stock_item = $product->getStockItem()) {
											$attr_value = $stock_item->getIsInStock();
										}
										else {
											$attr_value = 0;
										}
									break;
									case ('category'):
										$attr_value = $product->getCategory();
									break;
									case ('category_id'):
										$attr_value = $product->getCategoryIds();
									break;
									default:
										$attr_value = $product->getData($condition['attribute_code']);
								}
								
								$cond_value = $condition['value'];
								
								$is_multi = false;
								
								if ($product_attribute = $attributes->getItemByColumnValue('attribute_code', $condition['attribute_code'])) {
									
									if ($product_attribute->getFrontendInput() == 'multiselect') {
										
										$is_multi = true;
										$attr_value = explode(',', $attr_value);
									
									}
								
								}
								
								if ($condition['attribute_code'] == 'category_id') {
									$is_multi = true;
								}
								
								switch ($condition['condition']) {
									case ('eq'):
										if (! $is_multi && $attr_value == $cond_value || $is_multi && in_array($cond_value, $attr_value)) {
											continue 2;
										}
										else {
											continue 3;
										}
									break;
									case ('neq'):
										if (! $is_multi && $attr_value != $cond_value || $is_multi && ! in_array($cond_value, $attr_value)) {
											continue 2;
										}
										else {
											continue 3;
										}
									break;
									case ('gt'):
										if ($attr_value > $cond_value) {
											continue 2;
										}
										else {
											continue 3;
										}
									break;
									case ('lt'):
										if ($attr_value < $cond_value) {
											continue 1;
										}
										else {
											continue 3;
										}
									break;
									case ('gteq'):
										if ($attr_value >= $cond_value) {
											continue 2;
										}
										else {
											continue 3;
										}
									break;
									case ('lteq'):
										if ($attr_value <= $cond_value) {
											continue 2;
										}
										else {
											continue 3;
										}
									break;
									case ('like'):
										if (strpos($attr_value, $cond_value) !== false) {
											continue 2;
										}
										else {
											continue 3;
										}
									break;
									case ('nlike'):
										if (strpos($attr_value, $cond_value) === false) {
											continue 2;
										}
										else {
											continue 3;
										}
									break;
								}
							}
							
							if (in_array($option['value_type'], array('percent', 'attribute'))) {
								$attribute_value = '';
								foreach ($option['value_type_attribute'] as $_value_type_attribute) {
									$attribute_value_tmp = '';
									switch ($_value_type_attribute['attribute']) {
										
										case ('price'):
											
											if (in_array($product->getTypeId(), array(Mage_Catalog_Model_Product_Type::TYPE_GROUPED, Mage_Catalog_Model_Product_Type::TYPE_BUNDLE)))
												$attribute_value_tmp = $store->convertPrice($product->getMinimalPrice(), false, false);
											else
												$attribute_value_tmp = $store->convertPrice($product->getPrice(), false, false);
										
										break;
										
										case ('store_price'):
											
											$attribute_value_tmp = $store->convertPrice($product->getFinalPrice(), false, false);
										
										break;
										
										case ('image'):
										case ('gallery'):
										case ('media_gallery'):
											
											if (! $product->hasData('product_base_image')) {
												$_prod = Mage::getModel('catalog/product')->load($product->getId());
												
												try {
													if ($image_width || $image_height) {
														$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image')->resize($image_width, $image_height);
													}
													else {
														$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image');
													}
												
												}
												catch (Exception $e) {
													$image_url = '';
												}
												$product->setData('product_base_image', $image_url);
												$attribute_value_tmp = $image_url;
											
											}
											else {
												$attribute_value_tmp = $product->getData('product_base_image');
											}
										
										break;
										case ('thumbnail'):
										case ('small_image'):
											try {
												$_prod = Mage::getModel('catalog/product')->load($product->getId());
												if ($image_width || $image_height) {
													$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $_value_type_attribute['attribute'])->resize($image_width, $image_height);
												}
												else {
													$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $_value_type_attribute['attribute']);
												}
											}
											catch (Exception $e) {
												$image_url = '';
											}
											$attribute_value_tmp = $image_url;
										break;
										case ('url'):
											$attribute_value_tmp = $product->getProductUrl(false);
										break;
										case ('qty'):
											$stock_item = $product->getStockItem();
											if (! ($stock_item && $stock_item->getData('item_id'))) {
												$stock_item = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
											}
											if ($stock_item && $stock_item->getData('item_id')) {
												$product->setStockItem($stock_item);
												$attribute_value_tmp = ceil($stock_item->getQty());
											}
											else {
												$attribute_value_tmp = 0;
											}
										break;
										case ('category'):
											$attribute_value_tmp = $product->getCategory();
										break;
										default:
											if ($value_type_attribute = $attributes->getItemByColumnValue('attribute_code', $_value_type_attribute['attribute'])) {
												if ($value_type_attribute->getFrontendInput() == 'select' || $value_type_attribute->getFrontendInput() == 'multiselect') {
													$attribute_value_tmp = implode(', ', ( array ) $product->getAttributeText($_value_type_attribute['attribute']));
												}
												else {
													$attribute_value_tmp = $product->getData($_value_type_attribute['attribute']);
												}
											}
											else {
												$attribute_value_tmp = $product->getData($_value_type_attribute['attribute']);
											}
										break;
									}
									
									if ($option['value_type'] == 'percent') {
										$attribute_value = $attribute_value_tmp;
										break;
									}
									if ($attribute_value_tmp != '') {
										$attribute_value .= $_value_type_attribute['prefix'] . $attribute_value_tmp;
									}
								
								}
							}
							elseif ($option['value_type'] == 'conf_values') {
								if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
									$conf_values = array();
									$conf_values_attribute = '';
									$conf_values_prefix = '';
									foreach ($option['value_type_attribute'] as $_value_type_attribute) {
										$conf_values_attribute = $_value_type_attribute['attribute'];
										$conf_values_prefix = $_value_type_attribute['prefix'];
										break;
									}
									if ($conf_values_attribute) {
										$conf_attributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
										foreach ($conf_attributes as $conf_attribute) {
											if ($conf_attribute['attribute_code'] == $conf_values_attribute) {
												foreach ($conf_attribute['values'] as $conf_attribute_value) {
													$conf_values[] = $conf_attribute_value['store_label'];
												}
												break;
											}
										}
									}
									if (count($conf_values)) {
										$value = implode($conf_values_prefix, $conf_values);
									}
								}
							}
							
							if ($option['value_type'] == 'percent') {
								$value = floatval($attribute_value) / 100 * floatval($option['value']);
							}
							elseif ($option['value_type'] == 'attribute') {
								$value = $attribute_value;
							}
							elseif ($option['value_type'] == 'static') {
								$value = $option['value'];
							}
							break;
						}
						
						if ($value === null && $custom_attribute->getDefaultValue()) {
							
							switch ($custom_attribute->getDefaultValue()) {
								
								case ('price'):
									if (in_array($product->getTypeId(), array(Mage_Catalog_Model_Product_Type::TYPE_GROUPED, Mage_Catalog_Model_Product_Type::TYPE_BUNDLE)))
										$value = $store->convertPrice($product->getMinimalPrice(), false, false);
									else
										$value = $store->convertPrice($product->getPrice(), false, false);
								break;
								case ('store_price'):
									$value = $store->convertPrice($product->getFinalPrice(), false, false);
								break;
								case ('image'):
								case ('gallery'):
								case ('media_gallery'):
									
									if (! $product->hasData('product_base_image')) {
										$_prod = Mage::getModel('catalog/product')->load($product->getId());
										
										try {
											if ($image_width || $image_height) {
												$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image')->resize($image_width, $image_height);
											}
											else {
												$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, 'image');
											}
										
										}
										catch (Exception $e) {
											$image_url = '';
										}
										$product->setData('product_base_image', $image_url);
										$value = $image_url;
									
									}
									else {
										$value = $product->getData('product_base_image');
									}
								
								break;
								case ('thumbnail'):
								case ('small_image'):
									try {
										$_prod = Mage::getModel('catalog/product')->load($product->getId());
										if ($image_width || $image_height) {
											$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $custom_attribute->getDefaultValue())->resize($image_width, $image_height);
										}
										else {
											$image_url = ( string ) Mage::helper('catalog/image')->init($_prod, $custom_attribute->getDefaultValue());
										}
									}
									catch (Exception $e) {
										$image_url = '';
									}
									$value = $image_url;
								break;
								case ('url'):
									$value = $product->getProductUrl(false);
								break;
								case ('qty'):
									$stock_item = $product->getStockItem();
									if (! ($stock_item && $stock_item->getData('item_id'))) {
										$stock_item = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
									}
									if ($stock_item && $stock_item->getData('item_id')) {
										$product->setStockItem($stock_item);
										$value = ceil($stock_item->getQty());
									}
									else {
										$value = 0;
									}
								break;
								case ('category'):
									$value = $product->getCategory();
								break;
								default:
									if ($custom_attribute->getDefaultValue()) {
										$value = $product->getData($custom_attribute->getDefaultValue());
										if ($defaul_attribute = $attributes->getItemByColumnValue('attribute_code', $custom_attribute->getDefaultValue())) {
											if ($defaul_attribute->getFrontendInput() == 'select' || $defaul_attribute->getFrontendInput() == 'multiselect') {
												$value = implode(', ', ( array ) $product->getAttributeText($custom_attribute->getDefaultValue()));
											}
										}
									}
								break;
							}
						
						}
					
					}
				
				}
				elseif ($attribute = $attributes->getItemByColumnValue('attribute_code', $coll_attribute_value)) {
					
					if ($attribute->getFrontendInput() == 'select' || $attribute->getFrontendInput() == 'multiselect') {
						$value = implode(', ', ( array ) $product->getAttributeText($coll_attribute_value));
					}
					else {
						$value = $product->getData($coll_attribute_value);
					}
				}
				else {
					$value = $product->getData($coll_attribute_value);
				}
			break;
		}
		
		return $value;
	
	}
	
	public function generate() {
		
		$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId(), true);
		$store = $this->getStore();
		
		if (! in_array($store->getWebsiteId(), Mage::helper('gomage_feed')->getAvailavelWebsites())) {
			$generate_info->setError(Mage::helper('gomage_feed')->__('Please activate FeedPro'))->save();
			return false;
		}
		
		$generate_info->start()->save();
		
		Mage::helper('gomage_feed/generator')->setServerConfig($this);
		
		$fileDir = Mage::helper('gomage_feed/generator')->getBaseDir();
		$filePath = $fileDir . DS . $this->getFileNameWithExt();
		
		if (! file_exists($fileDir)) {
			mkdir($fileDir);
			chmod($fileDir, 0777);
		}
		
		if (is_dir($fileDir)) {
			
			if ($this->getType() == 'csv') {
				
				switch ($this->getDelimiter()) {
					case ('comma'):
					default:
						$delimiter = ",";
					break;
					case ('tab'):
						$delimiter = "\t";
					break;
					case ('colon'):
						$delimiter = ":";
					break;
					case ('space'):
						$delimiter = " ";
					break;
					case ('vertical pipe'):
						$delimiter = "|";
					break;
					case ('semi-colon'):
						$delimiter = ";";
					break;
				}
				
				switch ($this->getEnclosure()) {
					case (1):
					default:
						$enclosure = "'";
					break;
					case (2):
						$enclosure = '"';
					break;
					case (3):
						$enclosure = ' ';
					break;
					case (4):
						$enclosure = ' ';
					break;
				}
				
				$collection = $this->getProductsCollection();
				$total_products = $collection->getSize();
				
				$generate_info->setData('total_records', $total_products)->save();
				
				$per_page = intval($this->getIterationLimit());
				
				if ($per_page) {
					$pages = ceil($total_products / $per_page);
				}
				else {
					$pages = 1;
					$per_page = 0;
				}
				
				$this->addLog("started at:" . date("F j, Y, g:i:s a") . ", pages:{$pages}, per_page:{$per_page} \n", true);
				
				for($i = 0; $i < $pages; $i ++) {
					
					$feed_url = $store->getUrl('feed/index/index', array('id' => $this->getId(), 'start' => $i, 'length' => $per_page, '_nosid' => true));
					$contents = '';
					
					if (Mage::getStoreConfig('gomage_feedpro/configuration/system', $store) == GoMage_Feed_Model_Adminhtml_System_Config_Source_System::FOPEN) {
						if ($_fp = fopen($feed_url, 'r')) {
							while (! feof($_fp)) {
								$contents .= fread($_fp, 8192);
							}
							fclose($_fp);
						}
						else {
							$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId());
							$generate_info->setError(Mage::helper('gomage_feed')->__('Cant open temp file'))->save();
							return false;
						}
					}
					else {
						try {
							$contents = Mage::helper('gomage_feed/generator')->getHttpClient()->setUri($feed_url)->request()->getBody();
						}
						catch (Exception $e) {
							$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId());
							$generate_info->setError(Mage::helper('gomage_feed')->__('Cant open temp file'))->save();
							return false;
						}
					
					}
					
					$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId());
					if ($generate_info->getData('stopped')) {
						return false;
					}
					
					$response = Zend_Json::decode($contents);
// 					(var_dump( $response ));
					
					if (! isset($response['result']) || ! $response['result']) {
						
						$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId());
						$generate_info->setError(Mage::helper('gomage_feed')->__('There was an error while generating file. Change "Number of Products" in the Advanced Settings.
                                                Try to change "Number of Products" in the Advanced Settings.
                                                For example: set "Number of Products" equal 50 or 100.'))->save();
						return false;
					
					}
				
				}
				
				$csv_data = @file_get_contents($this->getTempFilePath());
				
				$fp = fopen($filePath, 'w');
				
				if ($this->getData('use_addition_header') == 1) {
					fwrite($fp, $this->getData('addition_header'));
				}
				if ($this->getShowHeaders()) {
					$fields = array();
					$maping = json_decode($this->getContent());
					foreach ($maping as $col) {
						$fields[] = $col->name;
					}
					if ($this->getEnclosure() != 4)
						fputcsv($fp, $fields, $delimiter, $enclosure);
					else
						$this->extended_fputcsv($fp, $fields, $delimiter);
				}
				
				fwrite($fp, $csv_data);
				fclose($fp);
				
				if (file_exists($this->getTempFilePath())) {
					unlink($this->getTempFilePath());
				}
			
			}
			else {
				
				$rootBlock = Mage::getModel('gomage_feed/item_block', array('content' => $this->getContent(), 'feed' => $this));
				@file_put_contents($filePath, $rootBlock->render());
			
			}
		
		}
		
		$generate_info = Mage::helper('gomage_feed/generator')->getGenerateInfo($this->getId());
		$generate_info->finish()->save();
		list ($hour, $min, $sec) = $generate_info->getGenerationTime();
		$this->setData('generated_at', date('Y-m-j H:i:s', time()));
		$this->setData('generation_time', Mage::helper('gomage_feed/generator')->formatGenerationTime($hour, $min, $sec, true));
		$this->save();
	}
	
	public function ftpUpload() {
		
		if (intval($this->getFtpActive())) {
			
			$host_info = explode(':', $this->getFtpHost());
			
			$host = $host_info[0];
			$port = 21;
			
			if (isset($host_info[1])) {
				$port = intval($host_info[1]);
			}
			
			if ($connection = ftp_connect($host, $port)) {
				
				try {
					$ligun_result = ftp_login($connection, $this->getFtpUserName(), $this->getFtpUserPass());
				}
				catch (Exception $e) {
					$ligun_result = false;
				}
				
				if ($ligun_result) {
					if ($this->getFtpPassiveMode()) {
						ftp_pasv($connection, true);
					}
					else {
						ftp_pasv($connection, false);
					}
					
					if (ftp_chdir($connection, $this->getFtpDir())) {
						
						$filePath = sprintf('%s/productsfeed/%s', Mage::getBaseDir('media'), $this->getFileNameWithExt());
						
						if (ftp_put($connection, $this->getFileNameWithExt(), $filePath, FTP_BINARY)) {
							
							$this->setData('uploaded_at', date('Y-m-j H:i:s', time()));
							$this->save();
							
							return true;
						
						}
						else {
							throw new Mage_Core_Exception('Cannot upload file.');
						}
					
					}
					else {
						throw new Mage_Core_Exception('Cannot change dir.');
					}
				
				}
				else {
					throw new Mage_Core_Exception('Authenticate failure.');
				}
			
			}
			else {
				throw new Mage_Core_Exception('Cant connect to host.');
			}
		
		}
		
		return false;
	
	}
	
	public function getUrl() {
		
		$file_path = sprintf('productsfeed/%s', $this->getFileNameWithExt());
		
		if (file_exists(Mage::getBaseDir('media') . '/' . $file_path)) {
			
			return Mage::getBaseUrl('media', false) . $file_path;
		
		}
		
		return '';
	}
	
	public function delete() {
		if ($this->getFileNameWithExt()) {
			$file_path = Mage::helper('gomage_feed/generator')->getBaseDir() . DS . $this->getFileNameWithExt();
			@unlink($file_path);
		}
		return parent::delete();
	}
	
	public function extended_fputcsv($fp, $fields, $delimiter = ';', $enclosure = ' ') {
		for($i = 0; $i < sizeof($fields); $i ++) {
			$use_enclosure = false;
			if (strpos($fields[$i], $delimiter) !== false) {
				$use_enclosure = true;
			}
			if (strpos($fields[$i], $enclosure) !== false) {
				$use_enclosure = true;
			}
			if (strpos($fields[$i], "\\") !== false) {
				$use_enclosure = true;
			}
			if (strpos($fields[$i], "\n") !== false) {
				$use_enclosure = true;
			}
			if (strpos($fields[$i], "\r") !== false) {
				$use_enclosure = true;
			}
			if (strpos($fields[$i], "\t") !== false) {
				$use_enclosure = true;
			}
			if (strpos($fields[$i], " ") !== false) {
				$use_enclosure = true;
			}
			
			if ($use_enclosure == true) {
				$fields[$i] = explode("\$enclosure", $fields[$i]);
				for($j = 0; $j < sizeof($fields[$i]); $j ++) {
					$fields[$i][$j] = explode($enclosure, $fields[$i][$j]);
					$fields[$i][$j] = implode("{$enclosure}", $fields[$i][$j]);
				}
				$fields[$i] = implode("\$enclosure", $fields[$i]);
				$fields[$i] = "{$fields[$i]}";
			}
		}
		
		return fwrite($fp, implode($delimiter, $fields) . "\n");
	}
	
	public function addLog($text = '', $rewrite = false) {
		$log_file = Mage::helper('gomage_feed/generator')->getLogDir() . DS . 'log-' . $this->getId() . '.txt';
		if ($rewrite || ! file_exists($log_file)) {
			@file_put_contents($log_file, $text);
		}
		else {
			$fp = fopen($log_file, 'a');
			fwrite($fp, $text);
			fclose($fp);
		}
	}

}