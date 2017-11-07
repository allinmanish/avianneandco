<?php 
class WSM_Fraud_Model_Resource_Fraud_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init( 'wsm_fraud/fraud' );
	}
}