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
 * @since        Class available since Release 2.0
 */

class GoMage_Feed_Model_Product_Collection extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
{
    
    protected $_feed_categories = array();
    protected $_prepared_feed_categories = array(
    										'inclusion' => array(),
											'exclusion' => array());    
    protected $_filter_type = GoMage_Feed_Model_Adminhtml_System_Config_Source_Filter::TOGETHER;
    
    public function isEnabledFlat()
    {        
        return false;
    }
    
    protected function _productLimitationJoinPrice()
    {
        $filters = $this->_productLimitationFilters;
        if (empty($filters['use_price_index'])) {
            return $this;
        }

        $connection = $this->getConnection();

        $joinCond = $joinCond = join(' AND ', array(
            'price_index.entity_id = e.entity_id',
            $connection->quoteInto('price_index.website_id = ?', $filters['website_id']),
            $connection->quoteInto('price_index.customer_group_id = ?', $filters['customer_group_id'])
        ));

        $fromPart = $this->getSelect()->getPart(Zend_Db_Select::FROM);
        if (!isset($fromPart['price_index'])) {
            $minimalExpr = new Zend_Db_Expr(
                'IF(`price_index`.`tier_price`, LEAST(`price_index`.`min_price`, `price_index`.`tier_price`), `price_index`.`min_price`)'
            );
            $this->getSelect()->joinLeft(
                array('price_index' => $this->getTable('catalog/product_index_price')),
                $joinCond,
                array('price', 'tax_class_id', 'final_price', 'minimal_price'=>$minimalExpr , 'min_price', 'max_price', 'tier_price')
            );

            // Set additional field filters
            if (isset($this->_priceDataFieldFilters) && is_array($this->_priceDataFieldFilters))
            {
                foreach ($this->_priceDataFieldFilters as $filterData) {
                    $this->getSelect()->where(call_user_func_array('sprintf', $filterData));
                }
            }
        } else {
            $fromPart['price_index']['joinCondition'] = $joinCond;
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
        }

        return $this;
    }
    
    protected function _applyProductLimitations()
    {
        $this->_prepareProductLimitationFilters();
        $this->_productLimitationJoinWebsite();
        $this->_productLimitationJoinPrice();
        $filters = $this->_productLimitationFilters;

        if (!(isset($filters['category_id']) || count($this->_feed_categories)) && !isset($filters['visibility'])) {
            return $this;
        }

        $conditions = array(
            'cat_index.product_id=e.entity_id',
            $this->getConnection()->quoteInto('cat_index.store_id=?', $filters['store_id'])
        );
        if (isset($filters['visibility']) && !isset($filters['store_table'])) {
            $conditions[] = $this->getConnection()
                ->quoteInto('cat_index.visibility IN(?)', $filters['visibility']);
        }
        
        if (count($this->_feed_categories)){        	
        	if ($this->_filter_type == GoMage_Feed_Model_Adminhtml_System_Config_Source_Filter::TOGETHER){        	
            	$conditions[] = $this->getConnection()->quoteInto('cat_index.category_id IN(?)', $this->_feed_categories);
        	}    
            $this->getSelect()->distinct();    
        }    
        else {
            $conditions[] = $this->getConnection()
                ->quoteInto('cat_index.category_id=?', $filters['category_id']);
        }        

            
        if (isset($filters['category_is_anchor'])) {
            $conditions[] = $this->getConnection()
                ->quoteInto('cat_index.is_parent=?', $filters['category_is_anchor']);
        }

        $joinCond = join(' AND ', $conditions);
        $fromPart = $this->getSelect()->getPart(Zend_Db_Select::FROM);
        if (isset($fromPart['cat_index'])) {
            $fromPart['cat_index']['joinCondition'] = $joinCond;
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
        }
        else {
            $this->getSelect()->join(
                array('cat_index' => $this->getTable('catalog/category_product_index')),
                $joinCond,
                array('cat_index_position' => 0)
            );
        }
        
        if (method_exists($this, '_productLimitationJoinStore'))
            $this->_productLimitationJoinStore();

        Mage::dispatchEvent('catalog_product_collection_apply_limitations_after', array(
            'collection'    => $this
        ));

        return $this;
    }
    
