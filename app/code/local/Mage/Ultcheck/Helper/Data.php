<?php
class Mage_Ultcheck_Helper_Data extends Mage_Core_Helper_Data
{
    public function isUltcheckEnabled()
    {
        return Mage::getStoreConfig('ultcheck/general_settings/enabled');
    }
}