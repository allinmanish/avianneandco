<?php
 
class WSM_Rank_Model_Rank extends Mage_Core_Model_Abstract
{
     
    public function _construct()
    {
        parent::_construct();
        $this->_init('rank/rank');
    }
}