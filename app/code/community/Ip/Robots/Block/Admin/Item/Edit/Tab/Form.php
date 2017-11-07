<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Ip.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Ip_Robots_Block_Admin_Item_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {


    protected function _prepareForm() {
        $model = Mage::registry('robots_item');
        $form = new Varien_Data_Form(array('id' => 'edit_form_item', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('robots')->__('General Information'), 'class' => 'fieldset-wide'));
        if ($model->getItemId()) {
            $fieldset->addField('item_id', 'hidden', array(
                'name' => 'item_id',
            ));
        }

//        if (!Mage::app()->isSingleStoreMode()) {
//            $fieldset->addField('store_id', 'multiselect', array(
//                'name' => 'stores[]',
//                'label' => Mage::helper('robots')->__('Store View'),
//                'title' => Mage::helper('robots')->__('Store View'),
//                'required' => true,
//                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
//            'style' => 'height:150px',
//            ));
//        } else {
//            $fieldset->addField('store_id', 'hidden', array(
//                'name' => 'stores[]',
//                'value' => Mage::app()->getStore(true)->getId()
//            ));
//            $model->setStoreId(Mage::app()->getStore(true)->getId());
//        }


        $fieldset->addField('type', 'select', array(
            'label' => Mage::helper('robots')->__('Type'),
            'title' => Mage::helper('robots')->__('Type'),
            'name' => 'type',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('robots')->__('Allow'),
                '0' => Mage::helper('robots')->__('Disallow'),
            ),
        ));
        
        $fieldset->addField('url', 'text', array(
            'name' => 'url',
            'label' => Mage::helper('robots')->__('Url'),
            'title' => Mage::helper('robots')->__('Url'),
            'required' => false,
        ));      
          
       $fieldset->addField('comment', 'text', array(
            'name' => 'comment',
            'label' => Mage::helper('robots')->__('Comment'),
            'title' => Mage::helper('robots')->__('Comment'),
            'required' => false,
        ));
        
        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('robots')->__('Status'),
            'title' => Mage::helper('robots')->__('Status'),
            'name' => 'is_active',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('robots')->__('Enabled'),
                '0' => Mage::helper('robots')->__('Disabled'),
            ),
        ));

        if (Mage::helper('robots')->versionUseWysiwig()) {
            $wysiwygConfig = Mage::getSingleton('robots/wysiwyg_config')->getConfig();
        } else {
            $wysiwygConfig = '';
        }

//        print_r($model->getData());
//        exit();
//        $form->setUseContainer(true);
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
