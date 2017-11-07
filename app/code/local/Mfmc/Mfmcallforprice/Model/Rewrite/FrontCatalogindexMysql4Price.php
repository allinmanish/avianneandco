<?php

class Mfmc_mfmcallforprice_Model_Rewrite_FrontCatalogindexMysql4Price extends Mage_CatalogIndex_Model_Mysql4_Price
{

public function applyFilterToCollection($collection, $attribute, $range, $index, $tableName = 'price_table')
    {
        /**
         * Distinct required for removing duplicates in case when we have grouped products
         * which contain multiple rows for one product id
         */
        $collection->getSelect()->distinct(true);
        $tableName = $tableName.'_'.$attribute->getAttributeCode();
        $collection->getSelect()->joinLeft(
            array($tableName => $this->getMainTable()),
            $tableName .'.entity_id=e.entity_id',
            array()
        );
        
        // MFMC MFMCALLFORPRICE
        $cfpTable = 'cfp';
       
        $collection->getSelect()->joinLeft(
            array($cfpTable => $this->getTable('mfmcallforprice/mfmcallforprice')),
            $cfpTable .'.product_id=e.entity_id',
            array()
        );
        // END MFMC MFMCALLFORPRICE


        $response = new Varien_Object();
        $response->setAdditionalCalculations(array());

        $collection->getSelect()
            ->where($tableName . '.website_id = ?', $this->getWebsiteId())
            ->where($tableName . '.attribute_id = ?', $attribute->getId());

        // MFMC MFMCALLFORPRICE
        $collection->getSelect()
            ->where($cfpTable . '.product_id IS NULL OR ('.$cfpTable . '.product_id IS NOT NULL AND '.$cfpTable . '.hide_price = 0)');
        // END MFMC MFMCALLFORPRICE

        if ($attribute->getAttributeCode() == 'price') {
            $collection->getSelect()->where($tableName . '.customer_group_id = ?', $this->getCustomerGroupId());
            $args = array(
                'select'=>$collection->getSelect(),
                'table'=>$tableName,
                'store_id'=>$this->getStoreId(),
                'response_object'=>$response,
            );

            Mage::dispatchEvent('catalogindex_prepare_price_select', $args);
        }

        $collection->getSelect()->where("(({$tableName}.value".implode('', $response->getAdditionalCalculations()).")*{$this->getRate()}) >= ?", ($index-1)*$range);
        $collection->getSelect()->where("(({$tableName}.value".implode('', $response->getAdditionalCalculations()).")*{$this->getRate()}) < ?", $index*$range);

        return $this;
    }


    public function getCount($range, $attribute, $entitySelect)
    {
        $select = clone $entitySelect;
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $select->join(array('price_table'=>$this->getMainTable()), 'price_table.entity_id=e.entity_id', array());

        // MFMC MFMCALLFORPRICE
        $cfpTable = 'cfp';
       
        $select->joinLeft(
            array($cfpTable => $this->getTable('mfmcallforprice/mfmcallforprice')),
            $cfpTable .'.product_id=e.entity_id',
            array()
        );
        // END MFMC MFMCALLFORPRICE


        
        
        
        $response = new Varien_Object();
        $response->setAdditionalCalculations(array());

        if ($attribute->getAttributeCode() == 'price') {
            $select->where('price_table.customer_group_id = ?', $this->getCustomerGroupId());
            
            // MFMC MFMCALLFORPRICE
            $select->where($cfpTable . '.product_id IS NULL OR ('.$cfpTable . '.product_id IS NOT NULL AND '.$cfpTable . '.hide_price = 0)');
            // END MFMC MFMCALLFORPRICE


            
            
            $args = array(
                'select'=>$select,
                'table'=>'price_table',
                'store_id'=>$this->getStoreId(),
                'response_object'=>$response,
            );
            Mage::dispatchEvent('catalogindex_prepare_price_select', $args);
        }


        $fields = array('count'=>'COUNT(DISTINCT price_table.entity_id)', 'range'=>"FLOOR(((price_table.value".implode('', $response->getAdditionalCalculations()).")*{$this->getRate()})/{$range})+1");

        $select->from('', $fields)
            ->group('range')
            ->where('price_table.website_id = ?', $this->getWebsiteId())
            ->where('price_table.attribute_id = ?', $attribute->getId());


        $result = $this->_getReadAdapter()->fetchAll($select);

        $counts = array();
        foreach ($result as $row) {
            $counts[$row['range']] = $row['count'];
        }

        return $counts;
    }


}