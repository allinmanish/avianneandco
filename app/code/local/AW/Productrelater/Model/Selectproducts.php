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

class AW_Productrelater_Model_Selectproducts	{
	const	SELPROD_ITEM_RANDOM		=1;
	const	SELPROD_ITEM_LAST		=2;
	const	SELPROD_ITEM_LEXICAL	=3;
	
	public function toOptionArray()	{
		return array(
			array('value' => self::SELPROD_ITEM_RANDOM,   'label' => Mage::helper('productrelater')->__('Random')),
			array('value' => self::SELPROD_ITEM_LAST,   'label' => Mage::helper('productrelater')->__('Last added')),
			array('value' => self::SELPROD_ITEM_LEXICAL,   'label' => Mage::helper('productrelater')->__('Lexically similar (can slow down page loading on huge inventories)')),
		);
	}
}