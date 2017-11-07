<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento community edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento community edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Followupemail
 * @version    3.5.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Sendcopy extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $data = Mage::registry('followupemail_data');

        if (is_object($data)) $data = $data->getData();

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('followupemail_sendcopy', array('legend' => $this->__('Send copy to email')));

        # email_copy_to field
        $fieldset->addField('email_copy_to', 'text', array(
            'label' => $this->__('Send copy to email'),
            'name' => 'email_copy_to',
            'note' => $this->__('Separate e-mails by spaces, commas, or semicolons'),
            'after_element_html' =>
            '<span class="note"><small>'
                . $this->__('These addresses will be added to the BCC: fields of the emails generated by the rule')
                . '</small></span>',
        ));

        $fieldset->addField('email_send_to_customer', 'select', array(
            'label' => 'Send email to customer',
            'name' => 'email_send_to_customer',
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'onchange' => 'checkSendToCustomer()',
            'note' => $this->__('If "No" is selected, email will be sent to recipients specified in the "Send copy to email" field only')
        ));

        if (!isset($data['email_send_to_customer'])) $data['email_send_to_customer'] = 1;

        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
