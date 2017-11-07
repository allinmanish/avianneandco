<?php

class Wee_Fpc_Block_Adminhtml_Cache_Additional extends Mage_Adminhtml_Block_Cache_Additional
{
    public function getFlushCacheUrl()
    {
        return $this->getUrl('*/fpc/clean');
    }

    public function isFpcEnabled()
    {
        return Mage::helper('wee_fpc')->isEnabled();
    }
}