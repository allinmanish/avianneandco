<?php
/**
 * Resource model for single fraud status
 *
 * @category   Eye4Fraud
 * @package    Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_Resource_Status extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Primery key auto increment flag
     *
     * @var bool
     */
    protected $_isPkAutoIncrement = false;

    /**
     * Initialize main table and table id field
     */
    protected function _construct()
    {
        $this->_init('eye4fraud_connector/status', 'order_id');
    }

    public function setNewFlag($flag){
        $this->_useIsObjectNew = $flag;
    }
}