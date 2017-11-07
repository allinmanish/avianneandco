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

class AW_Zblocks_Block_Adminhtml_Zblocks_Edit_Tab_Content_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    private $blockId = '';

    public function __construct()
    {
        parent::__construct();
        $this->setId('contentGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('block_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $this->blockId = $this->getRequest()->getParam('id'); 

        $model = Mage::getModel('zblocks/zblocks')->load($this->blockId);
        if ($this->blockId == $model->getId())
        {
            $collection = Mage::getModel('zblocks/content')->getCollection();
            $collection->getSelect()
                ->where('zblock_id=?', $this->blockId)
                ->order('sort_order');
            $this->setCollection($collection);
        }

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('title', array(
            'header'    => $this->__('Title'),
            'index'     => 'title',
            'align'     => 'left',
            'width'     => '100px',
        ));

        $this->addColumn('content', array(
            'header'    => $this->__('Content'),
            'index'     => 'content',
            'align'     => 'left',
            'type'      => 'text',
            'nl2br'     => 1,
            'escape'    => 1,
            'truncate'  => 250,
        ));

        $this->addColumn('sort_order', array(
            'header'    => $this->__('Sort Order'),
            'index'     => 'sort_order',
            'align'     => 'right',
            'width'     => '50px',
        ));

        $this->addColumn('is_active', array(
            'header'    => $this->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => $this->__('Disabled'),
                1 => $this->__('Enabled'),
            ),
        ));

        $this->addColumn('delete',
            array(
                'header'    => $this->__('Delete'),
                'width'     => '50',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => $this->__('Delete'),
                        'url'       => array('base' => '*/*/deleteContent'),
                        'field'     => 'id',
                        'confirm'   => $this->__('Are you sure you want to delete the block?'),
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/editContent', array('id' => $row->getId(), 'block_id' => $this->blockId));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/editGrid', array('id' => $this->blockId, '_current' => true));
    }

}