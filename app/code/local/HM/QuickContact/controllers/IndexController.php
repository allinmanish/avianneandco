<?php

class HM_QuickContact_IndexController extends Mage_Core_Controller_Front_Action
{		
    const XML_PATH_EMAIL_RECIPIENT  = 'quickcontact/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'quickcontact/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'quickcontact/email/email_template';
	const XML_PATH_REPLY_EMAIL_TEMPLATE   = 'quickcontact/auto_reply/email_template';
	const XML_PATH_EMAIL_INCLUDE_TITLE_LINK   = 'quickcontact/email/include_title_link';
	
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('quickcontactForm');
        $this->renderLayout();
    }
	    
    public function postAction()
    {				
		$post = $this->getRequest()->getPost();		
		if(!$post) exit;
		$translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		try {				
                $postObject = new Varien_Object();
                $postObject->setData($post);

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    echo '<div class="error-msg">'.Mage::helper('contacts')->__('Please fill in required fields.').'</div>';
					exit; 
                }

                if (!Zend_Validate::is(trim($post['content']) , 'NotEmpty')) {
                    echo '<div class="error-msg">'.Mage::helper('contacts')->__('Please fill in required fields.').'</div>';
					exit; 
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    echo '<div class="error-msg">'.Mage::helper('contacts')->__('Please enter a valid email address. For example johndoe@domain.com.').'</div>';
					exit; 
                }
	
				// if ($postObject['codemd5'] != ''){
				// if (Mage::getStoreConfig("quickcontact/recaptcha/enabled")!=false){		
					// if(md5($post['security_code']) != $post['codemd5']) {
						// echo '<div class="error-msg">'.Mage::helper('quickcontact')->__('The security code is incorrect.').'</div>';
						// exit;						
					// }					
				// }}
				
				if (Mage::getStoreConfig("quickcontact/recaptcha/enabled")!=false){	
					$captcha = Mage::getModel('quickcontact/captcha');
					$privatekey = Mage::getStoreConfig("quickcontact/recaptcha/private_key");
					if (isset($post["recaptcha_response_field"])) {
						$resp = $captcha->recaptcha_check_answer ($privatekey,
														$_SERVER["REMOTE_ADDR"],
														$_POST["recaptcha_challenge_field"],
														$_POST["recaptcha_response_field"]);

						if ($resp->is_valid) {
						} else {
								echo '<div class="error-msg">'.Mage::helper('quickcontact')->__('The security code is incorrect.').'</div>';
								exit;
						}
					}
				}
				
				if (!isset($postObject['phone']) || strlen($postObject['phone'])<1) {
					$postObject['phone'] = '';
				}
				
				if (!Mage::getStoreConfig(self::XML_PATH_EMAIL_INCLUDE_TITLE_LINK)) {
					$postObject['page_title'] = '';
					$postObject['page_link'] = '';
				}
               		
				$mailTemplate = Mage::getModel('core/email_template');
				
				if (isset($postObject['send_email_to']) && $postObject['send_email_to']!=''):
					$sendto = $post['send_email_to'];
				else:
					$sendto = Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT);
				endif;
				
				if ($postObject['subject']==''):
					$postObject['subject'] = Mage::getStoreConfig('quickcontact/email/default_subject');
				endif;
				
				if ($postObject['contact_type_title']==''):
					$postObject['contact_type_title'] = Mage::getStoreConfig('quickcontact/email/default_title');
				endif;
				
				$length = '6';
				$var = "0123456789";
				srand((double)microtime()*1000000);
				$i = 0;    $emailid = '' ;
				while ($i < $length) {
					$num = rand() % 10;
					$tmp = substr($var, $num, 1);
					$emailid = $emailid . $tmp;
					$i++;		
				}
				$postObject['emailid'] = $emailid;
				$from = array( 'name' =>  $post['name'], 'email' => $post['email']);

				$mailTemplate->setDesignConfig(array('area' => 'frontend'))
					->setReplyTo($post['email'])
					->sendTransactional(
						Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
						$from,
						$sendto,
						null,
						array('data' => $postObject)
					);
				
				Mage::log(Mage::getStoreConfig("quickcontact/auto_reply/enable"));
				if (Mage::getStoreConfig("quickcontact/auto_reply/enable") == true) {
					
					$replyMailTemplate = Mage::getModel('core/email_template');
					$replyPostObject = $postObject;
					$replyPostObject['reply'] = Mage::getStoreConfig("quickcontact/auto_reply/content");
					if (Mage::getStoreConfig("quickcontact/auto_reply/append")) 
					$replyPostObject['append'] = Mage::getStoreConfig("quickcontact/auto_reply/append");
					$replyMailTemplate->setDesignConfig(array('area' => 'frontend'))
					->setReplyTo($post['email'])
					->sendTransactional(
						Mage::getStoreConfig(self::XML_PATH_REPLY_EMAIL_TEMPLATE),
						Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
						$post['email'],
						null,
						array('data' => $replyPostObject)
					);
				}

				if (!$mailTemplate->getSentSuccess()) {					
					echo '<div class="error-msg">'.Mage::helper('contacts')->__('Unable to submit your request. Please, try again later.').'</div>';
					exit;
				}				
				$translate->setTranslateInline(true);

                echo '<div class="success-msg">'.Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.').'</div>';
			} catch (Exception $e) {
				$translate->setTranslateInline(true);
				echo '<div class="error-msg">'.Mage::helper('contacts')->__('Unable to submit your request. Please, try again later.').$e.'</div>';
				exit;
			}		
	}	
}
?>