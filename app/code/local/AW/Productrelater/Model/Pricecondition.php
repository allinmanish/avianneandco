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

class AW_Productrelater_Model_Pricecondition	{
	const	PRICECONDITION_ITEM_ANY				=1;
	const	PRICECONDITION_ITEM_LESSTHAN		=2;
	const	PRICECONDITION_ITEM_NOTLESSTHAN		=3;
	const	PRICECONDITION_ITEM_MORETHAN		=4;
	const	PRICECONDITION_ITEM_NOTMORETHAN		=5;
	
	public function toOptionArray()	{
		return array(
			array('value' => self::PRICECONDITION_ITEM_ANY,   'label' => Mage::helper('productrelater')->__('Any')),
			array('value' => self::PRICECONDITION_ITEM_NOTMORETHAN,   'label' => Mage::helper('productrelater')->__('Equal or less than')),
			array('value' => self::PRICECONDITION_ITEM_LESSTHAN,   'label' => Mage::helper('productrelater')->__('Less than')),
			array('value' => self::PRICECONDITION_ITEM_NOTLESSTHAN,   'label' => Mage::helper('productrelater')->__('Equal or greater than')),
			array('value' => self::PRICECONDITION_ITEM_MORETHAN,   'label' => Mage::helper('productrelater')->__('Greater than')),
		);
	}
}