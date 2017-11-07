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
 * Catalog Layer Price Filter resource model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */



    class Mango_AjaxList_Model_Resource_Eav_Mysql4_Layer_Filter_Price extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price {

        public function applyPriceRange($filter) {
            // echo "apply price range";
            $interval = $filter->getInterval();
            if (!$interval) {
                return $this;
            }

            list($from, $to) = $interval;
            if ($from === '' && $to === '') {
                return $this;
            }

            $select = $filter->getLayer()->getProductCollection()->getSelect();
            $priceExpr = $this->_getPriceExpression($filter, $select, false);

            if ($to !== '') {
                $to = (float) $to;
                if ($from == $to) {
                    $to += self::MIN_POSSIBLE_PRICE;
                }
            }

            if ($from !== '') {
                $select->where($priceExpr . ' >= ' . $this->_getComparingValue($from, $filter));
            }

            //echo "to " . $to . "---";

            if ($to !== '') {
                $select->where($priceExpr . ' <= ' . $this->_getComparingValue($to, $filter));
            }

            return $this;
        }

        /**
         * Get comparing value sql part
         *
         * @param float $price
         * @param Mage_Catalog_Model_Layer_Filter_Price $filter
         * @param bool $decrease
         * @return float
         */
        protected function _getComparingValue($price, $filter, $decrease = true) {
            $currencyRate = $filter->getLayer()->getProductCollection()->getCurrencyRate();
            if ($decrease) {
                // return ($price - (self::MIN_POSSIBLE_PRICE / 2)) / $currencyRate;
                return ($price ) / $currencyRate;
            }
            // return ($price + (self::MIN_POSSIBLE_PRICE / 2)) / $currencyRate;
            return ($price ) / $currencyRate;
        }

    }


