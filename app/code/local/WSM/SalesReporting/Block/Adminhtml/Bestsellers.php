<?php

class WSM_SalesReporting_Block_Adminhtml_Bestsellers extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_bestsellers';
        $this->_blockGroup = 'wsm_salesreporting';
        parent::__construct();

        $this->_headerText = Mage::helper('sales')->__('Best Seller Report');
        $this->setTemplate('report/grid/container.phtml');
        $this->_removeButton('add');
        $this->addButton('filter_form_submit', array(
            'label'     => Mage::helper('reports')->__('Show Report'),
            'onclick'   => 'filterFormSubmit()'
        ));
    }

    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/index', array('_current' => true));
    }
}
