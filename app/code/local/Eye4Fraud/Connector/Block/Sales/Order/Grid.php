<?php

/**
 * Rewritten orders grid with added column of fraud status. Use this class in other class rewrites if needed
 *
 * @category   Eye4Fraud
 * @package    Eye4fraud_Connector
 */
if(mageFindClassFile("EM_DeleteOrder_Block_Adminhtml_Sales_Order_Grid")){
    /** @noinspection PhpUndefinedClassInspection */
    class _BaseGridClass extends EM_DeleteOrder_Block_Adminhtml_Sales_Order_Grid{}
}
else{
    class _BaseGridClass extends Mage_Adminhtml_Block_Sales_Order_Grid{}
}

class Eye4Fraud_Connector_Block_Sales_Order_Grid extends _BaseGridClass

{
    protected function _prepareColumns()
    {
        if(!Mage::helper('eye4fraud_connector')->isEnabled()) return parent::_prepareColumns();

        $this->addColumnAfter('eye4fraud_status', array(
            'header' => Mage::helper('eye4fraud_connector')->__('Fraud Status'),
            'width' => '123',
            'filter'    => false,
            'sortable'  => false,
            'getter'    => array(Mage::getResourceSingleton('eye4fraud_connector/status_collection'),'getOrderStatusLabel'),
            'frame_callback' => array(Mage::getResourceSingleton('eye4fraud_connector/status_collection'),'addStatusDescription')
        ), 'status');
        return parent::_prepareColumns();
    }
}