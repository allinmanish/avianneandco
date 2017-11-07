<?php

class Livechat_Livechat_Block_Adminhtml_Livechat_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('livechatGrid');
      $this->setDefaultSort('date_added');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  } 
}