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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales orders grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_grid_collection';
    }

	protected function _prepareCollection()
    {
        $this->setDefaultFilter( array( 
//         		'status' => 'processing',
//         		'int_status' => 'incomplete'
        ) );
    	$collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        
        // Parent method
        
        if ($this->getCollection()) {

            $this->_preparePage();

            $columnId = $this->getParam($this->getVarNameSort(), $this->_defaultSort);
            $dir      = $this->getParam($this->getVarNameDir(), $this->_defaultDir);
            $filter   = $this->getParam($this->getVarNameFilter(), null);

            if (is_null($filter)) {
                $filter = $this->_defaultFilter;
            }

            if (is_string($filter)) {
                $data = $this->helper('adminhtml')->prepareFilterString($filter);
                $this->_setFilterValues($data);
            }
            else if ($filter && is_array($filter)) {
                $this->_setFilterValues($filter);
            }
            else if(0 !== sizeof($this->_defaultFilter)) {
                $this->_setFilterValues($this->_defaultFilter);
            }
            
            $_where = $this->getCollection()->getSelect()->getPart('WHERE');
            $this->getCollection()->getSelect()->reset(Zend_Db_Select::WHERE);
            foreach( $_where as $where ) {
            	if (strpos($where, "(main_table.status = 'processing')") !== false) {
            		$this->getCollection()->getSelect()->where('main_table.status IN (?)', array(
            				'processing',
            				'payment_review',
            				'fraud'
            		)
            		);
            	} 
            	elseif (strpos($where, "AND") === 0) {
            		$this->getCollection()->getSelect()->where(substr($where,3));
            	}
            	elseif (strpos($where, "OR") === 0) {
            		$this->getCollection()->getSelect()->orWhere(substr($where,2));
            	} else {
            		$this->getCollection()->getSelect()->where($where);
            	}
            }

            if (isset($this->_columns[$columnId]) && $this->_columns[$columnId]->getIndex()) {
                $dir = (strtolower($dir)=='desc') ? 'desc' : 'asc';
                $this->_columns[$columnId]->setDir($dir);
                $this->_setCollectionOrder($this->_columns[$columnId]);
            }

             $this->getCollection()->getSelect()->joinLeft(array("eye4fraud_stat" => 'fraud_status' ),"main_table.increment_id = eye4fraud_stat.order_id",array('eye4_status'=>'status'));

            //echo $this->getCollection()->getSelect();

            if (!$this->_isExport) {
                $this->getCollection()->load();
                $this->_afterLoadCollection();
            }
        }

        return $this;
        
        // Parent method
        
    }

    protected function _prepareColumns()
    {

    	$this->addColumn('status_color', array(
    			'header'=> Mage::helper('sales')->__('Color'),
    			'width' => '90px',
    			'type'  => 'text',
    			'index' => 'color',
    			'filter'    => false,
    			'sortable'  => false,
    	));

        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
        	'align' => 'center',
            'index' => 'increment_id',
        ));        
        
        // if(Mage::helper('eye4fraud_connector')->isEnabled()) {
        // 	$this->addColumn('eye4fraud_status', array(
        // 			'header' => Mage::helper('eye4fraud_connector')->__('Fraud Status'),
        // 			'width' => '123',
        //            // 'type'  => 'options',
        // 			//'filter'    => false,
        //             'index' => 'fraud_status',
        // 			'sortable'  => false,
        // 			'getter'    => array(Mage::getResourceSingleton('eye4fraud_connector/status_collection'),'getOrderStatusLabel'),
        // 			'frame_callback' => array(Mage::getResourceSingleton('eye4fraud_connector/status_collection'),'addStatusDescription')
        // 	), 'status');
        // }     
 if(Mage::helper('eye4fraud_connector')->isEnabled()) {
        $this->addColumn('eye4_status', array(
        		'header'=> Mage::helper('sales')->__('Fraud Status'),
        		'width' => '90px',
        		'type'  => 'options',
        		'index' => 'eye4_status',
//         		'filter'    => false,
//         		'sortable'  => false,
        		'options' => array( 
        				'I' => 'Insured',
        				'F' => 'Fraud',
        				'D' => 'Declined',
                        'C' => 'Cancelled',
        				'A' => 'Awaiting Response',
        				'P' => 'Pending Insurance',
        				'E' => 'Error'
        		),
        		'filter_condition_callback' => array($this, 'fraudFilter'),
                'frame_callback' => array(Mage::getResourceSingleton('eye4fraud_connector/status_collection'),'addStatusDescription')
        ));

    }
        
        $this->addColumn('image', array(
        		'header'=> Mage::helper('sales')->__('Image'),
        		'width' => '80px',
        	//	'type'  => 'text',
        		'filter'    => false,
        		'sortable'  => false,
        		'index' => 'image',
        ));

        /*if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }*/

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        		'width' => '100px',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        		'width' => '100px',
        ));

        /*$this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));*/

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('Price'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        		'width' => '100px',
        ));

        $this->addColumn('int_status', array(
        		'header' => Mage::helper('sales')->__('Int Status'),
        		'index' => 'int_status',
        		'width' => '100px',
//         		'type'  => 'options',
//         		'width' => '70px',
//         		'options' => array( 
//         		    'invoiced' => 'Invoiced',
//         		    'uninvoiced' => 'Uninvoiced',
//         		    'void' => 'Void',
//         				'incomplete' => 'Incomplete', 
//         				'unpaid' => 'Unpaid',
//         				'verifying' => 'Verifying',
//         				'waxing' => 'Waxing',
//         				'molding' => 'Molding',
//         				'casting' => 'Casting',
//         				'setting' => 'Setting',
//         				'polish/size' => 'Polish/Size',
//         				'engraving' => 'Engraving',
//         				'el fasi' => 'El fasi',
//         				'pvd' => 'PVD',
//         				'complete' => 'Complete',
//                         'return_received' => 'Return Received',
//         				)
        		//'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
        
        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Ext Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => array( 'processing' => 'Processing', 'complete' => 'Shipped', 'canceled' => 'Canceled' ),
        ));

        /*
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action',
                array(
                    'header'    => Mage::helper('sales')->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'     => 'getId',
                    'actions'   => array(
                        array(
                            'caption' => Mage::helper('sales')->__('View'),*/
                            //'url'     => array('base'=>'*/sales_order/view'),
                          /*  'field'   => 'order_id'
                        )
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
            ));
        }*/
        
        // Add Customer comment
       /* $this->addColumn('customercomment', array(
         		'header'       => Mage::helper('ordercomment')->__('Customer Comment'),
        		'index'        => 'customercomment',
        		'filter'    => false,
                'sortable'  => false,
        ));*/
        
        $this->addColumn('ordercomment', array(
        		'header'       => Mage::helper('ordercomment')->__('Customer Comment'),
        		'index'        => 'ordercomment',
        		'filter'    => false,
        		'sortable'  => false,
        		'width' => '100px',
        		'column_css_class' 	=> 'customer-comment',
        ));
        
        // Add Internal comment
        $this->addColumn('internalcomment', array(
        		'header'       => Mage::helper('ordercomment')->__('Internal Comment'),
        		'index'        => 'internalcomment',
        		'filter'    => false,
        		'sortable'  => false,
        ));
        
        // Add last edit
        $this->addColumn('last_edit', array(
        		'header'       => Mage::helper('ordercomment')->__('Last Edit'),
        		'index'        => 'last_edit',
        		'filter'    => false,
        		'sortable'  => false,
        ));
        
        // Add Processing functions
        $this->addColumn('processing', array(
        		'header'       => Mage::helper('ordercomment')->__('Processing Functions'),
        		'index'        => 'processing',
        		'filter'    => false,
        		'sortable'  => false,
        		'column_css_class' 	=> 'proc-functions',
        		'width' 	=> '270px',
        ));
        
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        //$this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem('cancel_order', array(
                 'label'=> Mage::helper('sales')->__('Cancel'),
                 'url'  => $this->getUrl('*/sales_order/massCancel'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
            $this->getMassactionBlock()->addItem('hold_order', array(
                 'label'=> Mage::helper('sales')->__('Hold'),
                 'url'  => $this->getUrl('*/sales_order/massHold'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
            $this->getMassactionBlock()->addItem('unhold_order', array(
                 'label'=> Mage::helper('sales')->__('Unhold'),
                 'url'  => $this->getUrl('*/sales_order/massUnhold'),
            ));
        }

        $this->getMassactionBlock()->addItem('pdfinvoices_order', array(
             'label'=> Mage::helper('sales')->__('Print Invoices'),
             'url'  => $this->getUrl('*/sales_order/pdfinvoices'),
        ));

        $this->getMassactionBlock()->addItem('pdfshipments_order', array(
             'label'=> Mage::helper('sales')->__('Print Packingslips'),
             'url'  => $this->getUrl('*/sales_order/pdfshipments'),
        ));

        $this->getMassactionBlock()->addItem('pdfcreditmemos_order', array(
             'label'=> Mage::helper('sales')->__('Print Credit Memos'),
             'url'  => $this->getUrl('*/sales_order/pdfcreditmemos'),
        ));

        $this->getMassactionBlock()->addItem('pdfdocs_order', array(
             'label'=> Mage::helper('sales')->__('Print All'),
             'url'  => $this->getUrl('*/sales_order/pdfdocs'),
        ));

        $this->getMassactionBlock()->addItem('print_shipping_label', array(
             'label'=> Mage::helper('sales')->__('Print Shipping Labels'),
             'url'  => $this->getUrl('*/sales_order_shipment/massPrintShippingLabel'),
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
        }
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
    protected function fraudFilter($collection, $column)
    {
    	if (!$value = $column->getFilter()->getValue()) {
    		return $this;
    	}
    	//if there was a space input

        else if($value=='E'){

             $this->getCollection()->addAttributeToFilter('eye4fraud_stat.status', NULL);
        }
    	
    	else
    	{
    		//else use default grid filter functionality (like $value input)
    		$this->getCollection()->addAttributeToFilter('eye4fraud_stat.status', array('like' => '%'.$value.'%'));
    	}
    	return $this;
    }

}
