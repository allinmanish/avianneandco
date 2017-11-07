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
 * @package    AW_All
 * @copyright  Copyright (c) 2003-2009 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */ 


class HM_QuickContact_Block_Info extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
		
		$html = $this->_getHeaderHtml($element);
		
		$html.= $this->_getFieldHtml($element);
        
        $html .= $this->_getFooterHtml($element);

        return $html;
    }

   

	protected function _getFieldHtml($fieldset)
    {
		$content = 'This extension is developed by <a href="http://mage-world.com/">Mage-World.com</a>';
		return $content;
    }
}
