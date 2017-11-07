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

class AW_Productrelater_Block_List extends Mage_Catalog_Block_Product_Abstract	{
	public function __construct()	{
		if(!$this->getTemplate())	$this->setTemplate("productrelater/productrelater_block.phtml");
	}
	
	public function getProduct(){
		if(Mage::registry('product'))
			return Mage::registry('product');
		return isset($this->_data['product']) ? $this->_data['product'] : Mage::registry('aw_pq_product');
	}
	
	function escape($str)	{
		$str=Mage::getSingleton('core/resource')->getConnection('core_read')->quote($str);
		return substr($str,1,-1);
	}
	
	public function _toHtml()	{
		//Loading extension configuration.
		//If some options are setted from block call they has higher priority than default values
		$myConf=Mage::getStoreConfig("productrelater");
		if(!$this->getItemscount())			$this->setItemscount($myConf["mainoptions"]["itemscount"]);
		if(!$this->getSource())				$this->setSource($myConf["mainoptions"]["source"]);
		if(!$this->getSelectproducts())		$this->setSelectproducts($myConf["mainoptions"]["selectproducts"]);
		if(!$this->getFetchinstock())		$this->setFetchinstock($myConf["mainoptions"]["fetchinstock"]);
		if(!$this->getPricecondition())		$this->setPricecondition($myConf["mainoptions"]["pricecondition"]);
		if(!$this->getPrice())				$this->setPrice($myConf["mainoptions"]["price"]);
		$this->setReplaceoriginalrp($myConf["mainoptions"]["replaceoriginalrp"]);
		
		//don`t display anything when rewriting standard related products is enabled
		if($this->getNameInLayout()==="productrelater_list"&&$this->getReplaceoriginalrp()) return;
		
		if(!(int)$this->getItemscount()||(int)$this->getItemscount()<1)				$this->setItemscount(3);
		if((int)$this->getSource()!=1&&(int)$this->getSource()!=2)					$this->setSource(1);
		if(is_null(Mage::registry("current_category"))) 							$this->setSource(1);
		if((int)$this->getSelectproducts()<1||(int)$this->getSelectproducts()>3)	$this->setSelectproducts(2);
		if((int)$this->getFetchinstock()<0||(int)$this->getFetchinstock()>1)		$this->setFetchinstock(0);
		if((int)$this->getPricecondition()<1||(int)$this->getPricecondition()>5)	$this->setPricecondition(1);
		if(!$this->getPrice())	$this->setPrice(0);
			else	$this->setPrice((int)$this->getPrice());
		
		//get all products and finding related
		if(!$this->getProduct()&&$this->getSelectproducts()==3)	$this->setCount(0);
			else	{
			$visibility = array(
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH
			);
			$collection=Mage::getModel("catalog/product")
				->getCollection()
				->addAttributeToSelect('*')
				->addAttributeToFilter('visibility', $visibility)
				->addAttributeToFilter('status',array("in"=>Mage::getSingleton("catalog/product_status")->getVisibleStatusIds()));
			//removing itself from collection
			if($this->getProduct())
				$collection->addAttributeToFilter("entity_id",array("neq"=>$this->getProduct()->getId()));
			//Source filter
			if($this->getSource()==1)	$collection->addStoreFilter(Mage::app()->getStore()->getCode());
				else if(!is_null(Mage::registry("current_category"))) $collection->addCategoryFilter(Mage::registry("current_category"));
			//Select products filter
			switch($this->getSelectproducts())	{
				case 1://random selection of products
					$collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
					break;
				case 2://last added products
					$collection->addAttributeToSort('created_at','desc');
					break;
				case 3://lexically similar
					if(!$this->getProduct()) break;
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
			switch($this->getPricecondition())	{
				case 2://Less than
					$collection->addAttributeToFilter("price",array("lt"=>$this->getPrice()));
					break;
				case 3://Not less than
					$collection->addAttributeToFilter("price",array("moreq"=>$this->getPrice()));
					break;
				case 4://More than
					$collection->addAttributeToFilter("price",array("gt"=>$this->getPrice()));
					break;
				case 5://Not more than
					$collection->addAttributeToFilter("price",array("lteq"=>$this->getPrice()));
					break;
			}
			//Fetch only in stock filter
			if($this->getFetchinstock())
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection); 
			//Limiting count to number of items
			$collection->setPageSize($this->getItemscount());
			$collection->setCurPage(1);
			$this->setItems($collection);
			$this->setCount(count($collection));
		}
		return parent::_toHtml();
	}
}