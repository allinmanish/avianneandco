<?php

/**
 * Check order grid rewrite
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_Config_Frontend_Authorizenet extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Render config field
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element){
        $model = Mage::helper('payment')->getMethodInstance('authorizenet');
        $element->setData('checked', is_a($model, 'Eye4Fraud_Connector_Model_Authorizenet'));
        $element->setData('disabled', 'disabled');
        $html = parent::render($element);
        if(!is_a($model, 'Eye4Fraud_Connector_Model_Authorizenet')) $html .= '<td colspan="3">Final model class '.get_class($model).'</td>';
        return $html;
    }

}