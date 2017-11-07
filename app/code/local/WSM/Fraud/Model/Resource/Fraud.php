<?php
class WSM_Fraud_Model_Resource_Fraud extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('wsm_fraud/fraud', 'entity_id');
    }
}