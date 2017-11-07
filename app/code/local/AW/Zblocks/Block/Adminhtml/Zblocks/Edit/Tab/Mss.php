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

class AW_Zblocks_Block_Adminhtml_Zblocks_Edit_Tab_Mss extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('zblocks_mss', array('legend' => $this->__('Market Segmentation Suite Rule')));

        if(Mage::helper('zblocks')->isMSSInstalled())
        {
            $mssRules = array();
            $ruleCollection = Mage::getModel('marketsuite/filter')->getRuleCollection();

            $mssRules = array(
                array(
                    'value' => 0,
                    'label' => '',
            ));

            foreach($ruleCollection as $rule)
                if($rule->getIsActive())
                    $mssRules[] = array(
                                        'value' => $rule->getId(),
                                        'label' => $rule->getName(),
                                    );

            $fieldset->addField('mss_rule_id', 'select', array(
                    'label'     => $this->__('Validate the block by MSS rule'),
                    'name'      => 'mss_rule_id',
                    'values'    => $mssRules,
                    'note'      => $this->__('Only active MSS rules are listed here'),
                ));
        }
        else
        {
            $fieldset->addField('mss_rule_id', 'hidden', array(
                    'name'   => 'mss_rule_id',
                ));

            $fieldset->addField('mss_warning', 'note', array(
                    'text'  => 'You can considerably increase functionality of Z-Blocks by installing the <strong>Market Segmentation Suite</strong> extension.
To get more information, please visit <a href="http://ecommerce.aheadworks.com/market-segmentation-suite.html">extension page</a>',
                ));
        }

        if($data = Mage::registry('zblocks_data')) $form->setValues($data);

        return parent::_prepareForm();
    }
}