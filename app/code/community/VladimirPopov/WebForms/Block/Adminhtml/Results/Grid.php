<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2011 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Adminhtml_Results_Grid
	extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('webformsGrid');
		$this->setDefaultSort('created_time');
		$this->setDefaultDir('desc');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
		$this->setVarNameFilter('product_filter');
	}
	
	public function getGridUrl()
	{
		return $this->getUrl('*/*/grid', array('_current'=>true));
	}
	
	protected function _getStore()
	{
		$storeId = (int) $this->getRequest()->getParam('store', 0);
		return Mage::app()->getStore($storeId);
	}
	
	protected function _filterCustomerCondition($collection,$column){
		if (!$value = trim($column->getFilter()->getValue())) {
			return;
		}
		while(strstr($value,"  ")){
			$value = str_replace("  "," ",$value);
		}
		$customers_array = array();
		$name = explode(" ",$value);
		$firstname = $name[0];
		$lastname = $name[count($name)-1];
		$customers = Mage::getModel('customer/customer')->getCollection()
			->addAttributeToFilter('firstname',$firstname);
		if(count($name)==2)
			$customers->addAttributeToFilter('lastname',$lastname);
		foreach($customers as $customer){
			$customers_array[]= $customer->getId();
		}
		$collection->addFieldToFilter('customer_id', array('in' => $customers_array));
	}
	
	protected function _filterStoreCondition($collection, $column)
	{
		if (!$value = $column->getFilter()->getValue()) {
			return;
		}

		$this->getCollection()->addFilter('store_id',$value);
	}

	protected function _filterFieldCondition($collection,$column){
		$field_id = str_replace('field_','',$column->getIndex());
		$value = $column->getFilter()->getValue();
		$collection->addFieldFilter($field_id, $value);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('webforms/results')->getCollection()->addFilter('webform_id',$this->getRequest()->getParam('webform_id'));
		$this->setCollection($collection);

		Mage::dispatchEvent('webforms_block_adminhtml_results_grid_prepare_collection',array('grid'=>$this));

		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('id',array(
			'header' => Mage::helper('webforms')->__('Id'),
			'align'	=> 'right',
			'width'	=> '50px',
			'index'	=> 'id',
		));
		
		$this->addColumn('status',array(
				'header' => Mage::helper('webforms')->__('Status'),
				'align'	=> 'right',
				'width'	=> '80px',
				'index'	=> 'status',
		));
		
		$fields = Mage::getModel('webforms/fields')->getCollection()
			->addFilter('webform_id',$this->getRequest()->getParam('webform_id'));
		$fields->getSelect()->order('position asc');
		
		$maxlength = Mage::getStoreConfig('webforms/results/fieldname_display_limit');
		foreach($fields as $field){
			if($field->getType() != 'html'){
				$field_name = $field->getName();
				if($field->getResultLabel()){
					$field_name = $field->getResultLabel();
				}
				if(strlen($field_name)>$maxlength && $maxlength>0){
					if(function_exists('mb_substr')){
						$field_name = mb_substr($field_name,0,$maxlength).'...';
					} else {
						$field_name = substr($field_name,0,$maxlength).'...';
					}
				}
				$config = array(
					'header' => $field_name,
					'index' => 'field_'.$field->getId(),
					'sortable' => false,
					'filter_condition_callback' => array($this, '_filterFieldCondition'),
					'renderer' => 'VladimirPopov_WebForms_Block_Adminhtml_Results_Renderer_Value'
				);
				if($this->_isExport){
					$config['renderer'] = false;
				} else {
					if($field->getType() == 'image'){
						$config['filter'] = false;
						$config['width'] =  Mage::getStoreConfig('webforms/images/grid_thumbnail_width').'px';
					}
					if(strstr($field->getType(),'select')){
						$config['type'] = 'options';
						$config['options'] = $field->getSelectOptions();
					}
					if($field->getType() == 'number' || $field->getType() == 'stars'){
						$config['type'] = 'number';
					}
					if($field->getType() == 'date'){
						$config['type'] = 'date';
					}
					if($field->getType() == 'datetime'){
						$config['type'] = 'datetime';
					}
				}
				$config = new Varien_Object($config);
				Mage::dispatchEvent('webforms_block_adminhtml_results_grid_prepare_columns_config',array('field'=>$field,'config'=>$config));
				
				$this->addColumn('field_'.$field->getId(), $config->getData());
			}
		}
		$config = array(
			'header' => Mage::helper('webforms')->__('Customer'),
			'align' => 'left',
			'index' => 'customer_id',
			'renderer' => 'VladimirPopov_WebForms_Block_Adminhtml_Results_Renderer_Customer',
			'filter_condition_callback' => array($this, '_filterCustomerCondition'),
			'sortable' => false
		);
		if($this->_isExport){
			$config['renderer'] = false;
		}
		$this->addColumn('customer_id',$config);

		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'        => Mage::helper('webforms')->__('Store View'),
				'index'         => 'store_id',
				'type'          => 'store',
				'store_all'     => true,
				'store_view'    => true,
				'sortable'      => false,
				'filter_condition_callback'	=> array($this, '_filterStoreCondition'),
			));
		}
		
		if(Mage::registry('webform_data')->getApprove()){
			$this->addColumn('approved', array(
				'header'    => Mage::helper('webforms')->__('Approved'),
				'index'     => 'approved',
				'type'      => 'options',
				'options'   => Array("1"=>$this->__('Yes'),"0"=>$this->__('No')),
			));
			
		}
		
		$this->addColumn('ip',array(
			'header' => Mage::helper('webforms')->__('IP'),
			'index' => 'ip',
			'sortable' => false,
			'filter' => false,
		));
		
		
		$this->addColumn('created_time', array(
			'header'    => Mage::helper('webforms')->__('Date Created'),
			'index'     => 'created_time',
			'type'      => 'datetime',
		));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('webforms')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('webforms')->__('Excel XML'));

		Mage::dispatchEvent('webforms_block_adminhtml_results_grid_prepare_columns',array('grid'=>$this));
		
		return parent::_prepareColumns();
	}
	
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('id');
					
		$this->getMassactionBlock()->addItem('delete', array(
			 'label'=> Mage::helper('webforms')->__('Delete'),
			 'url'  => $this->getUrl('*/*/massDelete',array('webform_id'=>$this->getRequest()->getParam('webform_id'))),
			 'confirm' => Mage::helper('webforms')->__('Are you sure to delete selected results?'),
		));
				
		$this->getMassactionBlock()->addItem('email', array(
			 'label'=> Mage::helper('webforms')->__('Send by e-mail'),
			 'url'  => $this->getUrl('*/*/massEmail',array('webform_id'=>$this->getRequest()->getParam('webform_id'))),
			 'confirm' => Mage::helper('webforms')->__('Send selected results by e-mail?'),
		));
		
		if(Mage::registry('webform_data')->getApprove()){
			$this->getMassactionBlock()->addItem('approve', array(
				 'label'=> Mage::helper('webforms')->__('Approve'),
				 'url'  => $this->getUrl('*/*/massApprove',array('webform_id'=>$this->getRequest()->getParam('webform_id'))),
				 'confirm' => Mage::helper('webforms')->__('Approve selected results?'),
			));
			
			$this->getMassactionBlock()->addItem('disapprove', array(
				 'label'=> Mage::helper('webforms')->__('Disapprove'),
				 'url'  => $this->getUrl('*/*/massDisapprove',array('webform_id'=>$this->getRequest()->getParam('webform_id'))),
				 'confirm' => Mage::helper('webforms')->__('Disapprove selected results?'),
			));
		}

		Mage::dispatchEvent('webforms_adminhtml_results_grid_prepare_massaction',array('grid'=>$this));
	
		return $this;
	}
}
?>
