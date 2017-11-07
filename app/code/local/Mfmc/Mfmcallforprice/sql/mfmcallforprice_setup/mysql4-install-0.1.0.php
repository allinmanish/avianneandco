<?php

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('mfmc_mfmcallforprice')} (
  `id` int(10) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned default NULL,
  `hide_price` tinyint(3) unsigned default NULL,
  `message` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");

$installer->endSetup(); 