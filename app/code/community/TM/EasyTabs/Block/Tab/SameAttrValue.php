<?php
class TM_EasyTabs_Block_Tab_SameAttrValue extends Mage_Catalog_Block_Product_Abstract
{
    protected $_cachePrefix = 'TM_EASYTABS_SERIE';
    const PRODUCTS_COUNT = 8;
    const COLUMNS_COUNT = 4;
    const ATTRIBUTE_CODE = 'serie';

    protected function _construct()
    {
        parent::_construct();
        $this->addData(array(
            'cache_lifetime'    => 86400,
            'cache_tags'        => array(Mage_Catalog_Model_Product::CACHE_TAG),
        ));
    }
    public function getCacheKeyInfo()
    {
        $productId = 0;
        if ($product = Mage::registry('product')) {
            $productId = $product->getId();
        }
        return array(
            $this->_cachePrefix,
            Mage::app()->getStore()->getId(),
            Mage::app()->getStore()->getCurrentCurrencyCode(),
            Mage::getDesign()->getPackageName(),
            $this->getTemplate(),
            $this->getProductsCount(),
            $this->getColumnsCount(),
            $this->getNameInLayout(),
            $productId
        );
    }
    /**
     * Process cached form_key and uenc params
     *
     * @param   string $html
     * @return  string
     */
    protected function _loadCache()
    {
        $cacheData = parent::_loadCache();
        if ($cacheData) {
            $search = array(
                '{{tm_easytabs uenc}}'
            );
            $replace = array(
                Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED
                    . '/' . Mage::helper('core/url')->getEncodedUrl()
            );
            if (defined('Mage_Core_Model_Url::FORM_KEY')) {
                $formKey = Mage::getSingleton('core/session')->getFormKey();
                $search = array_merge($search, array(
                    '{{tm_easytabs form_key_url}}',
                    '{{tm_easytabs form_key_hidden}}'
                ));
                $replace = array_merge($replace, array(
                    Mage_Core_Model_Url::FORM_KEY . '/' . $formKey,
                    'value="' . $formKey . '"'
                ));
            }
            $cacheData = str_replace($search, $replace, $cacheData);
        }
        return $cacheData;
    }
    /**
     * Replace form_key and uenc with placeholders
     *
     * @param string $data
     * @return Mage_Core_Block_Abstract
     */
    protected function _saveCache($data)
    {
        if (is_null($this->getCacheLifetime())
            || !$this->getMageApp()->useCache(self::CACHE_GROUP)) {
            return false;
        }
        $search = array(
            Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED
                . '/' . Mage::helper('core/url')->getEncodedUrl()
        );
        $replace = array(
            '{{tm_easytabs uenc}}'
        );
        if (defined('Mage_Core_Model_Url::FORM_KEY')) {
            $formKey = Mage::getSingleton('core/session')->getFormKey();
            $search = array_merge($search, array(
                Mage_Core_Model_Url::FORM_KEY . '/' . $formKey,
                'value="' . $formKey . '"'
            ));
            $replace = array_merge($replace, array(
                '{{tm_easytabs form_key_url}}',
                '{{tm_easytabs form_key_hidden}}'
            ));
        }
        $data = str_replace($search, $replace, $data);
        return parent::_saveCache($data);
    }
    /**
     * EE compatibility
     *
     * @return Mage_Core_Model_App
     */
    public function getMageApp()
    {
        if (method_exists($this, '_getApp')) {
            return $this->_getApp();
        }
        return Mage::app();
    }
    /**
     * Retrieve product final price in current currency
     *
     * @param  Mage_Catalog_Model_Product $product
     * @param  boolean $includingTax
     * @return float
     */
    public function getProductFinalPrice($product, $includingTax = false)
    {
        $basePrice = Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), $includingTax);
        return round(Mage::helper('core')->currency($basePrice, false, false), 2);
    }
    protected function _toHtml()
    {
        if (!$this->getProductCollection()) {
            return '';
        }
        return parent::_toHtml();
    }
    public function getProductsCount()
    {
        return self::PRODUCTS_COUNT;
    }
    public function getColumnsCount()
    {
        return self::COLUMNS_COUNT;
    }

    protected function _beforeToHtml()
    {
        $product = Mage::registry('product');
        if (!$product) {
            return parent::_beforeToHtml();
        }
        $attrValue = $product->getResource()
            ->getAttribute(self::ATTRIBUTE_CODE)
            ->getFrontend()
            ->getValue($product);
        /**
         * @var Mage_Catalog_Model_Resource_Product_Collection
         */
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(
            Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds()
        );
        $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        // $collection->addFieldToFilter(self::ATTRIBUTE_CODE, array('eq' => $attrValue));
        $collection->addFieldToFilter(array(
                        array('attribute' => 'entity_id', 'neq' => $product->getId()),
                    ));
        $collection->addFieldToFilter(array(
                        array('attribute' => self::ATTRIBUTE_CODE, 'eq' => $attrValue),
                    ));
        $this->setProductCollection($collection);
        return parent::_beforeToHtml();
    }
   /**
    * Retrieve url for add product to cart
    * Will return product view page URL if product has required options
    *
    * @param Mage_Catalog_Model_Product $product
    * @param array $additional
    * @return string
    */
    public function getAddToCartUrl($product, $additional = array())
    {
        if ($product->getTypeInstance(true)->hasOptions($product)
            || 'grouped' === $product->getTypeId()) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            if (!isset($additional['_query'])) {
                $additional['_query'] = array();
            }
            $additional['_query']['options'] = 'cart';
            $_url = $product->getUrl();
            $product->setUrl(null);
            $url = $this->getProductUrl($product, $additional);
            $product->setUrl($_url);
            return $url;
        }
        return parent::getAddToCartUrl($product, $additional);
    }
}