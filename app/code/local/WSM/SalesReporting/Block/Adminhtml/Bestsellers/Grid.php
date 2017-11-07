<?php

class WSM_SalesReporting_Block_Adminhtml_Bestsellers_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareCollection()
    {
    	$filterData = $this->getFilterData();
    	$store = Mage::app()->getStore()->getStoreId();
    
		$this->setCountTotals(false);
		$this->setFilterVisibility(false);
		$this->setCountSubTotals(false);

    	$collection = Mage::getModel('sales/order_item')->getCollection()
    	->addAttributeToSelect('sku')
    	->addAttributeToSelect('name')
    	->addAttributeToSelect('product_id')
    	->addAttributeToSelect('price');

    	$collection->getSelect()->join( array('orders'=> 'sales_flat_order_grid'), 'orders.entity_id = main_table.order_id', array('orders.increment_id'));
    	$collection->getSelect()->joinLeft( array('fraud'=> 'fraud_status'), 'orders.increment_id = fraud.order_id', array('fraud.status'));
    	$collection->getSelect()->joinLeft( array('rank'=> 'catalog_product_entity_int'), 'rank.entity_id = main_table.product_id AND rank.attribute_id = 1015', array('rank.value'));
//     	$collection->getSelect()->joinLeft( array('category'=> 'catalog_category_product'), 'category.product_id = main_table.product_id', array('category.category_id'));
//     	$collection->getSelect()->join( array('cat'=> 'catalog_category_entity_varchar'), 'cat.entity_id = category.category_id AND cat.attribute_id = 111', array('cat.value'));

    	$subquery = new Zend_Db_Expr(' (SELECT catalog_category_product.category_id, catalog_category_product.product_id FROM catalog_category_product GROUP BY catalog_category_product.product_id) ');
    	$collection->getSelect()->joinLeft( array('cat_prod' => $subquery), 'cat_prod.product_id = main_table.product_id', array('cat_prod.category_id') );
    	$collection->getSelect()->joinLeft( array('category'=> 'catalog_category_entity_varchar'), 'category.entity_id = cat_prod.category_id AND category.attribute_id = 111', array('cat_name'=>'category.value') );
    	$collection->addFieldToFilter(
    					array(
    							'fraud.status', 'fraud.status',
    					), 
    					array(
    							array('in' => array('I')),
    							array('null' => 'null')
    					)
    	);
    	$collection->getSelect()
                ->columns('SUM(main_table.qty_ordered) as amount')
                ->group('main_table.product_id');
    	
    	if ($filterData->getData('from') != null) {
    		$from_date = Mage::app()->getLocale()->utcDate($store, $filterData->getData('from'), false, "yyyy-MM-dd");
    		$collection->addFieldToFilter('main_table.created_at', array('gteq' => $from_date->toString("yyyy-MM-dd HH:mm:ss")));
    	}
    	if ($filterData->getData('to') != null) {
    		$to_date = Mage::app()->getLocale()->utcDate($store, $filterData->getData('to'), false, "yyyy-MM-dd");
    		$to_date->addDay('1');
    		$collection->addFieldToFilter('main_table.created_at', array('lteq' => $to_date->toString("yyyy-MM-dd HH:mm:ss")));
    	}
    	$collection->getSelect()->order('amount DESC');
    	
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {

    	$this->addColumn('product_id', array(
    			'header'    => Mage::helper('sales')->__('Product ID'),
    			'index'     => 'product_id',
    			'type'      => 'string',
    			'sortable'  => false
    	));
    	$this->addColumn('sku', array(
    			'header'    => Mage::helper('sales')->__('Product SKU'),
    			'index'     => 'sku',
    			'type'      => 'string',
    			'sortable'  => false
    	));
    	$this->addColumn('name', array(
    			'header'    => Mage::helper('sales')->__('Product Name'),
    			'index'     => 'name',
    			'type'      => 'string',
    			'sortable'  => false
    	));
    	$this->addColumn('amount', array(
    			'header'    => Mage::helper('sales')->__('Amount Sold'),
    			'index'     => 'amount',
    			'type'      => 'number',
    			'sortable'  => false
    	));
    	$this->addColumn('cat_name', array(
    			'header'    => Mage::helper('sales')->__('Category'),
    			'index'     => 'cat_name',
    			'type'      => 'string',
    			'sortable'  => false
    	));

        $currencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn('price', array(
            'header'        => Mage::helper('sales')->__('Price'),
            'type'          => 'currency',
            'currency_code' => $currencyCode,
            'index'         => 'price',
            'sortable'      => false,
            'rate'          => $this->getRate($currencyCode),
        ));

        $this->addColumn('value', array(
        		'header'    => Mage::helper('sales')->__('Product Rank'),
        		'index'     => 'value',
        		'type'      => 'string',
        		'sortable'  => false
        ));

        $this->addExportType('*/*/exportBestsellersCsv', Mage::helper('adminhtml')->__('CSV'));
        $this->addExportType('*/*/exportBestsellersExcel', Mage::helper('adminhtml')->__('Excel XML'));

        return parent::_prepareColumns();
    }
    
    public function getCurrentCurrencyCode()
    {
    	if (is_null($this->_currentCurrencyCode)) {
    		$this->_currentCurrencyCode = (count($this->_storeIds) > 0)
    		? Mage::app()->getStore(array_shift($this->_storeIds))->getBaseCurrencyCode()
    		: Mage::app()->getStore()->getBaseCurrencyCode();
    	}
    	return $this->_currentCurrencyCode;
    }
    
    /**
     * Get currency rate (base to given currency)
     *
     * @param string|Mage_Directory_Model_Currency $currencyCode
     * @return double
     */
    public function getRate($toCurrency)
    {
    	return Mage::app()->getStore()->getBaseCurrency()->getRate($toCurrency);
    }
}
