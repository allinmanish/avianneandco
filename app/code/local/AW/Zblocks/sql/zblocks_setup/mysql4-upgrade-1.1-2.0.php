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
?>
<?php

$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE {$this->getTable('zblocks/zblocks')} (
 `zblock_id` int(10) NOT NULL auto_increment,
 `block_title` varchar(255) NOT NULL,
 `block_position` varchar(255) NOT NULL,
 `block_position_custom` varchar(255) default NULL,
 `store_ids` varchar(255) NOT NULL,
 `category_ids` varchar(255) NOT NULL,
 `creation_time` datetime default NULL,
 `update_time` datetime default NULL,
 `block_is_active` tinyint(1) NOT NULL default '1',
 `schedule_from_date` date default '0000-00-00',
 `schedule_to_date` date default '0000-00-00',
 `schedule_pattern` varchar(255) default NULL,
 `schedule_from_time` time default NULL,
 `schedule_to_time` time default NULL,
 `block_sort_order` int(10) NOT NULL default '0',
 `rotator_mode` tinyint(1) NOT NULL default '0',
 PRIMARY KEY  (`zblock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE {$this->getTable('zblocks/content')} (
 `block_id` int(10) NOT NULL auto_increment,
 `zblock_id` int(10) NOT NULL,
 `title` varchar(255) default NULL,
 `content` text,
 `is_active` tinyint(1) NOT NULL default '1',
 `sort_order` int(10) NOT NULL default '0',
 PRIMARY KEY  (`block_id`),
 KEY `FK_zblocks` (`zblock_id`),
 CONSTRAINT `FK_zblocks` FOREIGN KEY (`zblock_id`) REFERENCES {$this->getTable('zblocks/zblocks')} (`zblock_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();