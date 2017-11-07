<?php
class Mage_Adminhtml_Block_Sales_Order_Bold extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row) {
		$value = $this->_getValue($row);
		return '<strong>'.$value.'</strong>';
	}
}
?>