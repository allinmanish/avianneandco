<?php
/*
 *  Created on Aug 16, 2011
 *  Author Ivan Proskuryakov - volgodark@gmail.com
 *  Copyright Proskuryakov Ivan. Ip.com Â© 2011. All Rights Reserved.
 *  Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
 */
?>
<?php
$installer = $this;
$installer->startSetup();


$sql.= "
CREATE TABLE IF NOT EXISTS {$this->getTable('ip_robots_item')} (
  `item_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `url` text NOT NULL,
  `comment` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;
";

$cms_page = $this->getTable('cms_page');
$cms_page_store = $this->getTable('cms_page_store');
$page_id = rand(1000, 9999);
$sql.= '
INSERT INTO `'.$cms_page.'` (`page_id`, `title`, `root_template`, `meta_keywords`, `meta_description`, `identifier`, `content_heading`, `content`, `creation_time`, `update_time`, `is_active`, `sort_order`, `layout_update_xml`, `custom_theme`, `custom_root_template`, `custom_layout_update_xml`, `custom_theme_from`, `custom_theme_to`) VALUES
('.$page_id.', \'Magento development\', \'one_column\', \'magento development, magento developer, magento store development\', \'Wide range of services related to the e-shops development,\r\n		 their design and installation as well as their configuration on Magento e-commerce \r\n		 platform.\', \'magento-store-development\', \'Magento development\', \'<h3>PROFESSIONAL MAGENTO DEVELOPMENT</h3>\r\n<p>There are no limits to creativity with us and <strong>Magento</strong>. Control every facet of your store, from merchandising to promotions and more.&nbsp;We are <strong>professional developers </strong>we provide premium&nbsp;<a title=\"magento development\" href=\"http://www.ecommerceoffice.com/\">MAGENTO DEVELOPMENT</a>&nbsp;for Business, Corporate built with the latest innovations in web-development and SEO niche. You will pay less to go live with our wide range of solutions and services.</p>\r\n<p>Our magento programmers are ready to set up your online store starting from the very beginning. All you need to do is to tell us what kind of business you plan to organize and what products you intend to sell. As a company, we provide a wide range of services related to the e-shops <strong>development</strong>, their design and installation as well as their configuration on <strong>Magento </strong>e-commerce platform.</p>\r\n<p>As a company, we provide a wide range of services related to the e-shops <strong>development</strong>, their design and installation as well as their configuration on Magento e-commerce platform. The platform itself is extremely scalable and flexible, being suitable for the unique solutions on a &ldquo;turnkey&rdquo; basis implementation. Magento has the advantages of outstanding safety and reliability, thus satisfying the basic requirements of high quality online business needs.</p>\r\n<p>Given that the primary focus of the ecommerceoffice.com is an Online-business and development services. We offer our invitation to web-developers, web-designers, Business Owners, internet investors, entrepreneurs and organizations interested in developing websites, online stores and other forms of online business.</p>\r\n<p>In partnership, we focus on stability and reliability because it will achieve the best results when working together. As part of the partnership, we are ready to consider any form of cooperation, such as mediation services in attracting customers, win-win implementation of joint projects, providing favorable conditions of our systems and services, and so on.</p>\', \'2011-10-14 09:03:52\', \'2011-10-14 09:08:38\', 1, 0, NULL, NULL, \'\', NULL, NULL, NULL);

INSERT INTO `'.$cms_page_store.'` (`page_id`, `store_id`) VALUES
('.$page_id.', 0);

'

;


$installer->run($sql);

$installer->endSetup();

Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('robots')->__('

robots.txt was successfully installed! 
Now you have 0 rules. 
If you want to add standard Magento rules go to "CMS"->"Robots.txt"->"Manage"->"Install standard rules for your Magento"


'));



?>