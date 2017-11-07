<?php 
class WSM_Reviews_Block_List extends Mage_Core_Block_Template {
	public function getModel(){
		return Mage::getModel('customreviews/customreviews');
	}
}