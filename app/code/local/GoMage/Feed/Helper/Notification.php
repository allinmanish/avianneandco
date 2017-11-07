<?php
/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2012 GoMage.com (http://www.gomage.com)
 * @author       GoMage.com
 * @license      http://www.gomage.com/licensing  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.0
 * @since        Class available since Release 3.0
 */

class GoMage_Feed_Helper_Notification extends Mage_Core_Helper_Abstract {
		
	public function sendMessage($feed, $message, $notify_type) 
    {
    	if (!Mage::getStoreConfig('gomage_feedpro/notifications/enabled', $feed->getStoreId())){
    		return $this;
    	}    	
    	$notify_types = explode(',', Mage::getStoreConfig('gomage_feedpro/notifications/notify', $feed->getStoreId()));    	
    	if (!in_array($notify_type, $notify_types)){
    		return $this;
    	}
    	
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);

        $mailTemplate = Mage::getModel('core/email_template');

        $template = Mage::getStoreConfig('gomage_feedpro/notifications/email_template', $feed->getStoreId());

        $copyTo = $this->_getEmails('gomage_feedpro/notifications/copy_to', $feed->getStoreId());
        $copyMethod = Mage::getStoreConfig('gomage_feedpro/notifications/copy_method', $feed->getStoreId());
        if ($copyTo && $copyMethod == 'bcc') {
            $mailTemplate->addBcc($copyTo);
        }

        $_reciever = Mage::getStoreConfig('gomage_feedpro/notifications/reciever', $feed->getStoreId());
        $sendTo = array(
            array(
                'email' => Mage::getStoreConfig('trans_email/ident_'.$_reciever.'/email', $feed->getStoreId()),
                'name'  => Mage::getStoreConfig('trans_email/ident_'.$_reciever.'/name', $feed->getStoreId())
            )
        );

        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $sendTo[] = array(
                    'email' => $email,
                    'name'  => null
                );
            }
        }
        
        $data = array(
    		'id' => $feed->getId(),
			'name' => $feed->getName(),
			'message' => $message
        );
        
        $data_object = new Varien_Object();
        $data_object->setData($data); 
        
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$feed->getStoreId()))
                ->sendTransactional(
                    $template,
                    Mage::getStoreConfig('gomage_feedpro/notifications/sender', $feed->getStoreId()),
                    $recipient['email'],
                    $recipient['name'],
                    array('data' => $data_object) 
                );
        }

        $translate->setTranslateInline(true);

        return $this;
    }

    protected function _getEmails($configPath, $storeId)
    {
        $data = Mage::getStoreConfig($configPath, $storeId);
        if (!empty($data)) {
            return explode(',', $data);
        }
        return false;
    } 
	

}
