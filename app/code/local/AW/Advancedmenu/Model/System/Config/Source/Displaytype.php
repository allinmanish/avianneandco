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
 * @package    AW_Advancedmenu
 * @copyright  Copyright (c) 2008-2009 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */

class AW_Advancedmenu_Model_System_Config_Source_Displaytype
{

    public function toOptionArray()
    {
        return array(
						array('value'=>'default', 'label'=>"Top, Default"),
            array('value'=>'dropdown', 'label'=>"Top, Dropdown"),
            array('value'=>'plain', 'label'=>"Top, Plain"),
            array('value'=>'advplain', 'label'=>"Top, Plain (advanced)"),
            array('value'=>'static', 'label'=>"Top, Static"),
            array('value'=>'l_dropdown', 'label'=>"Left, Dropdown"),						
            array('value'=>'l_folding', 'label'=>"Left, Folding"),
						array('value'=>'l_plain', 'label'=>"Left, Plain")
        );
    }

}
