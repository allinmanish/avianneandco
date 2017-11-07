<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_Productrelater
 * @copyright  Copyright (c) 2003-2009 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */ 
 
class AW_Productrelater_Block_Erewrite	extends Enterprise_TargetRule_Block_Catalog_Product_List_Abstract	{
	function escape($str)	{
		$str=Mage::getSingleton('core/resource')->getConnection('core_read')->quote($str);
		return substr($str,1,-1);
	}
	
	public function getItemCollection()	{
		if (is_null($this->_items)) {
			$myConf=Mage::getStoreConfig("productrelater");
			$__itemscount = $myConf["mainoptions"]["itemscount"];
			$__source = $myConf["mainoptions"]["source"];
			$__selectproducts = $myConf["mainoptions"]["selectproducts"];
			$__fetchinstock = $myConf["mainoptions"]["fetchinstock"];
			$__pricecondition = $myConf["mainoptions"]["pricecondition"];
			$__price = $myConf["mainoptions"]["price"];
			$__replaceoriginalrp = $myConf["mainoptions"]["replaceoriginalrp"];
			
			if(!$__replaceoriginalrp) return parent::getItemCollection();
			
			if(!(int)$__itemscount||(int)$__itemscount<1)	$__itemscount = 3;
			if(($__source!=='1'&&$__source!=='2')
					||!$this->getProduct())												$__source = 1;
			if((int)$__selectproducts<1||(int)$__selectproducts>3)						$__selectproducts = 2;
			if(!$this->getProduct()&&$__selectproducts==3)								$__selectproducts = 2;
			if((int)$__fetchinstock<0||(int)$__fetchinstock>1||!$__fetchinstock)		$__fetchinstock = 0;
			if((int)$__pricecondition<1||(int)$__pricecondition>5)						$__pricecondition = 1;
			if(!$__price)	$__price = 0;
				else	$__price = ((int)$__price);
					
			$visibility = array(
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH
			);
			//get all products and finding related
			$collection=Mage::getModel("catalog/product")
				->getCollection()
				->addAttributeToSelect('*')
				->addAttributeToFilter('visibility', $visibility)
				->addAttributeToFilter('status',array("in"=>Mage::getSingleton("catalog/product_status")->getVisibleStatusIds()));
			//removing itself from collection
			
			if($this->getProduct())
				$collection->addAttributeToFilter("entity_id",array("neq"=>$this->getProduct()->getId()));
			
			//Source filter
			if($__source==1)	$collection->addStoreFilter(Mage::app()->getStore()->getCode());
				else if(!is_null(Mage::registry("current_category"))) $collection->addCategoryFilter(Mage::registry("current_category"));
			
			//Select products filter
			switch($__selectproducts)	{
				case 1://random selection of products
					$collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
					break;
				case 2://last added products
					$collection->addAttributeToSort('created_at','desc');
					break;
				case 3://lexically similar
					$allWords=explode(" ",$this->getProduct()->getName());
					$exprArray=array();
					for($i=0;$i<count($allWords);$i++)	
						if(strlen($allWords[$i])>=3)
							$exprArray[]=array('attribute'=>'name','like'=>$this->escape('%'.$allWords[$i].'%'));
					unset($allWords);
					$collection->addAttributeToFilter("name",array('or'=>$exprArray));
					break;
			}
			
			//Filtering by price
			switch($__pricecondition)	{
				case 2://Less than
					$collection->addAttributeToFilter("price",array("lt"=>$__price));
					break;
				case 3://Not less than
					$collection->addAttributeToFilter("price",array("moreq"=>$__price));
					break;
				case 4://More than
					$collection->addAttributeToFilter("price",array("gt"=>$__price));
					break;
				case 5://Not more than
					$collection->addAttributeToFilter("price",array("lteq"=>$__price));
					break;
			}
			
			//Fetch only in stock filter
			if($__fetchinstock)
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
			//Limiting count to number of items
			$collection->setPageSize($__itemscount);
			$collection->setCurPage(1);
			
			foreach ($collection as $item) {
				$this->_items[$item->getEntityId()] = $item;
			}
		}
		return $this->_items;
	}
	
	public function getType()
    {
        return Enterprise_TargetRule_Model_Rule::RELATED_PRODUCTS;
    }

    public function getExcludeProductIds()
    {
        if (is_null($this->_excludeProductIds)) {
            $cartProductIds = Mage::getSingleton('checkout/cart')->getProductIds();
            $this->_excludeProductIds = array_merge($cartProductIds, array($this->getProduct()->getEntityId()));
        }
        return $this->_excludeProductIds;
    }
 }