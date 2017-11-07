<?php

/**
 * Collection of fraud statuses of orders
 *
 * @category   Eye4Fraud
 * @package    Eye4fraud_Connector
 */
class Eye4Fraud_Connector_Model_Resource_Status_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * All requested statuses
     * @var array
     */
    protected $statuses = array();

    /**
     * Collection loaded in cron
     * @var bool
     */
    protected $_cronFlag = false;

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('eye4fraud_connector/status');
    }

    /**
     * Add attribute id to collection
     *
     * @param array $statuses Array of order IDs
     * @return Mage_Customer_Model_Resource_Group_Collection
     */
    public function setStatuses($statuses)
    {
        $this->statuses = $statuses;
        $this->addFieldToFilter('order_id', array('in'=>array_keys($statuses)));
        return $this;
    }

    /**
     * Select all statuses except
     * @param $statuses
     * @return $this
     */
    public function exceptStatuses($statuses){
        if(!is_array($statuses)) return $this;
        $this->getSelect()->where('status NOT IN (?)',$statuses);
        return $this;
    }

    /**
     * Limit collection by update date
     * @param $timestamp
     * @return $this
     */
    public function notOlderThan($timestamp){
        $this->getSelect()->where('updated_at < ?',$timestamp);
        return $this;
    }

    public function limitRecordsCount($limit){
        $this->getSelect()->limit($limit);
        return $this;
    }

    /**
     * Set cron flag
     * @param bool $flag
     * @return $this
     */
    public function setCronFlag($flag){
        $this->_cronFlag = $flag;
        return $this;
    }

    /**
     * @return $this
     */
    protected function _afterLoad(){
        parent::_afterLoad();
        $helper = Mage::helper("eye4fraud_connector");
        $isCronEnabled = $helper->getConfig('cron_settings/enabled');
        $final_statuses = $helper->getFinalStatuses();
        foreach ($this->_items as $item) {
            /** @var Eye4Fraud_Connector_Model_Status $item */
            if((!$isCronEnabled or $this->_cronFlag) and !in_array($item['status'],$final_statuses)){
                $this->statuses[$item->getData('order_id')] = 0;
            }
            else $this->statuses[$item->getData('order_id')] = 1;
        }
        Mage::helper("eye4fraud_connector")->log(json_encode($this->statuses));
        foreach($this->statuses as $orderId=>$loaded){
            if(!$loaded){
                if($item = $this->getItemById($orderId)){
                    $item->retrieveStatus();
                }
                else{
                    /** @var Eye4Fraud_Connector_Model_Status $item */
                    $item = Mage::getModel("eye4fraud_connector/status");
                    $item->isObjectNew(true);
                    $item->setData('order_id', $orderId);
                    $item->retrieveStatus();
                    $this->addItem($item);
                }
            }
        }
        return $this;
    }

    /**
     * Get fraud status from status Model
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    public function getOrderStatus($order){
        $fraudStatusItem = $this->getItemById($order->getData('increment_id'));
        if($fraudStatusItem==null) return "IER";
        $status = $fraudStatusItem->getData('status');
        return $status;
    }

    public function getOrderStatusLabel($order){
        $status = $this->getOrderStatus($order);
        return Mage::helper('eye4fraud_connector')->__("status:".$status);
    }

    public function addStatusDescription($renderedValue, $order, $column){
        $fraudStatusItem = $this->getItemById($order->getData('increment_id'));
        if($fraudStatusItem==null) return $renderedValue;
        $description = $fraudStatusItem->getData('description');
        if(!$description or $renderedValue==$description) return $renderedValue;
        return '<span>'.$renderedValue.'&nbsp;<img style="vertical-align:middle; margin-top:-3px;" src="'.Mage::getDesign()->getSkinUrl('images/i_question-mark.png').'" title="'.$description.'"/></span>';
    }

    /**
     * Retreive option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return parent::_toOptionArray('id', 'record_id');
    }

    /**
     * Retreive option hash
     *
     * @return array
     */
    public function toOptionHash()
    {
        return parent::_toOptionHash('id', 'record_id');
    }
}
