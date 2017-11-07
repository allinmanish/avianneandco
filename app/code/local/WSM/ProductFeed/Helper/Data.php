<?php
class WSM_ProductFeed_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getBest($categoryID = null){
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $visibility = array(
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
        );

        if ($categoryID) {
            $category = Mage::getModel('catalog/category')->load($categoryID);
            $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addOrderedQty()
                ->addCategoryFilter($category)
                ->addAttributeToFilter('visibility', $visibility)
                ->addAttributeToFilter('status', array('eq' => 1))
		->addUrlRewrite()
		->setPageSize(6)
                ->setOrder('ordered_qty', 'desc');

            return $_productCollection;
        } else {

            $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addOrderedQty()
                ->addAttributeToFilter('visibility', $visibility)
                ->addAttributeToFilter('status', array('eq' => 1))
		->addUrlRewrite()
		->setPageSize(6)
                ->setOrder('ordered_qty', 'desc');

            return $_productCollection;
        }

    }

    public function getNew($categoryID = null){
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $visibility = array(
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
        );

        if ($categoryID) {
            $category = Mage::getModel('catalog/category')->load($categoryID);
            $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addOrderedQty()
                ->addCategoryFilter($category)
                ->addAttributeToFilter('visibility', $visibility)
                ->addAttributeToFilter('status', array('eq' => 1))
                ->setOrder('created_at', 'desc')
		->addUrlRewrite()
		->setPageSize(6);

            return $_productCollection;
        } else {
            $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addOrderedQty()
                ->addAttributeToFilter('visibility', $visibility)
                ->addAttributeToFilter('status', array('eq' => 1))
                ->setOrder('created_at', 'desc')
		->addUrlRewrite()
		->setPageSize(6);

            return $_productCollection;
        }



    }
    public function getFeaturedProducts($categoryID = null){
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $visibility = array(
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
        );

        if ($categoryID) {
            $category = Mage::getModel('catalog/category')->load($categoryID);
            $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addOrderedQty()
                ->addCategoryFilter($category)
                ->addAttributeToFilter('visibility', $visibility)
                ->addAttributeToFilter('status', array('eq' => 1))
                ->setOrder('rank', 'desc')
		->addUrlRewrite()
		->setPageSize(6);

            return $_productCollection;
        } else {

            $_productCollection = Mage::getResourceModel('reports/product_collection')
                ->addAttributeToSelect('*')
                ->addOrderedQty()
                ->addAttributeToFilter('visibility', $visibility)
                ->addAttributeToFilter('status', array('eq' => 1))
                ->setOrder('rank', 'desc')
		->addUrlRewrite()
		->setPageSize(6);

            return $_productCollection;
        }

    }


    public function featuredProducts(){
        //Get Settings from System -> Configuration -> WSM -> Product Feed Settings
        $featuredProduct1=Mage::getStoreConfig('productfeed_section/productfeed_group/productfeed_featured1');
        $featuredProduct2=Mage::getStoreConfig('productfeed_section/productfeed_group/productfeed_featured2');
        $featuredProduct3=Mage::getStoreConfig('productfeed_section/productfeed_group/productfeed_featured3');
        $featuredProduct4=Mage::getStoreConfig('productfeed_section/productfeed_group/productfeed_featured4');

        if ($featuredProduct1 && $featuredProduct2 && $featuredProduct3 && $featuredProduct4 ) {
            $result = $featuredProduct1 .',' . $featuredProduct2 . ','. $featuredProduct3 . ',' . $featuredProduct4;
        }else{

              return null;
        }

        return $result;
    }




}
	 