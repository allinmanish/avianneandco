<?php

class WSM_Catalog_Model_Config extends Mage_Catalog_Model_Config {

public function getAttributeUsedForSortByArray()
{
    $options = parent::getAttributeUsedForSortByArray();
    if (!isset($options['created_at'])) {
        $options['created_at'] = Mage::helper('catalog')->__('Date');
    }
    return $options;
}

}