-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 27, 2011 at 04:36 AM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-1ubuntu9.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `magento1420`
--

-- --------------------------------------------------------

--
-- Table structure for table `ip_megamenusidebar_category`
--

CREATE TABLE IF NOT EXISTS `ip_megamenusidebar_category` (
  `category_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `url` text NOT NULL,
  `column` tinyint(4) NOT NULL DEFAULT '2',
  `position` tinyint(4) NOT NULL DEFAULT '0',
  `align_category` varchar(10) NOT NULL DEFAULT 'left',
  `align_content` varchar(10) NOT NULL DEFAULT 'right',
  `content_top` text CHARACTER SET utf8 COLLATE utf8_bin,
  `content_bottom` text CHARACTER SET utf8 COLLATE utf8_bin,
  `from_time` datetime DEFAULT NULL,
  `to_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ip_megamenusidebar_category`
--

INSERT INTO `ip_megamenusidebar_category` (`category_id`, `catalog_id`, `title`, `url`, `column`, `position`, `align_category`, `align_content`, `content_top`, `content_bottom`, `from_time`, `to_time`, `is_active`) VALUES
(2, 13, 'Electronics', '#', 3, 5, 'left', 'left', 0x3c703e3c7374726f6e673e3220636f6c756d6e732063617465676f7279206c61796f75743c2f7374726f6e673e3c2f703e0d0a3c703e596f752063616e20636f6d706c6574656c79206368616e6765206d656e75206f7220796f752063616e206c656176652069742061732069732c2074686973206d656e7520646f6573206e6f74206769766520796f7520616e792074726f75626c652e20416c6c207061727473206f66204d6567614d656e752061726520636f6d706c6574656c79206368616e676561626c6520696e207468652061646d696e6973747261746976652073656374696f6e2c20796f752063616e20616c736f206368616e6765206e616d6573206f662075726c206c696e6b732e2054686973206d656e75206973207265616c6c7920636f6e76696e69656e742e3c2f703e, '', '2009-11-06 10:46:36', '2011-03-12 02:45:00', 1),
(5, 10, 'Furniture', '#', 2, 2, 'left', 'left', '', 0x3c703e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c2f703e0d0a3c703e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e4d6567616d656e75322d2667743b4974656d732d2667743b4974656d4e616d652d2667743b436f6e74656e7420466f6f7465723c2f7370616e3e3c2f703e0d0a3c703e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c2f703e, '2011-02-27 16:47:02', NULL, 1),
(6, 18, 'Apparel', '#', 1, 12, 'left', 'left', '', 0x3c703e4353533320616e642043726f73732042726f7773657220537570706f72742e3c2f703e, '2001-03-12 16:52:09', '2016-03-31 16:52:09', 1),
(7, 3, 'Root', '#', 3, 1, 'left', 'left', 0x3c703e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c2f703e0d0a3c703e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e546f206368616e6765206974656d2068656164657220676f20746f204d6567616d656e75322d2667743b4974656d732d2667743b4974656d4e616d652d2667743b436f6e74656e7420546f703c6272202f3e3c2f7370616e3e3c2f703e0d0a3c703e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c7370616e207374796c653d22666f6e742d73697a653a20736d616c6c3b223e2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c2f7370616e3e3c2f703e, '', '2011-03-13 23:18:23', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ip_megamenusidebar_category_store`
--

CREATE TABLE IF NOT EXISTS `ip_megamenusidebar_category_store` (
  `category_id` smallint(6) unsigned DEFAULT NULL,
  `store_id` smallint(6) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ip_megamenusidebar_category_store`
--

INSERT INTO `ip_megamenusidebar_category_store` (`category_id`, `store_id`) VALUES
(6, 0),
(2, 0),
(5, 0),
(7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ip_megamenusidebar_item`
--

CREATE TABLE IF NOT EXISTS `ip_megamenusidebar_item` (
  `item_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `column` tinyint(4) NOT NULL DEFAULT '2',
  `align_item` varchar(10) NOT NULL DEFAULT 'left',
  `align_content` varchar(10) NOT NULL DEFAULT 'right',
  `position` tinyint(10) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `from_time` datetime DEFAULT NULL,
  `to_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `columnsize` tinyint(4) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `ip_megamenusidebar_item`
--

INSERT INTO `ip_megamenusidebar_item` (`item_id`, `title`, `url`, `column`, `align_item`, `align_content`, `position`, `content`, `from_time`, `to_time`, `is_active`, `columnsize`) VALUES
(5, 'Lists', '#', 4, 'right', 'right', 1, '<h4>Paragraphs with Borders</h4>\r\n<p class="dark">Nulla quis metus a dolor feugiat porta. Phasellus sapien magna, gravida congue fermentum vel, gravida sit amet sapien. Quisque elit est, ullamcorper ac hendrerit eget, porta id enim. Phasellus in velit velit.</p>\r\n<p class="brown">Praesent a dolor sem. Sed scelerisque, tellus id pulvinar tristique, erat eros rutrum mi, id adipiscing arcu sem vel est. Ut ac turpis ipsum. Mauris leo nulla, vestibulum id bibendum nec, auctor eu quam.</p>\r\n<p class="yellow">Nulla quis metus a dolor feugiat porta. Phasellus sapien magna, gravida congue fermentum vel, gravida sit amet sapien. Quisque elit est, ullamcorper ac hendrerit eget, porta id enim. Phasellus in velit velit.</p>\r\n<p class="red">Curabitur vulputate, massa sit amet mollis sodales, arcu quam scelerisque augue, ac elementum velit mauris ac tellus. Nunc dapibus mollis ante a sollicitudin. Nullam adipiscing ante.</p>\r\n<p class="blue">Quisque varius, erat nec fermentum aliquam, erat urna venenatis orci, in semper lorem ante at dolor. Quisque scelerisque mattis magna ut lobortis. Cras porttitor scelerisque ligula eget condimentum.</p>\r\n<p class="green">Vestibulum sed elit ut arcu. Donec leo dui, mollis ut volutpat et, suscipit a mauris. Etiam molestie, purus id dapibus volutpat, nunc nisl ornare odio, sit amet ornare. Maecenas rutrum venenatis diam ac luctus.</p>', '2011-03-09 12:00:00', '2011-03-12 12:00:00', 1, 0),
(9, 'Video', '#', 3, 'right', 'right', 9, '<b>Welcome to Magento</b>\r\n<iframe width="450" height="320" frameborder="0" allowfullscreen="" src="http://www.youtube.com/embed/BBvsB5PcitQ" title="YouTube video player"></iframe>', '2011-03-14 01:52:21', '2016-07-14 01:52:21', 1, 0),
(10, 'Forms', '#', 3, 'left', 'left', 1, '<form id="contactForm" action="http://magento1420.dev/contacts/index/post/" method="post">\r\n<h2 class="legend">Contact Information</h2>\r\n<ul class="form-list">\r\n<li>\r\n<div class="field"><label class="required" for="name"><em>*</em>Name</label>\r\n<div class="input-box"><input id="name" class="input-text" title="Email" name="name" type="text" value="John Doe" /></div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class="field"><label class="required" for="email"><em>*</em>Email</label>\r\n<div class="input-box"><input id="email" class="input-text" title="Email" name="email" type="text" value="sample@mail.com" /></div>\r\n</div>\r\n</li>\r\n<li>\r\n<div class="field"><label class="required" for="name"><em>*</em>Telephone</label>\r\n<div class="input-box"><input id="telephone" class="input-text" title="Telephone" name="telephone" type="text" value="+1 0000 0000" /></div>\r\n</div>\r\n</li>\r\n<li class="wide"> <label class="required" for="comment"><em>*</em>Comment</label>\r\n<div class="input-box"><textarea id="comment" class="required-entry input-text" title="Comment" cols="5" rows="3" name="comment">Write message here ... </textarea></div>\r\n</li>\r\n</ul>\r\n<div class="buttons-set">\r\n<p class="required">* Required Fields0</p>\r\n<input id="hideit" style="display: none !important;" name="hideit" type="text" /> <button class="button" title="Submit"><span><span>Submit</span></span></button></div>\r\n</form>', '2011-03-14 09:16:00', '2015-03-14 02:16:45', 0, 0),
(12, 'Tables', '#', 4, 'right', 'right', 1, '<h4>This is a dark table</h4>\r\n<table id="table_dark" cellspacing="0">\r\n<tbody>\r\n<tr>\r\n<th>Title 1</th><th>Title 2</th><th>Title 3</th><th>Title 4</th>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h4>This is a light table</h4>\r\n<table id="table_light" cellspacing="0">\r\n<tbody>\r\n<tr>\r\n<th>Title 1</th><th>Title 2</th><th>Title 3</th><th>Title 4</th>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n<tr>\r\n<td>Cell 1</td>\r\n<td>Cell 2</td>\r\n<td>Cell 3</td>\r\n<td>Cell 4</td>\r\n</tr>\r\n</tbody>\r\n</table>', '2011-03-26 21:01:46', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ip_megamenusidebar_item_store`
--

CREATE TABLE IF NOT EXISTS `ip_megamenusidebar_item_store` (
  `item_id` smallint(6) unsigned DEFAULT NULL,
  `store_id` smallint(6) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ip_megamenusidebar_item_store`
--

INSERT INTO `ip_megamenusidebar_item_store` (`item_id`, `store_id`) VALUES
(10, 0),
(5, 0),
(12, 0),
(9, 0);
