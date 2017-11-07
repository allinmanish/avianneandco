<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Ip.com © 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php

class Ip_Robots_Block_Admin_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('RobotsGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('robots/item')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $baseUrl = $this->getUrl();
        $this->addColumn('item_id', array(
            'header' => Mage::helper('robots')->__('ID'),
            'align' => 'left',
            'width' => '30px',
            'index' => 'item_id',
        ));

        $this->addColumn('type', array(
            'header' => Mage::helper('robots')->__('Type'),
            'index' => 'type',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('robots')->__('Allow'),
                '0' => Mage::helper('robots')->__('Disallow'),
            ),
        ));                
        $this->addColumn('url', array(
            'header' => Mage::helper('robots')->__('Url'),
            'align' => 'left',
            'index' => 'url',
        ));
        $this->addColumn('comment', array(
            'header' => Mage::helper('robots')->__('Comment'),
            'align' => 'left',
            'index' => 'comment',
        ));

        $this->addColumn('is_active', array(
            'header' => Mage::helper('robots')->__('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('robots')->__('Disabled'),
                1 => Mage::helper('robots')->__('Enabled'),
            ),
        ));

        $this->addColumn('action',
                array(
                    'header' => Mage::helper('robots')->__('Action'),
                    'index' => 'item_id',
                    'sortable' => false,
                    'filter' => false,
                    'no_link' => true,
                    'width' => '100px',
                    'renderer' => 'robots/admin_item_grid_renderer_action'
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('robots')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('robots')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('massaction');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('robots')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('robots')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('robots')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('robots')->__('Status'),
                    'values' => array(
                        0 => Mage::helper('robots')->__('Disabled'),
                        1 => Mage::helper('robots')->__('Enabled'),
                    ),
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('item_id' => $row->getId()));
    }

}