    public function prepareFeedCategoryFilter($condition, $value){
        
        $categories = Mage::getResourceModel('catalog/category_collection')
                        ->addIsActiveFilter();

        $exclusion = in_array($condition, array('neq', 'nlike', 'nin'));
        if ($exclusion){
        	switch($condition){
        		case 'neq':
        			$condition = 'eq';
        		break;
        		case 'nlike':
        			$condition = 'like';
        		break;
        		case 'nin':
        			$condition = 'in';
        		break;	
        	}
        }                
        if ($condition == 'like' || $condition == 'nlike'){
            $category = Mage::getModel('catalog/category')->load($value);
            $categories->addFieldToFilter('name', array($condition=>'%'.$category->getName().'%'));
        }    
        else{ 
            $categories->addFieldToFilter('entity_id', array($condition=>$value));
        }    
                        
        foreach ($categories as $_cat){
        	if ($exclusion){        		
        		$this->_prepared_feed_categories['exclusion'][] = intval($_cat->getId());
        	}else{
        		$this->_prepared_feed_categories['inclusion'][] = intval($_cat->getId());
        	} 
        }

        return $this;
    }
    
    public function addFeedCategoryFilter(){
    	
    	$categories = Mage::getResourceModel('catalog/category_collection')
                        ->addIsActiveFilter();
                        
        $all_categories_ids = array();

    	foreach ($categories as $_cat){
        	$all_categories_ids[] = intval($_cat->getId());
        }
        
    	if (count($this->_prepared_feed_categories['exclusion'])){
			$all_categories_ids = array_diff($all_categories_ids, $this->_prepared_feed_categories['exclusion']);
		}
		if (count($this->_prepared_feed_categories['inclusion'])){
			$all_categories_ids = array_intersect($all_categories_ids, $this->_prepared_feed_categories['inclusion']);
		}
		
		$this->_feed_categories = $all_categories_ids;  
        
        $this->_applyProductLimitations();
    	
    	return $this;
    }
    
	public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        if ($attribute === null) {
            $this->getSelect();
            return $this;
        }

        if (is_numeric($attribute)) {
            $attribute = $this->getEntity()->getAttribute($attribute)->getAttributeCode();
        } else if ($attribute instanceof Mage_Eav_Model_Entity_Attribute_Interface) {
            $attribute = $attribute->getAttributeCode();
        }

        if (is_array($attribute)) {
            $sqlArr = array();
            foreach ($attribute as $condition) {
                $sqlArr[] = $this->_getAttributeConditionSql($condition['attribute'], $condition, $joinType);
            }            
	        if (count($this->_feed_categories)){        	
	        	if ($this->_filter_type == GoMage_Feed_Model_Adminhtml_System_Config_Source_Filter::SPLIT){        	
	            	$sqlArr[] = $this->getConnection()->quoteInto('cat_index.category_id IN(?)', $this->_feed_categories);
	        	}    	                
	        }            
            $conditionSql = '('.implode(') OR (', $sqlArr).')';
        } else if (is_string($attribute)) {
            if ($condition === null) {
                $condition = '';
            }
            $conditionSql = $this->_getAttributeConditionSql($attribute, $condition, $joinType);
        }

        if (!empty($conditionSql)) {
            $this->getSelect()->where($conditionSql, null, Varien_Db_Select::TYPE_CONDITION);
        } else {
            Mage::throwException('Invalid attribute identifier for filter ('.get_class($attribute).')');
        }

        return $this;
    }

    public function setFilterType($filter_type){
    	$this->_filter_type = $filter_type;
    	return $this;
    }
    
    public function applyFeedCategoryFilter(){
    	if (count($this->_feed_categories)){
    		$conditionSql = $this->getConnection()->quoteInto('cat_index.category_id IN(?)', $this->_feed_categories);        	
    		$this->getSelect()->where($conditionSql, null, Varien_Db_Select::TYPE_CONDITION);        
	    }
    	return $this;
    }

}
