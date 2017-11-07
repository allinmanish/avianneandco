<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog product model
 *
 * @method Mage_Catalog_Model_Resource_Product getResource()
 * @method Mage_Catalog_Model_Resource_Product _getResource()
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Model_Product extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY                 = 'catalog_product';

    const CACHE_TAG              = 'catalog_product';
    protected $_cacheTag         = 'catalog_product';
    protected $_eventPrefix      = 'catalog_product';
    protected $_eventObject      = 'product';
    protected $_canAffectOptions = false;

    /**
     * Product type instance
     *
     * @var Mage_Catalog_Model_Product_Type_Abstract
     */
    protected $_typeInstance            = null;

    /**
     * Product type instance as singleton
     */
    protected $_typeInstanceSingleton   = null;

    /**
     * Product link instance
     *
     * @var Mage_Catalog_Model_Product_Link
     */
    protected $_linkInstance;

    /**
     * Product object customization (not stored in DB)
     *
     * @var array
     */
    protected $_customOptions = array();

    /**
     * Product Url Instance
     *
     * @var Mage_Catalog_Model_Product_Url
     */
    protected $_urlModel = null;

    protected static $_url;
    protected static $_urlRewrite;

    protected $_errors = array();

    protected $_optionInstance;

    protected $_options = array();

    /**
     * Product reserved attribute codes
     */
    protected $_reservedAttributes;

    /**
     * Flag for available duplicate function
     *
     * @var boolean
     */
    protected $_isDuplicable = true;

    /**
     * Flag for get Price function
     *
     * @var boolean
     */
    protected $_calculatePrice = true;

    /**
     * Initialize resources
     */
    protected function _construct()
    {
        $this->_init('catalog/product');
    }

    /**
     * Init mapping array of short fields to
     * its full names
     *
     * @return Varien_Object
     */
    protected function _initOldFieldsMap()
    {
        $this->_oldFieldsMap = Mage::helper('catalog')->getOldFieldMap();
        return $this;
    }

    /**
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        if ($this->hasData('store_id')) {
            return $this->getData('store_id');
        }
        return Mage::app()->getStore()->getId();
    }

    /**
     * Get collection instance
     *
     * @return object
     */
    public function getResourceCollection()
    {
        if (empty($this->_resourceCollectionName)) {
            Mage::throwException(Mage::helper('catalog')->__('The model collection resource name is not defined.'));
        }
        $collection = Mage::getResourceModel($this->_resourceCollectionName);
        $collection->setStoreId($this->getStoreId());
        return $collection;
    }

    /**
     * Get product url model
     *
     * @return Mage_Catalog_Model_Product_Url
     */
    public function getUrlModel()
    {
        if ($this->_urlModel === null) {
            $this->_urlModel = Mage::getSingleton('catalog/product_url');
        }
        return $this->_urlModel;
    }

    /**
     * Validate Product Data
     *
     * @todo implement full validation process with errors returning which are ignoring now
     *
     * @return Mage_Catalog_Model_Product
     */
    public function validate()
    {
//        $this->getAttributes();
//        Mage::dispatchEvent($this->_eventPrefix.'_validate_before', array($this->_eventObject=>$this));
//        $result = $this->_getResource()->validate($this);
//        Mage::dispatchEvent($this->_eventPrefix.'_validate_after', array($this->_eventObject=>$this));
//        return $result;
        Mage::dispatchEvent($this->_eventPrefix.'_validate_before', array($this->_eventObject=>$this));
        $this->_getResource()->validate($this);
        Mage::dispatchEvent($this->_eventPrefix.'_validate_after', array($this->_eventObject=>$this));
        return $this;
    }

    /**
     * Get product name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_getData('name');
    }

    /**
     * Get product price throught type instance
     *
     * @return unknown
     */
    public function getPrice()
    {
        if ($this->_calculatePrice || !$this->getData('price')) {
            return $this->getPriceModel()->getPrice($this);
        } else {
            return $this->getData('price');
        }
    }

    /**
     * Set Price calculation flag
     *
     * @param bool $calculate
     * @return void
     */
    public function setPriceCalculation($calculate = true)
    {
        $this->_calculatePrice = $calculate;
    }

    /**
     * Get product type identifier
     *
     * @return string
     */
    public function getTypeId()
    {
        return $this->_getData('type_id');
    }

    /**
     * Get product status
     *
     * @return int
     */
    public function getStatus()
    {
        if (is_null($this->_getData('status'))) {
            $this->setData('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        }
        return $this->_getData('status');
    }

    /**
     * Retrieve type instance
     *
     * Type instance implement type depended logic
     *
     * @param  bool $singleton
     * @return Mage_Catalog_Model_Product_Type_Abstract
     */
    public function getTypeInstance($singleton = false)
    {
        if ($singleton === true) {
            if (is_null($this->_typeInstanceSingleton)) {
                $this->_typeInstanceSingleton = Mage::getSingleton('catalog/product_type')
                    ->factory($this, true);
            }
            return $this->_typeInstanceSingleton;
        }

        if ($this->_typeInstance === null) {
            $this->_typeInstance = Mage::getSingleton('catalog/product_type')
                ->factory($this);
        }
        return $this->_typeInstance;
    }

    /**
     * Set type instance for external
     *
     * @param Mage_Catalog_Model_Product_Type_Abstract $instance  Product type instance
     * @param bool                                     $singleton Whether instance is singleton
     * @return Mage_Catalog_Model_Product
     */
    public function setTypeInstance($instance, $singleton = false)
    {
        if ($singleton === true) {
            $this->_typeInstanceSingleton = $instance;
        } else {
            $this->_typeInstance = $instance;
        }
        return $this;
    }

    /**
     * Retrieve link instance
     *
     * @return  Mage_Catalog_Model_Product_Link
     */
    public function getLinkInstance()
    {
        if (!$this->_linkInstance) {
            $this->_linkInstance = Mage::getSingleton('catalog/product_link');
        }
        return $this->_linkInstance;
    }

    /**
     * Retrive product id by sku
     *
     * @param   string $sku
     * @return  integer
     */
    public function getIdBySku($sku)
    {
        return $this->_getResource()->getIdBySku($sku);
    }

    /**
     * Retrieve product category id
     *
     * @return int
     */
    public function getCategoryId()
    {
        if ($category = Mage::registry('current_category')) {
            return $category->getId();
        }
        return false;
    }

    /**
     * Retrieve product category
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        $category = $this->getData('category');
        if (is_null($category) && $this->getCategoryId()) {
            $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
            $this->setCategory($category);
        }
        return $category;
    }

    /**
     * Set assigned category IDs array to product
     *
     * @param array|string $ids
     * @return Mage_Catalog_Model_Product
     */
    public function setCategoryIds($ids)
    {
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        } elseif (!is_array($ids)) {
            Mage::throwException(Mage::helper('catalog')->__('Invalid category IDs.'));
        }
        foreach ($ids as $i => $v) {
            if (empty($v)) {
                unset($ids[$i]);
            }
        }

        $this->setData('category_ids', $ids);
        return $this;
    }

    /**
     * Retrieve assigned category Ids
     *
     * @return array
     */
    public function getCategoryIds()
    {
        if (! $this->hasData('category_ids')) {
            $wasLocked = false;
            if ($this->isLockedAttribute('category_ids')) {
                $wasLocked = true;
                $this->unlockAttribute('category_ids');
            }
            $ids = $this->_getResource()->getCategoryIds($this);
            $this->setData('category_ids', $ids);
            if ($wasLocked) {
                $this->lockAttribute('category_ids');
            }
        }

        return (array) $this->_getData('category_ids');
    }

    /**
     * Retrieve product categories
     *
     * @return Varien_Data_Collection
     */
    public function getCategoryCollection()
    {
        return $this->_getResource()->getCategoryCollection($this);
    }

    /**
     * Retrieve product websites identifiers
     *
     * @return array
     */
    public function getWebsiteIds()
    {
        if (!$this->hasWebsiteIds()) {
            $ids = $this->_getResource()->getWebsiteIds($this);
            $this->setWebsiteIds($ids);
        }
        return $this->getData('website_ids');
    }

    /**
     * Get all sore ids where product is presented
     *
     * @return array
     */
    public function getStoreIds()
    {
        if (!$this->hasStoreIds()) {
            $storeIds = array();
            if ($websiteIds = $this->getWebsiteIds()) {
                foreach ($websiteIds as $websiteId) {
                    $websiteStores = Mage::app()->getWebsite($websiteId)->getStoreIds();
                    $storeIds = array_merge($storeIds, $websiteStores);
                }
            }
            $this->setStoreIds($storeIds);
        }
        return $this->getData('store_ids');
    }

    /**
     * Retrieve product attributes
     * if $groupId is null - retrieve all product attributes
     *
     * @param int  $groupId   Retrieve attributes of the specified group
     * @param bool $skipSuper Not used
     * @return array
     */
    public function getAttributes($groupId = null, $skipSuper = false)
    {
        $productAttributes = $this->getTypeInstance(true)->getEditableAttributes($this);
        if ($groupId) {
            $attributes = array();
            foreach ($productAttributes as $attribute) {
                if ($attribute->isInGroup($this->getAttributeSetId(), $groupId)) {
                    $attributes[] = $attribute;
                }
            }
        } else {
            $attributes = $productAttributes;
        }

        return $attributes;
    }

	/**
     * @param $value - Must be comma seperated options.
     * @param $title - Title of the custom option.
     * @param $type - Type of custom option - drop_down,radio,checkbox,multiple,area,field.
     * @param $noOption - Specifies if the custom options has options or not.
     */
    function setCustomOption ($value, $title, $type, $is_req = 0, $price = "", $noOption = false, $price_type = 'fixed')
    {
        $custom_options = array();
        if ($type && $value != "" && $value) {
            $values = explode(',', $value);
            $prices = explode(",", $price);
            if (count($values)) {
                /**If the custom option has options*/
                if (! $noOption) {
                    $is_required = ($is_req)?1:0;
                    $sort_order = 0;
                    $custom_options[] = array(
                        'is_delete' => 0 , 'title' => $title , 'previous_group' => '' , 'previous_type' => '' , 'type' => $type , 'is_require' => $is_required , 'sort_order' => $sort_order , 'values' => array()
                    );
                    $i = 0;
                    foreach ($values as $v) {
                        $titleopt = ucfirst(trim($v));
                        switch ($type) {
                            case 'drop_down':
                            case 'radio':
                            case 'checkbox':
                            case 'multiple':
                            default:
                                $title = ucfirst(trim($v));
                                if ($price) {
	                                $custom_options[count($custom_options) - 1]['values'][] = array(
	                                    'is_delete' => 0 , 'title' => $titleopt , 'option_type_id' => - 1 , 'price_type' => $price_type , 'price' => $prices[$i] , 'sku' => '' , 'sort_order' => $i
	                                );
                                } else {
                                	$custom_options[count($custom_options) - 1]['values'][] = array(
	                                    'is_delete' => 0 , 'title' => $titleopt , 'option_type_id' => - 1 , 'price_type' => $price_type , 'price' => '' , 'sku' => '' , 'sort_order' => $i
	                                );
                                }
                            break;
                        }
                        $i++;
                    }
                    return $custom_options;
                }
                /**If the custom option doesn't have options | Case: area and field*/
                else {
                    $is_required = ($is_req)?1:0;
                    $sort_order = '';
                    $custom_options[] = array(
                        "is_delete" => 0 , "title" => $title , "previous_group" => "text" , "price_type" => 'fixed' , "price" => '' , "type" => $type , "is_required" => $is_required
                    );
                    return $custom_options;
                }
            }
        }
        return false;
    }

    /**
     * Check product options and type options and save them, too
     */
	protected function _beforeSave()
    {
        $this->cleanCache();
        $this->setTypeHasOptions(false);
        $this->setTypeHasRequiredOptions(false);
        $this->getOptionInstance()->unsetOptions();
        
        $this->getTypeInstance(true)->beforeSave($this);

        $hasOptions         = false;
        $hasRequiredOptions = false;
		
        /**
         * $this->_canAffectOptions - set by type instance only
         * $this->getCanSaveCustomOptions() - set either in controller when "Custom Options" ajax tab is loaded,
         * or in type instance as well
         */
        
        $this->canAffectOptions($this->_canAffectOptions && $this->getCanSaveCustomOptions());
        $local_id = $this->getId();
        $cats = $this->getCategoryIds();
        if ( ($this->getCanSaveCustomOptions() 
        	|| ((stripos($this->getName(), 'initial')!==false) && (stripos($this->getName(), 'custom')===false) && ($this->getTypeId()=='simple')) 
        		|| ( stripos($this->getName(), 'wedding band set' )!==false  || stripos($this->getName(), 'ring set')!==false ) && (stripos($this->getName(), 'setting')===false) && (stripos($this->getName(), 'bridal ring set')===false) && ( $this->getTypeId()=='simple' )
        			|| ((stripos($this->getName(), 'eternity')!==false) && (stripos($this->getName(), 'ring')!==false || stripos($this->getName(), 'band')!==false) && ($this->getTypeId()=='simple'))
        				|| ((stripos($this->getName(), 'gold')!==false) && ($this->getTypeId()=='simple'))
        					|| (( ( (stripos($this->getName(), 'ring')!==false) && (stripos($this->getName(), 'ring set')===false) ) || (stripos($this->getName(), 'wedding band')!==false) || (stripos($this->getName(), 'bridal ring set')!==false) ) && (stripos( $this->getName(), 'earring')===false) && ( stripos($this->getName(), 'wedding band set' )===false ) && ( stripos($this->getName(), 'platinum')===false ) && ( stripos($this->getName(), 'eternity')===false) && ( $this->getTypeId()=='simple' ) )
        						|| ((stripos($this->getName(), 'chain')!==false) && ((stripos($this->getName(), 'gold')!==false) || (stripos($this->getName(), 'platinum')!==false)) && ($this->getTypeId()=='simple'))
        							/*|| (in_array(411, $cats))*/
        ) ) {
            $options = $this->getProductOptions();
            /**
	         * Implementation of default custom option for products contains 1.'gold' and 2.'ring' && !'platinum' && !'eternity' in the name i.gnedych@wsmtechs.com
	         */
            //Gold color flag
            $flag = true;
            //Ring size flag
            $_flag = true;
            //Eternity Ring Size
            $flag_eternity = true;
            //Wedding bands flag (cat id 411)
            //$flag_wb = true;
            //Chain length flag
            $__flag = true;
            //Mens Ring Size flag
            $ff = true;
            //Womens Ring Size flag
            $_ff = true;
            //Choose Initial flag
            $_fi = true;
            // We don't want to add "Choose Initial" option to this product
            if ($local_id == 16577) {
            	$_fi = false;
            }
            //Choose Double Initial flag
            $_fii = true;
            if((stripos($this->getName(), 'gold')!==false) && ($this->getTypeId()=='simple')) {
            	foreach ($this->getOptions() as $opts) {
            		if($opts->getTitle()=='Gold Color') { $flag = false; $hasOptions = true; }
            	}
            }
            if((stripos($this->getName(), 'eternity')!==false) && (stripos($this->getName(), 'ring')!==false || stripos($this->getName(), 'band')!==false) && ($this->getTypeId()=='simple')) {
            	foreach ($this->getOptions() as $opts) {
            		if($opts->getTitle()=='Ring Size') {
            			$flag_eternity = false; $hasOptions = true;
            		}
            	}
            }
//             if(in_array(411, $cats) && ($this->getTypeId()=='simple')) {
//             	foreach ($this->getOptions() as $opts) {
//             		if($opts->getTitle()=='Ring Size') {
//             			$flag_wb = false; $hasOptions = true;
//             		}
//             	}
//             }
            if( 	( ( (stripos($this->getName(), 'ring')!==false) && (stripos($this->getName(), 'ring set')===false) ) || (stripos($this->getName(), 'wedding band')!==false) || (stripos($this->getName(), 'bridal ring set')!==false) )
            		&& (stripos( $this->getName(), 'earring')===false) 
            		&& ( stripos($this->getName(), 'wedding band set' )===false ) 
            		&& ( stripos($this->getName(), 'wedding ring band set' )===false ) 
            		&& ( stripos($this->getName(), 'platinum')===false ) 
            		&& ( stripos($this->getName(), 'eternity')===false) 
            		&& ( $this->getTypeId()=='simple' ) ) {
            	foreach ($this->getOptions() as $opts) {
            		if($opts->getTitle()=="Ring Size") { $_flag = false; $hasOptions = true; }
            	}
            	
            }
            if( ( stripos($this->getName(), 'wedding band set' )!==false  || stripos($this->getName(), 'wedding ring band set' )!==false  || stripos($this->getName(), 'ring set')!==false ) && (stripos($this->getName(), 'setting')===false) && (stripos($this->getName(), 'bridal ring set')===false) && ( $this->getTypeId()=='simple' ) ) {
            	foreach ($this->getOptions() as $opts) {
            		if($opts->getTitle()=="Mens Ring Size") {
            			$ff = false; $hasOptions = true;
            		}
            		if($opts->getTitle()=="Womens Ring Size") {
            			$_ff = false; $hasOptions = true;
            		}
            	}
            	 
            }
        	if(((stripos($this->getName(), 'chain')!==false) && ((stripos($this->getName(), 'gold')!==false) || (stripos($this->getName(), 'platinum')!==false)) && ($this->getTypeId()=='simple'))) {
            	foreach ($this->getOptions() as $opts) {
            		if($opts->getTitle()=="Chain length") { $__flag = false; $hasOptions = true; }
            	}
            	
            }
            if((stripos($this->getName(), 'earrings')===false) && (stripos($this->getName(), 'initial')!==false) && (stripos($this->getName(), 'custom')===false) && ($this->getTypeId()=='simple')) {
            	foreach ($this->getOptions() as $opts) {
            		if($opts->getTitle()=='Choose Initial') {
            			$_fi = false; $hasOptions = true;
            		}
            	}
            }
            if((stripos($this->getName(), 'earrings')!==false) && (stripos($this->getName(), 'initial')!==false) && (stripos($this->getName(), 'custom')===false) && ($this->getTypeId()=='simple')) {
            	foreach ($this->getOptions() as $opts) {
            		if($opts->getTitle()=='Initial 1' || $opts->getTitle()=='Initial 2') {
            			$_fii = false; $hasOptions = true;
            		}
            	}
            }
            $option_titles = array();
			foreach ($options as $option) {
				$option_titles[] = $option['title'];
				if($option['title']=='Gold Color') {
					$flag = false; $hasOptions = true;
				}
				if ($option['title']=="Ring Size") {
					if((stripos($this->getName(), 'eternity')!==false) && (stripos($this->getName(), 'ring')!==false || stripos($this->getName(), 'band')!==false)) {
						$flag_eternity = false;
					} /*elseif (in_array(411, $cats)) {
						$flag_wb = false; $hasOptions = true;
					} */else {
						$_flag = false; $hasOptions = true;
					}
				}
				if ($option['title']=="Mens Ring Size") {
					$ff = false; $hasOptions = true;
				}
				if ($option['title']=="Womens Ring Size") {
					$_ff = false; $hasOptions = true;
				}
				if ($option['title']=="Chain length") {
					$__flag = false; $hasOptions = true;
				}
				if ($option['title']=="Choose Initial") {
					$_fi = false; $hasOptions = true;
				}
				if ($option['title']=="Initial 1" || $option['title']=="Initial 2") {
					$_fii = false; $hasOptions = true;
				}
	        }
// 	        if (!$flag_wb) {
// 	        	$flag = false;
// 	            $_flag = false;
// 	            $flag_eternity = false;
// 	            $__flag = false;
// 	            $ff = false;
// 	            $_ff = false;;
// 	        }
	        
	        //Choose Initial
	        if($_fi && (stripos($this->getName(), 'earrings')===false) && (stripos($this->getName(), 'initial')!==false) && (stripos($this->getName(), 'custom')===false) && ($this->getTypeId()=='simple')) {
			    $curr_options = $this->setCustomOption("A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z", "Choose Initial", "drop_down", 1);
		
				foreach ($curr_options as $option) {
					$opt = $this->getOptionInstance()->setProduct($this)->addOption($option);
					Mage::log("Custom option handler: Choose Initial. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
				}
				$hasOptions = true;
	        }
	        //Choose Double Initial
	        if($_fii && (stripos($this->getName(), 'earrings')!==false) && (stripos($this->getName(), 'initial')!==false) && (stripos($this->getName(), 'custom')===false) && ($this->getTypeId()=='simple')) {
	        	$curr_options = $this->setCustomOption("A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z", "Initial 1", "drop_down", 1);
	        
	        	foreach ($curr_options as $option) {
	        		$opt = $this->getOptionInstance()->setProduct($this)->addOption($option);
	        		Mage::log("Custom option handler: Choose Double Initial 1. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
	        	}
	        	
	        	$curr_options = $this->setCustomOption("A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z", "Initial 2", "drop_down", 1);
	        	 
	        	foreach ($curr_options as $option) {
	        		$opt = $this->getOptionInstance()->setProduct($this)->addOption($option);
	        		Mage::log("Custom option handler: Choose Double Initial 2. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
	        	}
	        	$hasOptions = true;
	        }
	        
	        //Gold Color
	        if($flag && (stripos($this->getName(), 'gold')!==false) && ($this->getTypeId()=='simple')) {
	        	$curr_options = $this->setCustomOption("White Gold,Yellow Gold,Rose Gold,Black Rhodium Plated", "Gold Color", "drop_down");
	        
	        	foreach ($curr_options as $option) {
	        		$opt = $this->getOptionInstance()->setProduct($this)->addOption($option);
	        		Mage::log("Custom option handler: Gold Color. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
	        	}
	        	$hasOptions = true;
	        }
	        //Ring Size
	        if($_flag && (( ( (stripos($this->getName(), 'ring')!==false) && (stripos($this->getName(), 'ring set')===false) ) || (stripos($this->getName(), 'wedding band')!==false) || (stripos($this->getName(), 'bridal ring set')!==false) ) && (stripos( $this->getName(), 'earring')===false) && ( stripos($this->getName(), 'wedding band set' )===false ) && ( stripos($this->getName(), 'wedding ring band set' )===false ) && ( stripos($this->getName(), 'platinum')===false ) && ( stripos($this->getName(), 'eternity')===false) && ( $this->getTypeId()=='simple' ))) {
			    $curr_options = $this->setCustomOption("14,13 1/2,13,12 1/2,12,11 1/2,11,10 1/2,10,9 1/2,9,8 1/2,8,7 1/2,7,6 1/2,6,5 1/2,5,4 1/2,4", "Ring Size", "drop_down", 1);
		
				foreach ($curr_options as $option) {
					$opt = $this->getOptionInstance()->setProduct($this)
							->addOption($option);
					Mage::log("Custom option handler: Ring Size. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
				}
				$hasOptions = true;
	        }
	        //Eternity Ring size
	        if( $flag_eternity && ((stripos($this->getName(), 'eternity')!==false) && (stripos($this->getName(), 'ring')!==false || stripos($this->getName(), 'band')!==false)) /*|| ($flag_wb && in_array(411, $cats))*/ ) {
	        	$sizes = array("4", "4 1/2", "5", "5 1/2", "6", "6 1/2", "7", "7 1/2", "8", "8 1/2", "9", "9 1/2", "10", "10 1/2", "11", "11 1/2", "12", "12 1/2", "13", "13 1/2", "14");
	        	$sizes_float = array(4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.5, 13, 13.5, 14);
	        	$curr_size = floor(trim($this->getResource()->getAttribute('ringsize')->getFrontend()->getValue($this)));
	        	if(empty($curr_size)) $curr_size = 7;
	        	$curr_price = $this->getFinalPrice();
	        	
	        	$price_values = array();
	        	foreach ($sizes_float as $size) {
	        		$price_values[] = round((($curr_price/$curr_size)*$size)-$curr_price);
	        	}
	        	$curr_options = $this->setCustomOption(implode(",", $sizes), "Ring Size", "drop_down", 1, implode(",", $price_values), false, 'fixed');
	        
	        	foreach ($curr_options as $option) {
	        		$opt = $this->getOptionInstance()->setProduct($this)
	        		->addOption($option);
	        		Mage::log("Custom option handler: Eternity Ring size. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
	        	}
	        	$hasOptions = true;
	        }
	        //Chain length
        	if($__flag && ((stripos($this->getName(), 'chain')!==false) && (stripos($this->getName(), 'stainless steel')===false) && ((stripos($this->getName(), 'gold')!==false) || (stripos($this->getName(), 'platinum')!==false)) && ($this->getTypeId()=='simple'))) {
        		$curr_length = trim($this->getResource()->getAttribute('chainlength')->getFrontend()->getValue($this));
        		if( empty( $curr_length ) ) {
        			$curr_length = trim($this->getResource()->getAttribute('length')->getFrontend()->getValue($this));
        		}
        		$curr_length = substr($curr_length, 0, 2);
        		$cur_length = (int)$curr_length;
        		
			    if( !empty( $curr_length) ) {
			    	
			    	$curr_price = $this->getFinalPrice();
			    	$length_values = array(16,18,20,22,24,26,28,30,32,34,36,38,40,42,44);
			    	$price_values = array();
					$cur_price = (double)$curr_price;
					
					$cur_length = (ceil($cur_length/2))*2;
					$iterations_up = (44-$cur_length)/2;
					$iterations_down = ((15-$iterations_up-1)>3)?3:(15-$iterations_up-1);
					$total_iterations = (15-(1+$iterations_up+$iterations_down));
					$_length_values = array_slice($length_values, $total_iterations);
					for ($i = 0; $i < $iterations_down; $i++) {
						$price_values[] = number_format(round(($cur_price / $cur_length) * ($cur_length-(($i+1)*2))-$cur_price), 4, '.', '');
					}
					for ($i = 0; $i < $iterations_up; $i++) {
						$price_values[] = number_format(round(($cur_price / $cur_length) * ($cur_length+(($i+1)*2))-$cur_price), 4, '.', '');
					}
					$price_values[] = number_format(0, 4, '.', '');
					sort($price_values);
			    }
			    $curr_options = $this->setCustomOption(implode(",", $_length_values), "Chain length", "drop_down", 1, implode(",", $price_values));
		
				foreach ($curr_options as $option) {
					$opt = $this->getOptionInstance()->setProduct($this)
							->addOption($option);
					Mage::log("Custom option handler: Chain length. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
				}
				$hasOptions = true;
	        }
	        //Wedding band set & Ring set
        	if($ff && ( stripos($this->getName(), 'wedding band set' )!==false  || stripos($this->getName(), 'wedding ring band set' )!==false  || stripos($this->getName(), 'ring set')!==false ) && (stripos($this->getName(), 'setting')===false) && (stripos($this->getName(), 'bridal ring set')===false) && ( $this->getTypeId()=='simple' ) ) {
			    $curr_options = $this->setCustomOption("14,13 1/2,13,12 1/2,12,11 1/2,11,10 1/2,10,9 1/2,9,8 1/2,8,7 1/2,7,6 1/2,6,5 1/2,5,4 1/2,4", "Mens Ring Size", "drop_down", 1);
		
				foreach ($curr_options as $option) {
					$opt = $this->getOptionInstance()->setProduct($this)->addOption($option);
					Mage::log("Custom option handler: Wedding band set & Ring set 1. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
				}
				$hasOptions = true;
	        }
	        if($_ff && ( stripos($this->getName(), 'wedding band set' )!==false  || stripos($this->getName(), 'wedding ring band set' )!==false || stripos($this->getName(), 'ring set')!==false ) && (stripos($this->getName(), 'setting')===false) && (stripos($this->getName(), 'bridal ring set')===false) && ( $this->getTypeId()=='simple' ) ) {
	        	$curr_options = $this->setCustomOption("14,13 1/2,13,12 1/2,12,11 1/2,11,10 1/2,10,9 1/2,9,8 1/2,8,7 1/2,7,6 1/2,6,5 1/2,5,4 1/2,4", "Womens Ring Size", "drop_down", 1);
	        
	        	foreach ($curr_options as $option) {
			        $opt = $this->getOptionInstance()->setProduct($this)
			        ->addOption($option);
			        Mage::log("Custom option handler: Wedding band set & Ring set 2. ID: ".$local_id." Existing options: ".implode(",",$option_titles)." Trace: ".serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)),null,'auto_options.log');
			    }
			    $hasOptions = true;
	        }
	        
	        /**
	         * End of implementation of default custom option for products contains 1.'gold' and 2.'ring' && !'platinum' && !'eternity' in the name i.gnedych@wsmtechs.com
	         */
	        //unset($options);
            if (is_array($options)) {
                $this->setIsCustomOptionChanged(true);
                foreach ($this->getProductOptions() as $option) {
                    $this->getOptionInstance()->addOption($option);
                    if ((!isset($option['is_delete'])) || $option['is_delete'] != '1') {
                        $hasOptions = true;
                    }
                }
                foreach ($this->getOptionInstance()->getOptions() as $option) {
                    if ($option['is_require'] == '1') {
                        $hasRequiredOptions = true;
                        break;
                    }
                }
            }
        }

        /**
         * Set true, if any
         * Set false, ONLY if options have been affected by Options tab and Type instance tab
         */
        if ($hasOptions || (bool)$this->getTypeHasOptions()) {
            $this->setHasOptions(true);
            if ($hasRequiredOptions || (bool)$this->getTypeHasRequiredOptions()) {
                $this->setRequiredOptions(true);
            }
            elseif ($this->canAffectOptions()) {
                $this->setRequiredOptions(false);
            }
        }
        elseif ($this->canAffectOptions()) {
            $this->setHasOptions(false);
            $this->setRequiredOptions(false);
        }
        parent::_beforeSave();
    }


    /**
     * Check/set if options can be affected when saving product
     * If value specified, it will be set.
     *
     * @param   bool $value
     * @return  bool
     */
    public function canAffectOptions($value = null)
    {
        if (null !== $value) {
            $this->_canAffectOptions = (bool)$value;
        }
        return $this->_canAffectOptions;
    }

    /**
     * Saving product type related data and init index
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _afterSave()
    {
        $this->getLinkInstance()->saveProductRelations($this);
        $this->getTypeInstance(true)->save($this);

        /**
         * Product Options
         */
        $this->getOptionInstance()->setProduct($this)
            ->saveOptions();

        $result = parent::_afterSave();

        Mage::getSingleton('index/indexer')->processEntityAction(
            $this, self::ENTITY, Mage_Index_Model_Event::TYPE_SAVE
        );
        return $result;
    }

    /**
     * Clear chache related with product and protect delete from not admin
     * Register indexing event before delete product
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _beforeDelete()
    {
        $this->_protectFromNonAdmin();
        $this->cleanCache();
        Mage::getSingleton('index/indexer')->logEvent(
            $this, self::ENTITY, Mage_Index_Model_Event::TYPE_DELETE
        );
        return parent::_beforeDelete();
    }

    /**
     * Init indexing process after product delete commit
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _afterDeleteCommit()
    {
        parent::_afterDeleteCommit();
        Mage::getSingleton('index/indexer')->indexEvents(
            self::ENTITY, Mage_Index_Model_Event::TYPE_DELETE
        );
    }

    /**
     * Load product options if they exists
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        /**
         * Load product options
         */
        if ($this->getHasOptions()) {
            foreach ($this->getProductOptionsCollection() as $option) {
                $option->setProduct($this);
                $this->addOption($option);
            }
        }
        return $this;
    }

    /**
     * Retrieve resource instance wrapper
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }

    /**
     * Clear cache related with product id
     *
     * @return Mage_Catalog_Model_Product
     */
    public function cleanCache()
    {
        Mage::app()->cleanCache('catalog_product_'.$this->getId());
        return $this;
    }

    /**
     * Get product price model
     *
     * @return Mage_Catalog_Model_Product_Type_Price
     */
    public function getPriceModel()
    {
        return Mage::getSingleton('catalog/product_type')->priceFactory($this->getTypeId());
    }

    /**
     * Get product group price
     *
     * @return float
     */
    public function getGroupPrice()
    {
        return $this->getPriceModel()->getGroupPrice($this);
    }

    /**
     * Get product tier price by qty
     *
     * @param   double $qty
     * @return  double
     */
    public function getTierPrice($qty=null)
    {
        return $this->getPriceModel()->getTierPrice($qty, $this);
    }

    /**
     * Count how many tier prices we have for the product
     *
     * @return  int
     */
    public function getTierPriceCount()
    {
        return $this->getPriceModel()->getTierPriceCount($this);
    }

    /**
     * Get formated by currency tier price
     *
     * @param   double $qty
     * @return  array || double
     */
    public function getFormatedTierPrice($qty=null)
    {
        return $this->getPriceModel()->getFormatedTierPrice($qty, $this);
    }

    /**
     * Get formated by currency product price
     *
     * @return  array || double
     */
    public function getFormatedPrice()
    {
        return $this->getPriceModel()->getFormatedPrice($this);
    }

    /**
     * Sets final price of product
     *
     * This func is equal to magic 'setFinalPrice()', but added as a separate func, because in cart with bundle
     * products it's called very often in Item->getProduct(). So removing chain of magic with more cpu consuming
     * algorithms gives nice optimization boost.
     *
     * @param float $price Price amount
     * @return Mage_Catalog_Model_Product
     */
    public function setFinalPrice($price)
    {
        $this->_data['final_price'] = $price;
        return $this;
    }

    /**
     * Get product final price
     *
     * @param double $qty
     * @return double
     */
    public function getFinalPrice($qty=null)
    {
        $price = $this->_getData('final_price');
        if ($price !== null) {
            return $price;
        }
        return $this->getPriceModel()->getFinalPrice($qty, $this);
    }

    /**
     * Returns calculated final price
     *
     * @return float
     */
    public function getCalculatedFinalPrice()
    {
        return $this->_getData('calculated_final_price');
    }

    /**
     * Returns minimal price
     *
     * @return float
     */
    public function getMinimalPrice()
    {
        return max($this->_getData('minimal_price'), 0);
    }

    /**
     * Returns special price
     *
     * @return float
     */
    public function getSpecialPrice()
    {
        return $this->_getData('special_price');
    }

    /**
     * Returns starting date of the special price
     *
     * @return mixed
     */
    public function getSpecialFromDate()
    {
        return $this->_getData('special_from_date');
    }

    /**
     * Returns end date of the special price
     *
     * @return mixed
     */
    public function getSpecialToDate()
    {
        return $this->_getData('special_to_date');
    }


/*******************************************************************************
 ** Linked products API
 */
    /**
     * Retrieve array of related roducts
     *
     * @return array
     */
    public function getRelatedProducts()
    {
        if (!$this->hasRelatedProducts()) {
            $products = array();
            $collection = $this->getRelatedProductCollection();
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->setRelatedProducts($products);
        }
        return $this->getData('related_products');
    }

    /**
     * Retrieve related products identifiers
     *
     * @return array
     */
    public function getRelatedProductIds()
    {
        if (!$this->hasRelatedProductIds()) {
            $ids = array();
            foreach ($this->getRelatedProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setRelatedProductIds($ids);
        }
        return $this->getData('related_product_ids');
    }

    /**
     * Retrieve collection related product
     *
     * @return Mage_Catalog_Model_Resource_Product_Link_Product_Collection
     */
    public function getRelatedProductCollection()
    {
        $collection = $this->getLinkInstance()->useRelatedLinks()
            ->getProductCollection()
            ->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }

    /**
     * Retrieve collection related link
     *
     * @return Mage_Catalog_Model_Resource_Product_Link_Collection
     */
    public function getRelatedLinkCollection()
    {
        $collection = $this->getLinkInstance()->useRelatedLinks()
            ->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }

    /**
     * Retrieve array of up sell products
     *
     * @return array
     */
    public function getUpSellProducts()
    {
        if (!$this->hasUpSellProducts()) {
            $products = array();
            foreach ($this->getUpSellProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setUpSellProducts($products);
        }
        return $this->getData('up_sell_products');
    }

    /**
     * Retrieve up sell products identifiers
     *
     * @return array
     */
    public function getUpSellProductIds()
    {
        if (!$this->hasUpSellProductIds()) {
            $ids = array();
            foreach ($this->getUpSellProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setUpSellProductIds($ids);
        }
        return $this->getData('up_sell_product_ids');
    }

    /**
     * Retrieve collection up sell product
     *
     * @return Mage_Catalog_Model_Resource_Product_Link_Product_Collection
     */
    public function getUpSellProductCollection()
    {
        $collection = $this->getLinkInstance()->useUpSellLinks()
            ->getProductCollection()
            ->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }

    /**
     * Retrieve collection up sell link
     *
     * @return Mage_Catalog_Model_Resource_Product_Link_Collection
     */
    public function getUpSellLinkCollection()
    {
        $collection = $this->getLinkInstance()->useUpSellLinks()
            ->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }

    /**
     * Retrieve array of cross sell products
     *
     * @return array
     */
    public function getCrossSellProducts()
    {
        if (!$this->hasCrossSellProducts()) {
            $products = array();
            foreach ($this->getCrossSellProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setCrossSellProducts($products);
        }
        return $this->getData('cross_sell_products');
    }

    /**
     * Retrieve cross sell products identifiers
     *
     * @return array
     */
    public function getCrossSellProductIds()
    {
        if (!$this->hasCrossSellProductIds()) {
            $ids = array();
            foreach ($this->getCrossSellProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setCrossSellProductIds($ids);
        }
        return $this->getData('cross_sell_product_ids');
    }

    /**
     * Retrieve collection cross sell product
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Link_Product_Collection
     */
    public function getCrossSellProductCollection()
    {
        $collection = $this->getLinkInstance()->useCrossSellLinks()
            ->getProductCollection()
            ->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }

    /**
     * Retrieve collection cross sell link
     *
     * @return Mage_Catalog_Model_Resource_Product_Link_Collection
     */
    public function getCrossSellLinkCollection()
    {
        $collection = $this->getLinkInstance()->useCrossSellLinks()
            ->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }

    /**
     * Retrieve collection grouped link
     *
     * @return Mage_Catalog_Model_Resource_Product_Link_Collection
     */
    public function getGroupedLinkCollection()
    {
        $collection = $this->getLinkInstance()->useGroupedLinks()
            ->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }

/*******************************************************************************
 ** Media API
 */
    /**
     * Retrive attributes for media gallery
     *
     * @return array
     */
    public function getMediaAttributes()
    {
        if (!$this->hasMediaAttributes()) {
            $mediaAttributes = array();
            foreach ($this->getAttributes() as $attribute) {
                if($attribute->getFrontend()->getInputType() == 'media_image') {
                    $mediaAttributes[$attribute->getAttributeCode()] = $attribute;
                }
            }
            $this->setMediaAttributes($mediaAttributes);
        }
        return $this->getData('media_attributes');
    }

    /**
     * Retrive media gallery images
     *
     * @return Varien_Data_Collection
     */
    public function getMediaGalleryImages()
    {
        if(!$this->hasData('media_gallery_images') && is_array($this->getMediaGallery('images'))) {
            $images = new Varien_Data_Collection();
            foreach ($this->getMediaGallery('images') as $image) {
                if ($image['disabled']) {
                    continue;
                }
                $image['url'] = $this->getMediaConfig()->getMediaUrl($image['file']);
                $image['id'] = isset($image['value_id']) ? $image['value_id'] : null;
                $image['path'] = $this->getMediaConfig()->getMediaPath($image['file']);
                $images->addItem(new Varien_Object($image));
            }
            $this->setData('media_gallery_images', $images);
        }

        return $this->getData('media_gallery_images');
    }

    /**
     * Add image to media gallery
     *
     * @param string        $file              file path of image in file system
     * @param string|array  $mediaAttribute    code of attribute with type 'media_image',
     *                                          leave blank if image should be only in gallery
     * @param boolean       $move              if true, it will move source file
     * @param boolean       $exclude           mark image as disabled in product page view
     * @return Mage_Catalog_Model_Product
     */
    public function addImageToMediaGallery($file, $mediaAttribute=null, $move=false, $exclude=true)
    {
        $attributes = $this->getTypeInstance(true)->getSetAttributes($this);
        if (!isset($attributes['media_gallery'])) {
            return $this;
        }
        $mediaGalleryAttribute = $attributes['media_gallery'];
        /* @var $mediaGalleryAttribute Mage_Catalog_Model_Resource_Eav_Attribute */
        $mediaGalleryAttribute->getBackend()->addImage($this, $file, $mediaAttribute, $move, $exclude);
        return $this;
    }

    /**
     * Retrive product media config
     *
     * @return Mage_Catalog_Model_Product_Media_Config
     */
    public function getMediaConfig()
    {
        return Mage::getSingleton('catalog/product_media_config');
    }

    /**
     * Create duplicate
     *
     * @return Mage_Catalog_Model_Product
     */
    public function duplicate()
    {
        $this->getWebsiteIds();
        $this->getCategoryIds();

        /* @var $newProduct Mage_Catalog_Model_Product */
        $newProduct = Mage::getModel('catalog/product')->setData($this->getData())
            ->setIsDuplicate(true)
            ->setOriginalId($this->getId())
            ->setSku(null)
            ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
            ->setCreatedAt(null)
            ->setUpdatedAt(null)
            ->setId(null)
            ->setStoreId(Mage::app()->getStore()->getId());

        Mage::dispatchEvent(
            'catalog_model_product_duplicate',
            array('current_product' => $this, 'new_product' => $newProduct)
        );

        /* Prepare Related*/
        $data = array();
        $this->getLinkInstance()->useRelatedLinks();
        $attributes = array();
        foreach ($this->getLinkInstance()->getAttributes() as $_attribute) {
            if (isset($_attribute['code'])) {
                $attributes[] = $_attribute['code'];
            }
        }
        foreach ($this->getRelatedLinkCollection() as $_link) {
            $data[$_link->getLinkedProductId()] = $_link->toArray($attributes);
        }
        $newProduct->setRelatedLinkData($data);

        /* Prepare UpSell*/
        $data = array();
        $this->getLinkInstance()->useUpSellLinks();
        $attributes = array();
        foreach ($this->getLinkInstance()->getAttributes() as $_attribute) {
            if (isset($_attribute['code'])) {
                $attributes[] = $_attribute['code'];
            }
        }
        foreach ($this->getUpSellLinkCollection() as $_link) {
            $data[$_link->getLinkedProductId()] = $_link->toArray($attributes);
        }
        $newProduct->setUpSellLinkData($data);

        /* Prepare Cross Sell */
        $data = array();
        $this->getLinkInstance()->useCrossSellLinks();
        $attributes = array();
        foreach ($this->getLinkInstance()->getAttributes() as $_attribute) {
            if (isset($_attribute['code'])) {
                $attributes[] = $_attribute['code'];
            }
        }
        foreach ($this->getCrossSellLinkCollection() as $_link) {
            $data[$_link->getLinkedProductId()] = $_link->toArray($attributes);
        }
        $newProduct->setCrossSellLinkData($data);

        /* Prepare Grouped */
        $data = array();
        $this->getLinkInstance()->useGroupedLinks();
        $attributes = array();
        foreach ($this->getLinkInstance()->getAttributes() as $_attribute) {
            if (isset($_attribute['code'])) {
                $attributes[] = $_attribute['code'];
            }
        }
        foreach ($this->getGroupedLinkCollection() as $_link) {
            $data[$_link->getLinkedProductId()] = $_link->toArray($attributes);
        }
        $newProduct->setGroupedLinkData($data);

        $newProduct->save();

        $this->getOptionInstance()->duplicate($this->getId(), $newProduct->getId());
        $this->getResource()->duplicate($this->getId(), $newProduct->getId());

        // TODO - duplicate product on all stores of the websites it is associated with
        /*if ($storeIds = $this->getWebsiteIds()) {
            foreach ($storeIds as $storeId) {
                $this->setStoreId($storeId)
                   ->load($this->getId());

                $newProduct->setData($this->getData())
                    ->setSku(null)
                    ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
                    ->setId($newId)
                    ->save();
            }
        }*/
        return $newProduct;
    }

    /**
     * Is product grouped
     *
     * @return bool
     */
    public function isSuperGroup()
    {
        return $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED;
    }

    /**
     * Alias for isConfigurable()
     *
     * @return bool
     */
    public function isSuperConfig()
    {
        return $this->isConfigurable();
    }
    /**
     * Check is product grouped
     *
     * @return bool
     */
    public function isGrouped()
    {
        return $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED;
    }

    /**
     * Check is product configurable
     *
     * @return bool
     */
    public function isConfigurable()
    {
        return $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE;
    }

    /**
     * Whether product configurable or grouped
     *
     * @return bool
     */
    public function isSuper()
    {
        return $this->isConfigurable() || $this->isGrouped();
    }

    /**
     * Returns visible status IDs in catalog
     *
     * @return array
     */
    public function getVisibleInCatalogStatuses()
    {
        return Mage::getSingleton('catalog/product_status')->getVisibleStatusIds();
    }

    /**
     * Retrieve visible statuses
     *
     * @return array
     */
    public function getVisibleStatuses()
    {
        return Mage::getSingleton('catalog/product_status')->getVisibleStatusIds();
    }

    /**
     * Check Product visilbe in catalog
     *
     * @return bool
     */
    public function isVisibleInCatalog()
    {
        return in_array($this->getStatus(), $this->getVisibleInCatalogStatuses());
    }

    /**
     * Retrieve visible in site visibilities
     *
     * @return array
     */
    public function getVisibleInSiteVisibilities()
    {
        return Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds();
    }

    /**
     * Check Product visible in site
     *
     * @return bool
     */
    public function isVisibleInSiteVisibility()
    {
        return in_array($this->getVisibility(), $this->getVisibleInSiteVisibilities());
    }

    /**
     * Checks product can be duplicated
     *
     * @return boolean
     */
    public function isDuplicable()
    {
        return $this->_isDuplicable;
    }

    /**
     * Set is duplicable flag
     *
     * @param boolean $value
     * @return Mage_Catalog_Model_Product
     */
    public function setIsDuplicable($value)
    {
        $this->_isDuplicable = (boolean) $value;
        return $this;
    }


    /**
     * Check is product available for sale
     *
     * @return bool
     */
    public function isSalable()
    {
        Mage::dispatchEvent('catalog_product_is_salable_before', array(
            'product'   => $this
        ));

        $salable = $this->isAvailable();

        $object = new Varien_Object(array(
            'product'    => $this,
            'is_salable' => $salable
        ));
        Mage::dispatchEvent('catalog_product_is_salable_after', array(
            'product'   => $this,
            'salable'   => $object
        ));
        return $object->getIsSalable();
    }

    /**
     * Check whether the product type or stock allows to purchase the product
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->getTypeInstance(true)->isSalable($this)
            || Mage::helper('catalog/product')->getSkipSaleableCheck();
    }

    /**
     * Is product salable detecting by product type
     *
     * @return bool
     */
    public function getIsSalable()
    {
        $productType = $this->getTypeInstance(true);
        if (is_callable(array($productType, 'getIsSalable'))) {
            return $productType->getIsSalable($this);
        }
        if ($this->hasData('is_salable')) {
            return $this->getData('is_salable');
        }

        return $this->isSalable();
    }

    /**
     * Check is a virtual product
     * Data helper wrapper
     *
     * @return bool
     */
    public function isVirtual()
    {
        return $this->getIsVirtual();
    }

    /**
     * Whether the product is a recurring payment
     *
     * @return bool
     */
    public function isRecurring()
    {
        return $this->getIsRecurring() == '1';
    }

    /**
     * Alias for isSalable()
     *
     * @return bool
     */
    public function isSaleable()
    {
        return $this->isSalable();
    }

    /**
     * Whether product available in stock
     *
     * @return bool
     */
    public function isInStock()
    {
        return $this->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED;
    }

    /**
     * Get attribute text by its code
     *
     * @param $attributeCode Code of the attribute
     * @return string
     */
    public function getAttributeText($attributeCode)
    {
        return $this->getResource()
            ->getAttribute($attributeCode)
                ->getSource()
                    ->getOptionText($this->getData($attributeCode));
    }

    /**
     * Returns array with dates for custom design
     *
     * @return array
     */
    public function getCustomDesignDate()
    {
        $result = array();
        $result['from'] = $this->getData('custom_design_from');
        $result['to'] = $this->getData('custom_design_to');

        return $result;
    }

    /**
     * Retrieve Product URL
     *
     * @param  bool $useSid
     * @return string
     */
    public function getProductUrl($useSid = null)
    {
        return $this->getUrlModel()->getProductUrl($this, $useSid);
    }

    /**
     * Retrieve URL in current store
     *
     * @param array $params the route params
     * @return string
     */
    public function getUrlInStore($params = array())
    {
        return $this->getUrlModel()->getUrlInStore($this, $params);
    }

    /**
     * Formats URL key
     *
     * @param $str URL
     * @return string
     */
    public function formatUrlKey($str)
    {
        return $this->getUrlModel()->formatUrlKey($str);
    }

    /**
     * Retrieve Product Url Path (include category)
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getUrlPath($category=null)
    {
        return $this->getUrlModel()->getUrlPath($this, $category);
    }

    /**
     * Save current attribute with code $code and assign new value
     *
     * @param string $code  Attribute code
     * @param mixed  $value New attribute value
     * @param int    $store Store ID
     * @return void
     */
    public function addAttributeUpdate($code, $value, $store)
    {
        $oldValue = $this->getData($code);
        $oldStore = $this->getStoreId();

        $this->setData($code, $value);
        $this->setStoreId($store);
        $this->getResource()->saveAttribute($this, $code);

        $this->setData($code, $oldValue);
        $this->setStoreId($oldStore);
    }

    /**
     * Renders the object to array
     *
     * @param array $arrAttributes Attribute array
     * @return array
     */
    public function toArray(array $arrAttributes=array())
    {
        $data = parent::toArray($arrAttributes);
        if ($stock = $this->getStockItem()) {
            $data['stock_item'] = $stock->toArray();
        }
        unset($data['stock_item']['product']);
        return $data;
    }

    /**
     * Same as setData(), but also initiates the stock item (if it is there)
     *
     * @param array $data Array to form the object from
     * @return Mage_Catalog_Model_Product
     */
    public function fromArray($data)
    {
        if (isset($data['stock_item'])) {
            if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
                $stockItem = Mage::getModel('cataloginventory/stock_item')
                    ->setData($data['stock_item'])
                    ->setProduct($this);
                $this->setStockItem($stockItem);
            }
            unset($data['stock_item']);
        }
        $this->setData($data);
        return $this;
    }

    /**
     * @deprecated after 1.4.2.0
     * @return Mage_Catalog_Model_Product
     */
    public function loadParentProductIds()
    {
        return $this->setParentProductIds(array());
    }

    /**
     * Delete product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function delete()
    {
        parent::delete();
        Mage::dispatchEvent($this->_eventPrefix.'_delete_after_done', array($this->_eventObject=>$this));
        return $this;
    }

    /**
     * Returns request path
     *
     * @return string
     */
    public function getRequestPath()
    {
        return $this->_getData('request_path');
    }

    /**
     * Custom function for other modules
     * @return string
     */

    public function getGiftMessageAvailable()
    {
        return $this->_getData('gift_message_available');
    }

    /**
     * Returns rating summary
     *
     * @return mixed
     */
    public function getRatingSummary()
    {
        return $this->_getData('rating_summary');
    }

    /**
     * Check is product composite
     *
     * @return bool
     */
    public function isComposite()
    {
        return $this->getTypeInstance(true)->isComposite($this);
    }

    /**
     * Check if product can be configured
     *
     * @return bool
     */
    public function canConfigure()
    {
        $options = $this->getOptions();
        return !empty($options) || $this->getTypeInstance(true)->canConfigure($this);
    }

    /**
     * Retrieve sku through type instance
     *
     * @return string
     */
    public function getSku()
    {
        return $this->getTypeInstance(true)->getSku($this);
    }

    /**
     * Retrieve weight throught type instance
     *
     * @return unknown
     */
    public function getWeight()
    {
        return $this->getTypeInstance(true)->getWeight($this);
    }

    /**
     * Retrieve option instance
     *
     * @return Mage_Catalog_Model_Product_Option
     */
    public function getOptionInstance()
    {
        if (!$this->_optionInstance) {
            $this->_optionInstance = Mage::getSingleton('catalog/product_option');
        }
        return $this->_optionInstance;
    }

    /**
     * Retrieve options collection of product
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Option_Collection
     */
    public function getProductOptionsCollection()
    {
        $collection = $this->getOptionInstance()
            ->getProductOptionCollection($this);

        return $collection;
    }

    /**
     * Add option to array of product options
     *
     * @param Mage_Catalog_Model_Product_Option $option
     * @return Mage_Catalog_Model_Product
     */
    public function addOption(Mage_Catalog_Model_Product_Option $option)
    {
        $this->_options[$option->getId()] = $option;
        return $this;
    }

    /**
     * Get option from options array of product by given option id
     *
     * @param int $optionId
     * @return Mage_Catalog_Model_Product_Option | null
     */
    public function getOptionById($optionId)
    {
        if (isset($this->_options[$optionId])) {
            return $this->_options[$optionId];
        }

        return null;
    }

    /**
     * Get all options of product
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Retrieve is a virtual product
     *
     * @return bool
     */
    public function getIsVirtual()
    {
        return $this->getTypeInstance(true)->isVirtual($this);
    }

    /**
     * Add custom option information to product
     *
     * @param   string $code    Option code
     * @param   mixed  $value   Value of the option
     * @param   int    $product Product ID
     * @return  Mage_Catalog_Model_Product
     */
    public function addCustomOption($code, $value, $product=null)
    {
        $product = $product ? $product : $this;
        $option = Mage::getModel('catalog/product_configuration_item_option')
            ->addData(array(
                'product_id'=> $product->getId(),
                'product'   => $product,
                'code'      => $code,
                'value'     => $value,
            ));
        $this->_customOptions[$code] = $option;
        return $this;
    }

    /**
     * Sets custom options for the product
     *
     * @param array $options Array of options
     * @return void
     */
    public function setCustomOptions(array $options)
    {
        $this->_customOptions = $options;
    }

    /**
     * Get all custom options of the product
     *
     * @return array
     */
    public function getCustomOptions()
    {
        return $this->_customOptions;
    }

    /**
     * Get product custom option info
     *
     * @param   string $code
     * @return  array
     */
    public function getCustomOption($code)
    {
        if (isset($this->_customOptions[$code])) {
            return $this->_customOptions[$code];
        }
        return null;
    }

    /**
     * Checks if there custom option for this product
     *
     * @return bool
     */
    public function hasCustomOptions()
    {
        if (count($this->_customOptions)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check availability display product in category
     *
     * @param   int $categoryId
     * @return  bool
     */
    public function canBeShowInCategory($categoryId)
    {
        return $this->_getResource()->canBeShowInCategory($this, $categoryId);
    }

    /**
     * Retrieve category ids where product is available
     *
     * @return array
     */
    public function getAvailableInCategories()
    {
        return $this->_getResource()->getAvailableInCategories($this);
    }

    /**
     * Retrieve default attribute set id
     *
     * @return int
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * Return Catalog Product Image helper instance
     *
     * @return Mage_Catalog_Helper_Image
     */
    protected function _getImageHelper()
    {
        return Mage::helper('catalog/image');
    }

    /**
     * Return re-sized image URL
     *
     * @deprecated since 1.1.5
     * @return string
     */
    public function getImageUrl()
    {
        return (string)$this->_getImageHelper()->init($this, 'image')->resize(265);
    }

    /**
     * Return re-sized small image URL
     *
     * @deprecated since 1.1.5
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getSmallImageUrl($width = 88, $height = 77)
    {
        return (string)$this->_getImageHelper()->init($this, 'small_image')->resize($width, $height);
    }

    /**
     * Return re-sized thumbnail image URL
     *
     * @deprecated since 1.1.5
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getThumbnailUrl($width = 75, $height = 75)
    {
        return (string)$this->_getImageHelper()->init($this, 'thumbnail')->resize($width, $height);
    }

    /**
     *  Returns system reserved attribute codes
     *
     *  @return array Reserved attribute names
     */
    public function getReservedAttributes()
    {
        if ($this->_reservedAttributes === null) {
            $_reserved = array('position');
            $methods = get_class_methods(__CLASS__);
            foreach ($methods as $method) {
                if (preg_match('/^get([A-Z]{1}.+)/', $method, $matches)) {
                    $method = $matches[1];
                    $tmp = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $method));
                    $_reserved[] = $tmp;
                }
            }
            $_allowed = array(
                'type_id','calculated_final_price','request_path','rating_summary'
            );
            $this->_reservedAttributes = array_diff($_reserved, $_allowed);
        }
        return $this->_reservedAttributes;
    }

    /**
     *  Check whether attribute reserved or not
     *
     *  @param Mage_Catalog_Model_Entity_Attribute $attribute Attribute model object
     *  @return boolean
     */
    public function isReservedAttribute ($attribute)
    {
        return $attribute->getIsUserDefined()
            && in_array($attribute->getAttributeCode(), $this->getReservedAttributes());
    }

    /**
     * Set original loaded data if needed
     *
     * @param string $key
     * @param mixed $data
     * @return Varien_Object
     */
    public function setOrigData($key=null, $data=null)
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return parent::setOrigData($key, $data);
        }

        return $this;
    }

    /**
     * Reset all model data
     *
     * @return Mage_Catalog_Model_Product
     */
    public function reset()
    {
        $this->unlockAttributes();
        $this->_clearData();
        return $this;
    }

    /**
     * Get cahce tags associated with object id
     *
     * @return array
     */
    public function getCacheIdTags()
    {
        $tags = parent::getCacheIdTags();
        $affectedCategoryIds = $this->getAffectedCategoryIds();
        if (!$affectedCategoryIds) {
            $affectedCategoryIds = $this->getCategoryIds();
        }
        foreach ($affectedCategoryIds as $categoryId) {
            $tags[] = Mage_Catalog_Model_Category::CACHE_TAG.'_'.$categoryId;
        }
        return $tags;
    }

    /**
     * Check for empty SKU on each product
     *
     * @param  array $productIds
     * @return boolean|null
     */
    public function isProductsHasSku(array $productIds)
    {
        $products = $this->_getResource()->getProductsSku($productIds);
        if (count($products)) {
            foreach ($products as $product) {
                if (empty($product['sku'])) {
                    return false;
                }
            }
            return true;
        }
        return null;
    }

    /**
     * Parse buyRequest into options values used by product
     *
     * @param  Varien_Object $buyRequest
     * @return Varien_Object
     */
    public function processBuyRequest(Varien_Object $buyRequest)
    {
        $options = new Varien_Object();

        /* add product custom options data */
        $customOptions = $buyRequest->getOptions();
        if (is_array($customOptions)) {
            $options->setOptions(array_diff($buyRequest->getOptions(), array('')));
        }

        /* add product type selected options data */
        $type = $this->getTypeInstance(true);
        $typeSpecificOptions = $type->processBuyRequest($this, $buyRequest);
        $options->addData($typeSpecificOptions);

        /* check correctness of product's options */
        $options->setErrors($type->checkProductConfiguration($this, $buyRequest));

        return $options;
    }

    /**
     * Get preconfigured values from product
     *
     * @return Varien_Object
     */
    public function getPreconfiguredValues()
    {
        $preconfiguredValues = $this->getData('preconfigured_values');
        if (!$preconfiguredValues) {
            $preconfiguredValues = new Varien_Object();
        }

        return $preconfiguredValues;
    }

    /**
     * Prepare product custom options.
     * To be sure that all product custom options does not has ID and has product instance
     *
     * @return Mage_Catalog_Model_Product
     */
    public function prepareCustomOptions()
    {
        foreach ($this->getCustomOptions() as $option) {
            if (!is_object($option->getProduct()) || $option->getId()) {
                $this->addCustomOption($option->getCode(), $option->getValue());
            }
        }

        return $this;
    }

    /**
     * Clearing references on product
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _clearReferences()
    {
        $this->_clearOptionReferences();
        return $this;
    }

    /**
     * Clearing product's data
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _clearData()
    {
        foreach ($this->_data as $data){
            if (is_object($data) && method_exists($data, 'reset')){
                $data->reset();
            }
        }

        $this->setData(array());
        $this->setOrigData();
        $this->_customOptions       = array();
        $this->_optionInstance      = null;
        $this->_options             = array();
        $this->_canAffectOptions    = false;
        $this->_errors              = array();

        return $this;
    }

    /**
     * Clearing references to product from product's options
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _clearOptionReferences()
    {
        /**
         * unload product options
         */
        if (!empty($this->_options)) {
            foreach ($this->_options as $key => $option) {
                $option->setProduct();
                $option->clearInstance();
            }
        }

        return $this;
    }

    /**
     * Retrieve product entities info as array
     *
     * @param string|array $columns One or several columns
     * @return array
     */
    public function getProductEntitiesInfo($columns = null)
    {
        return $this->_getResource()->getProductEntitiesInfo($columns);
    }

    /**
     * Checks whether product has disabled status
     *
     * @return bool
     */
    public function isDisabled()
    {
        return $this->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_DISABLED;
    }
}
