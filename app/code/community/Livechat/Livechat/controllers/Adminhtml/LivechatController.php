<?php
class Livechat_Livechat_Adminhtml_LivechatController extends Mage_Adminhtml_Controller_action
{
 
	public function indexAction() {
			
		$block = $this->getLayout()->createBlock(
			'Mage_Core_Block_Template',
			'livechat_config',
			array('template' => 'livechat/livechat_config.phtml')
		);
		
		$this->loadLayout()
			->_setActiveMenu('livechat')
			->_addContent($block)
			->renderLayout();
	}
	public function postAction() {
		
		//avoid notices warnings
		!isset($_POST['livechat_license_number']) ? $livechat_license_number = '' : $livechat_license_number = $_POST['livechat_license_number']; 
		!isset($_POST['livechat_lang']) ? $livechat_lang = 'en' : $livechat_lang = $_POST['livechat_lang'];  
		!isset($_POST['livechat_groups']) ? $livechat_groups = '0' : $livechat_groups = $_POST['livechat_groups'];  
		!isset($_POST['livechat_params']) ? $livechat_params = '' : $livechat_params = $_POST['livechat_params']; 
		
		$config_table = Mage::getSingleton('core/resource')->getTableName('core_config_data');
		
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$query = 'SELECT * FROM ' . $config_table;
		$query .= ' WHERE scope="default" AND scope_id=0 AND path="livechat/general/license"';
		$results = $read->fetchAll($query);

		$write = Mage::getSingleton('core/resource')->getConnection('core_write');

		//check for existing configurations
		if ($row = array_pop($results)) {
			$license_id = $row['config_id'];
			
			$query = 'UPDATE ' . $config_table;
			$query .= ' SET value="' . $livechat_license_number . '"';
			$query .= ' WHERE config_id=' . $license_id;
			$write->query($query);
			
			$query = 'UPDATE ' . $config_table;
			$query .= ' SET value="' . $livechat_lang . '"';
			$query .= ' WHERE config_id=' . ++$license_id;
			$write->query($query);
			
			$query = 'UPDATE ' . $config_table;
			$query .= ' SET value="' . $livechat_groups . '"';
			$query .= ' WHERE config_id=' . ++$license_id;
			$write->query($query);
			
			$query = 'UPDATE ' . $config_table;
			$query .= ' SET value="' . $livechat_params . '"';
			$query .= ' WHERE config_id=' . ++$license_id;
			$write->query($query);
		} else {
			$query = 'INSERT INTO ' . $config_table;
			$query .= ' (scope, scope_id, path, value)';
			$query .= ' VALUES ("default", 0, "livechat/general/license", "' . $livechat_license_number . '"),';
			$query .= ' ("default", 0, "livechat/advanced/language", "en"),';
			$query .= ' ("default", 0, "livechat/advanced/skill", "0"),';
			$query .= ' ("default", 0, "livechat/advanced/params", "")';
			$write->query($query);
		}
		
		$this->_redirect('*/*/index');
	}
}