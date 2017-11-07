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

class AW_Zblocks_Block_Adminhtml_Zblocks_Edit_Tab_Content_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('zblocks_content_edit_form'); 
        $this->setTitle($this->__('Block Information'));
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/saveContent', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ));

        $fieldset = $form->addFieldset('zblocks_content', array('legend'=>$this->__('Content Item Information'), 'class' => 'fieldset-wide'));

        $fieldset->addField('zblock_id', 'hidden', array(
            'name'      => 'zblock_id',
        ));

        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => $this->__('Title'),
            'title'     => $this->__('Title'),
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => $this->__('Status'),
            'title'     => $this->__('Status'),
            'name'      => 'is_active',
            'options'   => array(
                1 => $this->__('Enabled'),
                0 => $this->__('Disabled'),
            ),
        ));

        $fieldset->addField('sort_order', 'text', array(
            'name'      => 'sort_order',
            'label'     => $this->__('Sort Order'),
            'style'     => 'width:274px !important;',
        ));

        $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'label'     => $this->__('Content'),
            'title'     => $this->__('Content'),
            'style'     => 'height:36em',
            'wysiwyg'   => false,
            'required'  => true,
        ));

        if($data = Mage::registry('zblocks_content')) $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}