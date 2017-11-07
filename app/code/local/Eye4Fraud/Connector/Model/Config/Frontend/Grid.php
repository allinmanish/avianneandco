<?php

/**
 * Check order grid rewrite
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_Config_Frontend_Grid extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    /**
     * Render config field
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element){
        $order_grid = Mage::getBlockSingleton('adminhtml/sales_order_grid');
        $element->setData('checked', is_a($order_grid, 'Eye4Fraud_Connector_Block_Sales_Order_Grid'));
        $element->setData('disabled', 'disabled');
        $html = parent::render($element);;
        if(!is_a($order_grid, 'Eye4Fraud_Connector_Block_Sales_Order_Grid'))  $html .= '<td colspan="3">Final model class '.get_class($order_grid).'</td>';
        return $html;
    }
}