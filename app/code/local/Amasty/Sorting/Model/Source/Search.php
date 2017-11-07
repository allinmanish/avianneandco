<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Sorting
 */
class Amasty_Sorting_Model_Source_Search
{
    public function toOptionArray()
    {
        $options = array();
        
        // magento wants at least one option to be selected
        $options[] = array(
            'value' => 'relevance',
            'label' => 'Relevance',
            
        );         
        foreach (Mage::helper('amsorting')->getMethods() as $className){
            $method = Mage::getSingleton('amsorting/method_' . $className);  
            $options[] = array(
                'value' => $method->getCode(),
                'label' => Mage::helper('amsorting')->__($method->getName()),
                
            );
        }   
        return $options;
    }
}