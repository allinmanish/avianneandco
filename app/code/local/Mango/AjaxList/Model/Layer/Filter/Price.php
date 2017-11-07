<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Layer price filter
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
$store_code = Mage::app()->getStore()->getCode();




    class Mango_AjaxList_Model_Layer_Filter_Price extends Mage_Catalog_Model_Layer_Filter_Price {

        /**
         * Get information about products count in range
         *
         * @param   int $range
         * @return  int
         */
        public function getRangeItemCounts($range) {
            $rangeKey = 'range_item_counts_' . $range;
            $items = $this->getData($rangeKey);
            if (is_null($items)) {
                $items = $this->_getResource()->getCount($this, $range);
                $this->setData($rangeKey, $items);
            }
            return $items;
        }

        /**
         * Get price range for building filter steps
         *
         * @return int
         */
        public function getPriceRange() {

            return $this->getMaxPriceInt();
        }

        /**
         * Get data for build price filter items
         *
         * @return array
         */
        protected function _getItemsData() {
            $key = $this->_getCacheKey();
            $data = $this->getLayer()->getAggregator()->getCacheData($key);






            if ($data === null) {
                $dbRanges = $this->getRangeItemCounts($this->getMaxPriceInt());
                foreach ($dbRanges as $index => $count) {
                    $data[] = array(
                        'label' => $this->_renderItemLabel(0, $this->getMaxPriceInt()),
                        'value' => 0 . ',' . $this->getMaxPriceInt(),
                        'count' => $count,
                    );
                }
                $tags = array(
                    Mage_Catalog_Model_Product_Type_Price::CACHE_TAG,
                );
                $tags = $this->getLayer()->getStateTags($tags);
                $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
            }
            return $data;
        }

       

        /**
         * Prepare text of range label
         *
         * @param float|string $fromPrice
         * @param float|string $toPrice
         * @return string
         */
        protected function _renderRangeLabel($fromPrice, $toPrice) {
            $store = Mage::app()->getStore();
            $formattedFromPrice = $store->formatPrice($fromPrice);
            if ($toPrice === '') {
                return Mage::helper('catalog')->__('%s and above', $formattedFromPrice);
            } elseif ($fromPrice == $toPrice && Mage::app()->getStore()->getConfig(self::XML_PATH_ONE_PRICE_INTERVAL)) {
                return $formattedFromPrice;
            } else {
                if ($fromPrice != $toPrice) {
                    //$toPrice -= .01;
                }
                return Mage::helper('catalog')->__('%s - %s', $formattedFromPrice, $store->formatPrice($toPrice));
            }
        }

        public function getItemsCount() {
            return 1;
        }

        /**
         * Get maximum price from layer products set
         *
         * @return float
         */
        public function getMaxPriceInt() {
            $maxPrice = $this->getData('max_price_int');
            if (is_null($maxPrice)) {
                //  echo "sdfsdf";
                $_collection = $this->getLayer()->getProductCollection();
                //->getMaxPrice();



                $select = clone $_collection->getSelect();
                $priceExpression = $_collection->getPriceExpression($select) . ' ' . $_collection->getAdditionalPriceExpression($select);
                $sqlEndPart = ') * ' . $_collection->getCurrencyRate() . ', 2)';


                $select->reset(Zend_Db_Select::ORDER);
                $select->reset(Zend_Db_Select::WHERE);
                $select->reset(Zend_Db_Select::LIMIT_COUNT);
                $select->reset(Zend_Db_Select::LIMIT_OFFSET);
                $select->reset(Zend_Db_Select::COLUMNS);


                //$select = $_collection->_getSelectCountSql($select, false);
                $select->columns('ROUND(MAX(' . $priceExpression . $sqlEndPart);
                // $select->columns('ROUND(MIN(' . $priceExpression . $sqlEndPart);
                // $select->columns($_collection->getConnection()->getStandardDeviationSql('ROUND((' . $priceExpression . $sqlEndPart));
                //  $select->where($_collection->getPriceExpression($select) . ' IS NOT NULL');
                // echo $select;

                $row = $_collection->getConnection()->fetchRow($select, array(), Zend_Db::FETCH_NUM);
                //$this->_pricesCount = (int)$row[0];
                //$this->_maxPrice = ;
                //$this->_minPrice = (float)$row[2];
                //$this->_priceStandardDeviation = (float)$row[3];
                // return $this;


                /* echo "---";
                  print_r($row);
                  echo "----"; */




                $maxPrice = ceil((float) $row[0]);
                $this->setData('max_price_int', $maxPrice);
            }


            return $maxPrice;
        }

    }


