<?php

/**
 * Backend model of system variable
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_Config_Backend_Data extends Mage_Core_Model_Config_Data
{

    /**
     * Add availability call after load as public
     */
    public function afterLoad()
    {
        $this->setData('checked','1');
        $this->setData('value','2');
        $this->_afterLoad();
    }

    /**
     * Disable load
     * @param int $id
     * @param null $field
     * @return $this
     */
    public function load($id, $field=null){
        return $this;
    }

    /**
     * Disable save
     * @return $this
     */
    public function save(){
        return $this;
    }
}