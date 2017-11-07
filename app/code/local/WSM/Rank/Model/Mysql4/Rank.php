<?php
 
class WSM_Rank_Model_Mysql4_Rank extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('rank/rank', 'entity_id');
    }
}