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
 * @package    AW_Zblocks
 * @copyright  Copyright (c) 2008-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */

class AW_Zblocks_Model_Status extends Varien_Object
{
    const STATUS_DISABLED	= 0;
    const STATUS_ENABLED	= 1;

    /*
     * Returns Enabled/Disabled options array
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('zblocks')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('zblocks')->__('Disabled')
        );
    }
}