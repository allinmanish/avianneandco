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

class AW_Productrelater_Model_Sources	{
	const SOURCE_TYPE_CURRENT_STORE		=1;
	const SOURCE_TYPE_CURRENT_CATEGORY	=2;
	
	public function toOptionArray()	{
		return array(
			array('value' => self::SOURCE_TYPE_CURRENT_STORE,   'label' => Mage::helper('productrelater')->__('Current store')),
            array('value' => self::SOURCE_TYPE_CURRENT_CATEGORY,  'label' => Mage::helper('productrelater')->__('Current category')), 
		);
	}
}