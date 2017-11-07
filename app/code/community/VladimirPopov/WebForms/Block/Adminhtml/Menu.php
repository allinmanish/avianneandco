<?php
/**
 * Feel free to contact me via Facebook
 * http://www.facebook.com/rebimol
 *
 *
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2012 Vladimir Popov
 */

class VladimirPopov_WebForms_Block_Adminhtml_Menu extends Mage_Adminhtml_Block_Page_Menu
{
	public function getMenuArray()
	{
		//Load standard menu
		$parentArr = parent::getMenuArray();
		
		if(!empty($parentArr['webforms'])){
			$collection = Mage::getModel('webforms/webforms')->getCollection()
				->addFilter('menu','1');
			$collection->getSelect()->order('name asc');
			
			//Update all previous menu items 
			foreach($parentArr['webforms']['children'] as $i=>$item){
				$parentArr['webforms']['children'][$i]['last'] = false;
			}
			
			$i=0;
			
			foreach($collection as $webform){
				$menuitem = array(
					'label' => $webform->getName(),
					'active' => false ,
					'sort_order' => $i++ * 10,
					'level' => 1,
					'url' => $this->getUrl('webforms/adminhtml_results',array('webform_id'=>$webform->getId()))
				);
				$parentArr['webforms']['children'][]= $menuitem;
			}
			
			$configItem    = array(
				'label' => $this->__('Forms Settings'),
				'active' => false ,
				'sort_order' => $i++ * 10,
				'level' => 1,
				'url' => $this->getUrl('adminhtml/system_config/edit/section/webforms'),
				'last' => true
			);
			
			$parentArr['webforms']['children'][]= $configItem;
		}
		
		Mage::dispatchEvent('webforms_adminhtml_menu_get_menu_array',array('parentArr'=>$parentArr));
		
		return $parentArr;
	}
}