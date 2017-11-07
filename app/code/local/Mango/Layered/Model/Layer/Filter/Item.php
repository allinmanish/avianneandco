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
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Filter item model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mango_Layered_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item {
    protected $_is_active = false;
    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl() {
        $_fragment = Mage::app()->getRequest()->getQuery();
        $_url_param = "";
        $_url_param = Mage::app()->getRequest()->getParam($this->getFilter()->getRequestVar());
        //echo $_url_param;
        $_filter = array();
        if (preg_match('/^[0-9,]+$/', $_url_param)) {
            $_filter = explode(',', $_url_param);
        } elseif ((int) $_url_param > 0) {
            $_filter[] = $_url_param;
        }
        //echo $_filter;
        // print_r($_filter);
        // print_r($_filter);
        //print_r($_filter);
        $_value = $this->getValue();
        // echo "***" . $this->getValue() . "--";
        // if(is_array($_value)) $_value = join(",", $_value); /*for price*/
        // print_r($_value);
        //echo $_value;
        // print_r($_filter);
        //   echo " --- $value ---";
        if (in_array($_value, $_filter)) {
            array_splice($_filter, array_search($_value, $_filter), 1);
            //   echo "--";
            //  print_r($_filter);
            //$this->setIsActive(true);
        } else {
            $_filter[] = $this->getValue();
            //     echo "***";
            //     print_r($_filter);
            //$this->setIsActive(true);
        }
        $_filter = array_unique($_filter);
        //   print_r($_filter);
        $_filter = join(",", $_filter);
        //echo $_filter;
        $_fragment[$this->getFilter()->getRequestVar()] = $_filter;
        if ($this->getFilter()->getRequestVar() == "price" && count($_filter)) {
            $_fragment[$this->getFilter()->getRequestVar()] = null;
        }
        $_fragment["ajax"] = null;
        $_fragment[Mage::getBlockSingleton('page/html_pager')->getPageVarName()] = null;
        $_query = $_fragment;
        //print_r($_fragment);
        /*foreach ($_query as $_index => $value)
            if ($_index != "q")
                $_query[$_index] = null;*/
        if (Mage::app()->getRequest()->getControllerName() == "result" && Mage::app()->getRequest()->getModuleName() == "catalogsearch") {
            return Mage::getUrl('*/*', array('_current' => true, '_use_rewrite' => true, '_query' => $_query));
        } else {
            return Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true, '_query' => $_query));
        }
    }
    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl() {
        $_fragment = Mage::app()->getRequest()->getQuery();
        /* echo ($_fragment[$this->getFilter()->getRequestVar()]);
          echo $this->getFilter()->getRequestVar();
          echo $this->getFilter()->getResetValue(); */
        $_fragment[$this->getFilter()->getRequestVar()] = $this->getFilter()->getResetValue();
        $_fragment["ajax"] = null;
        $_fragment["filter"] = null;

        $_query = $_fragment;
        /*foreach ($_query as $_index => $value
            )$_query[$_index] = null;*/
//echo http_build_query($_fragment);
        $params['_current'] = true;
        $params['_use_rewrite'] = true;


        $_query["q"] = Mage::app()->getRequest()->getParam("q"); 
        $params['_query'] = $_query;




        $params['_escape'] = true;
       // $params['_fragment'] = http_build_query($_fragment);
               if (Mage::app()->getRequest()->getControllerName() == "result" && Mage::app()->getRequest()->getModuleName() == "catalogsearch") {
        $_url = Mage::getUrl('*/*', $params);
                   } else {

               $_url = Mage::getUrl('*/*/*', $params);
        }
        /*if (strpos($_url, "#") == false) {
            $_url = $_url . "#filter=none";
        }*/
        return $_url;
    }
    public function setIsActive($value) {
        $this->_is_active = $value;
        return;
    }
    public function isItemActive() {
        $_url_param = Mage::app()->getRequest()->getParam($this->getFilter()->getRequestVar());
        $_filter = array();
        if (preg_match('/^[0-9,]+$/', $_url_param)) {
            $_filter = explode(',', $_url_param);
        } elseif ((int) $_url_param > 0) {
            $_filter[] = $_url_param;
        }
        $_value = $this->getValue();
        if (in_array($_value, $_filter)) {
            //array_splice($_filter, array_search($_value, $_filter), 1);
            return true;
        } else {
            return false;
            //$_filter[] = $this->getValue();
            //$this->setIsActive(true);
        }
        //return $this->_is_active;
    }
}
