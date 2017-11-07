<?php

/**
 * Check order grid rewrite
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_Config_Frontend_Paypaluk extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Render config field
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element){
        $model = Mage::getModel('paypaluk/api_nvp');
        $element->setData('checked', is_a($model, 'Eye4Fraud_Connector_Model_PaypalUk_Api_Nvp'));
        $element->setData('disabled', 'disabled');
        $html = parent::render($element);
        if(!is_a($model, 'Eye4Fraud_Connector_Model_PaypalUk_Api_Nvp')) $html .= '<td colspan="3">Final model class '.get_class($model).'</td>';
        return $html;
    }

}