-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Server version: 5.5.20
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gxc2`
--

-- --------------------------------------------------------

--
-- Table structure for table `gxc_auth_assignment`
--

CREATE TABLE IF NOT EXISTS `gxc_auth_assignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_auth_item`
--

CREATE TABLE IF NOT EXISTS `gxc_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_auth_item_child`
--

CREATE TABLE IF NOT EXISTS `gxc_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_autologin_tokens`
--

CREATE TABLE IF NOT EXISTS `gxc_autologin_tokens` (
  `user_id` bigint(20) NOT NULL,
  `token` char(40) NOT NULL,
  PRIMARY KEY (`user_id`,`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_block`
--

CREATE TABLE IF NOT EXISTS `gxc_block` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `created` int(11) DEFAULT '0',
  `creator` bigint(20) NOT NULL,
  `updated` int(11) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`block_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_comment`
--

CREATE TABLE IF NOT EXISTS `gxc_comment` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(255) NOT NULL DEFAULT '',
  `comment_author_url` varchar(255) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` int(11) NOT NULL,
  `comment_date_gmt` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_title` text,
  `comment_modified_content` text,
  PRIMARY KEY (`comment_id`),
  KEY `comment_approved` (`comment_approved`),
  KEY `comment_post_ID` (`object_id`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_company_category`
--

CREATE TABLE IF NOT EXISTS `gxc_company_category` (
  `companyId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `categoryId` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`companyId`,`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_company_cats`
--

CREATE TABLE IF NOT EXISTS `gxc_company_cats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `guid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `root` (`root`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_company_sphinxklist`
--

CREATE TABLE IF NOT EXISTS `gxc_company_sphinxklist` (
  `id` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_comp_store_layout`
--

CREATE TABLE IF NOT EXISTS `gxc_comp_store_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_contact_list`
--

CREATE TABLE IF NOT EXISTS `gxc_contact_list` (
  `owner_id` int(11) unsigned NOT NULL DEFAULT '0',
  `contact_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`owner_id`,`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_content_list`
--

CREATE TABLE IF NOT EXISTS `gxc_content_list` (
  `content_list_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`content_list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_favorite_product`
--

CREATE TABLE IF NOT EXISTS `gxc_favorite_product` (
  `productId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `userId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `notification` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`productId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_favorite_shop`
--

CREATE TABLE IF NOT EXISTS `gxc_favorite_shop` (
  `shopId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `userId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `notification` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`shopId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_language`
--

CREATE TABLE IF NOT EXISTS `gxc_language` (
  `lang_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(255) DEFAULT '',
  `lang_desc` varchar(255) DEFAULT '',
  `lang_required` tinyint(1) DEFAULT '0',
  `lang_active` tinyint(1) DEFAULT '0',
  `lang_short` varchar(10) NOT NULL,
  PRIMARY KEY (`lang_id`),
  KEY `lang_desc` (`lang_desc`),
  KEY `lang_name` (`lang_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_mailbox_conversation`
--

CREATE TABLE IF NOT EXISTS `gxc_mailbox_conversation` (
  `conversation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `initiator_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `bm_read` tinyint(3) NOT NULL DEFAULT '0',
  `bm_archived` tinyint(3) NOT NULL DEFAULT '0',
  `bm_spammed` tinyint(3) NOT NULL DEFAULT '0',
  `bm_deleted` tinyint(3) NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL,
  `is_system` enum('yes','no') NOT NULL DEFAULT 'no',
  `initiator_arch` tinyint(1) unsigned DEFAULT '0',
  `initiator_spam` tinyint(1) unsigned DEFAULT '0',
  `initiator_del` tinyint(1) unsigned DEFAULT '0',
  `initiator_restored` tinyint(1) unsigned DEFAULT '0',
  `initiator_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `initiator_flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`conversation_id`),
  KEY `initiator_id` (`initiator_id`),
  KEY `conversation_ts` (`modified`),
  KEY `subject` (`subject`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_mailbox_image`
--

CREATE TABLE IF NOT EXISTS `gxc_mailbox_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  `created_date` int(11) DEFAULT NULL,
  `update_date` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_mailbox_interlocutor`
--

CREATE TABLE IF NOT EXISTS `gxc_mailbox_interlocutor` (
  `conversation_id` int(11) unsigned NOT NULL DEFAULT '0',
  `interlocutor_id` int(11) unsigned NOT NULL DEFAULT '0',
  `interlocutor_del` tinyint(1) unsigned DEFAULT '0',
  `interlocutor_arch` tinyint(1) unsigned DEFAULT '0',
  `interlocutor_spam` tinyint(1) unsigned DEFAULT '0',
  `interlocutor_read` tinyint(1) unsigned DEFAULT '0',
  `interlocutor_flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`conversation_id`,`interlocutor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_mailbox_message`
--

CREATE TABLE IF NOT EXISTS `gxc_mailbox_message` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `sender_id` int(10) unsigned NOT NULL DEFAULT '0',
  `text` mediumtext NOT NULL,
  `crc64` bigint(20) NOT NULL,
  `sender_del` tinyint(1) unsigned DEFAULT '0',
  `sender_spam` tinyint(1) unsigned DEFAULT '0',
  `sender_flag` tinyint(1) unsigned DEFAULT '0',
  `sender_read` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`message_id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `timestamp` (`created`),
  KEY `crc64` (`crc64`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_mailbox_recipient`
--

CREATE TABLE IF NOT EXISTS `gxc_mailbox_recipient` (
  `message_id` int(11) unsigned NOT NULL DEFAULT '0',
  `recipient_id` int(11) unsigned NOT NULL DEFAULT '0',
  `recipient_del` tinyint(1) unsigned DEFAULT '0',
  `recipient_spam` tinyint(1) unsigned DEFAULT '0',
  `recipient_flag` tinyint(1) unsigned DEFAULT '0',
  `recipient_read` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`message_id`,`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_mailbox_spam`
--

CREATE TABLE IF NOT EXISTS `gxc_mailbox_spam` (
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `spammer_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`spammer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_membership_info`
--

CREATE TABLE IF NOT EXISTS `gxc_membership_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `order_date` int(11) NOT NULL,
  `end_date` int(11) DEFAULT NULL,
  `payment_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_membership_item`
--

CREATE TABLE IF NOT EXISTS `gxc_membership_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `rolename` varchar(64) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL COMMENT 'Price (when using membership)',
  `duration` int(11) DEFAULT NULL COMMENT '(how long a membership is valid)',
  `duration_type` tinyint(4) DEFAULT '0' COMMENT '(what type of duration will be days or years default is 0 - days)',
  `items_num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rolename` (`rolename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_membership_order`
--

CREATE TABLE IF NOT EXISTS `gxc_membership_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `order_num` varchar(100) NOT NULL,
  `order_date` int(11) NOT NULL,
  `end_date` int(11) DEFAULT NULL,
  `payment_date` int(11) DEFAULT NULL,
  `payment_due` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `invoice_num` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `membership_id` (`membership_id`),
  KEY `company_id` (`company_id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_menu`
--

CREATE TABLE IF NOT EXISTS `gxc_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL,
  `menu_description` varchar(255) NOT NULL,
  `lang` tinyint(4) DEFAULT NULL,
  `guid` varchar(255) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_menu_items`
--

CREATE TABLE IF NOT EXISTS `gxc_menu_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `root` int(10) DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_migration`
--

CREATE TABLE IF NOT EXISTS `gxc_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_notification`
--

CREATE TABLE IF NOT EXISTS `gxc_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `body` text,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_notification_read`
--

CREATE TABLE IF NOT EXISTS `gxc_notification_read` (
  `user_id` int(11) NOT NULL,
  `read_time` int(11) NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_object`
--

CREATE TABLE IF NOT EXISTS `gxc_object` (
  `object_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_author` bigint(20) unsigned DEFAULT '0',
  `object_date` int(11) NOT NULL DEFAULT '0',
  `object_date_gmt` int(11) NOT NULL DEFAULT '0',
  `object_content` longtext,
  `object_title` text,
  `object_excerpt` text,
  `object_status` tinyint(4) NOT NULL DEFAULT '1',
  `comment_status` tinyint(4) NOT NULL DEFAULT '1',
  `object_password` varchar(20) DEFAULT NULL,
  `object_name` varchar(255) NOT NULL,
  `object_modified` int(11) NOT NULL DEFAULT '0',
  `object_modified_gmt` int(11) NOT NULL DEFAULT '0',
  `object_content_filtered` text,
  `object_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `object_type` varchar(20) NOT NULL DEFAULT 'object',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  `object_slug` varchar(255) DEFAULT NULL,
  `object_description` text,
  `object_keywords` text,
  `lang` tinyint(4) DEFAULT '1',
  `object_author_name` varchar(255) DEFAULT NULL,
  `total_number_meta` tinyint(3) NOT NULL,
  `total_number_resource` tinyint(3) NOT NULL,
  `tags` text,
  `object_view` int(11) NOT NULL DEFAULT '0',
  `like` int(11) NOT NULL DEFAULT '0',
  `dislike` int(11) NOT NULL DEFAULT '0',
  `rating_scores` int(11) NOT NULL DEFAULT '0',
  `rating_average` float NOT NULL DEFAULT '0',
  `layout` varchar(125) DEFAULT NULL,
  PRIMARY KEY (`object_id`),
  KEY `type_status_date` (`object_type`,`object_status`,`object_date`,`object_id`),
  KEY `object_parent` (`object_parent`),
  KEY `object_author` (`object_author`),
  KEY `object_name` (`object_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_object_meta`
--

CREATE TABLE IF NOT EXISTS `gxc_object_meta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `meta_object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `object_id` (`meta_object_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_object_resource`
--

CREATE TABLE IF NOT EXISTS `gxc_object_resource` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `resource_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `resource_order` int(11) NOT NULL DEFAULT '0',
  `description` longtext,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`object_id`,`resource_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_object_term`
--

CREATE TABLE IF NOT EXISTS `gxc_object_term` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `data` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_id`),
  KEY `term_id` (`term_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_page`
--

CREATE TABLE IF NOT EXISTS `gxc_page` (
  `page_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `parent` bigint(20) NOT NULL,
  `layout` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `lang` tinyint(4) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `allow_index` tinyint(1) NOT NULL DEFAULT '1',
  `allow_follow` tinyint(1) NOT NULL DEFAULT '1',
  `display_type` varchar(50) NOT NULL DEFAULT 'main',
  `display_app` varchar(50) NOT NULL DEFAULT 'all',
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_page_block`
--

CREATE TABLE IF NOT EXISTS `gxc_page_block` (
  `page_id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `block_order` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `region` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`block_id`,`block_order`,`region`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_payment_info`
--

CREATE TABLE IF NOT EXISTS `gxc_payment_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `product_type` varchar(20) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `company_position` tinyint(4) DEFAULT NULL,
  `vat_code` varchar(11) DEFAULT NULL,
  `bank_name` varchar(20) DEFAULT NULL,
  `bank_number` varchar(60) DEFAULT NULL,
  `region_id` int(10) DEFAULT NULL,
  `province_id` int(10) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `postal_code` char(5) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `product_id` (`product_id`),
  KEY `product_type` (`product_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_private_message`
--

CREATE TABLE IF NOT EXISTS `gxc_private_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(256) NOT NULL DEFAULT '',
  `body` text,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `answered` int(11) DEFAULT NULL,
  `senderSpammed` tinyint(1) NOT NULL DEFAULT '0',
  `receiverSpammed` tinyint(1) NOT NULL DEFAULT '0',
  `senderMarkDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `receiverMarkDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `senderDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `receiverDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `sender_name` varchar(100) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sender` (`sender_id`),
  KEY `receiver` (`receiver_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_productsale_tag_relation`
--

CREATE TABLE IF NOT EXISTS `gxc_productsale_tag_relation` (
  `productId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tagId` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`productId`,`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT NULL,
  `domain_id` int(11) DEFAULT NULL,
  `section_store` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `tags` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_price` decimal(10,2) DEFAULT NULL,
  `currency` tinyint(1) DEFAULT '1',
  `lang` tinyint(1) DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `expire_time` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `main_image` int(11) DEFAULT '0',
  `visible_home` tinyint(1) DEFAULT '0',
  `min_quantity` tinyint(5) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `product_name` (`name`),
  KEY `shopId` (`shop_id`),
  KEY `domainId` (`domain_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Triggers `gxc_product_sale`
--
DROP TRIGGER IF EXISTS `gxc_product_sale_sphinxklist`;
DELIMITER //
CREATE TRIGGER `gxc_product_sale_sphinxklist` AFTER DELETE ON `gxc_product_sale`
 FOR EACH ROW BEGIN
insert into gxc_product_sale_sphinxklist VALUES (OLD.id, NOW());
 END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale_category`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale_category` (
  `product_id` int(11) unsigned NOT NULL DEFAULT '0',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale_category_list`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale_category_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `guid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `root` (`root`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale_comment`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `comment` text,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale_image`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  `created_date` int(11) DEFAULT NULL,
  `update_date` int(11) DEFAULT NULL,
  `shopId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  KEY `companyId` (`shopId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale_section`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale_section` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shopId` bigint(20) DEFAULT NULL,
  `name` varchar(60) NOT NULL DEFAULT '',
  `slug` varchar(120) NOT NULL DEFAULT '',
  `position` smallint(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `companyId` (`shopId`),
  KEY `position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale_sphinxklist`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale_sphinxklist` (
  `id` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_product_sale_tag`
--

CREATE TABLE IF NOT EXISTS `gxc_product_sale_tag` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `frequency` int(11) DEFAULT '1',
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_province`
--

CREATE TABLE IF NOT EXISTS `gxc_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(52) NOT NULL,
  `regionId` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `regionID` (`regionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_region`
--

CREATE TABLE IF NOT EXISTS `gxc_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(52) NOT NULL,
  `countryCode` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_resource`
--

CREATE TABLE IF NOT EXISTS `gxc_resource` (
  `resource_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(255) DEFAULT '',
  `resource_body` text,
  `resource_path` varchar(255) DEFAULT '',
  `resource_type` varchar(50) DEFAULT NULL,
  `created` int(11) DEFAULT '0',
  `updated` int(11) DEFAULT '0',
  `creator` bigint(20) NOT NULL,
  `where` varchar(50) NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_rights`
--

CREATE TABLE IF NOT EXISTS `gxc_rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_session`
--

CREATE TABLE IF NOT EXISTS `gxc_session` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_settings`
--

CREATE TABLE IF NOT EXISTS `gxc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL DEFAULT 'system',
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_key` (`category`,`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_shop_category`
--

CREATE TABLE IF NOT EXISTS `gxc_shop_category` (
  `shopId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `categoryId` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopId`,`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_shop_province`
--

CREATE TABLE IF NOT EXISTS `gxc_shop_province` (
  `shopId` int(11) NOT NULL DEFAULT '0',
  `provinceId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopId`,`provinceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_shop_review`
--

CREATE TABLE IF NOT EXISTS `gxc_shop_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `comment` text,
  `user_id` int(11) DEFAULT NULL,
  `shop_id` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `shop_id` (`shop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_shop_review_rating`
--

CREATE TABLE IF NOT EXISTS `gxc_shop_review_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `review_id` int(11) NOT NULL,
  `rate` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `review_id` (`review_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_shop_review_vote`
--

CREATE TABLE IF NOT EXISTS `gxc_shop_review_vote` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `value` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `user_review` (`user_id`,`review_id`),
  KEY `review_id` (`review_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_shop_shipping`
--

CREATE TABLE IF NOT EXISTS `gxc_shop_shipping` (
  `optionId` int(11) NOT NULL DEFAULT '0',
  `shopId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`optionId`,`shopId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_sphinxcounters`
--

CREATE TABLE IF NOT EXISTS `gxc_sphinxcounters` (
  `tablename` varchar(255) NOT NULL,
  `maxts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_table_modifications`
--

CREATE TABLE IF NOT EXISTS `gxc_table_modifications` (
  `table_name` varchar(50) NOT NULL,
  `last_modification` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_tag`
--

CREATE TABLE IF NOT EXISTS `gxc_tag` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `frequency` int(11) DEFAULT '1',
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_tag_relationships`
--

CREATE TABLE IF NOT EXISTS `gxc_tag_relationships` (
  `tag_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  PRIMARY KEY (`tag_id`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_taxonomy`
--

CREATE TABLE IF NOT EXISTS `gxc_taxonomy` (
  `taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'article',
  `lang` tinyint(4) DEFAULT '1',
  `guid` varchar(255) NOT NULL,
  PRIMARY KEY (`taxonomy_id`),
  KEY `taxonomy` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_term`
--

CREATE TABLE IF NOT EXISTS `gxc_term` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `taxonomy_id` int(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `parent` bigint(20) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`term_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_transfer`
--

CREATE TABLE IF NOT EXISTS `gxc_transfer` (
  `transfer_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) NOT NULL,
  `from_user_id` bigint(20) NOT NULL,
  `to_user_id` bigint(20) NOT NULL,
  `before_status` tinyint(2) NOT NULL,
  `after_status` tinyint(2) NOT NULL,
  `type` int(11) NOT NULL,
  `note` varchar(125) DEFAULT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`transfer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_user`
--

CREATE TABLE IF NOT EXISTS `gxc_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `display_name` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `passwordStrategy` varchar(128) DEFAULT NULL,
  `requiresNewPassword` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(128) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `recent_login` int(11) DEFAULT NULL,
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `email_recover_key` varchar(255) DEFAULT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT '0',
  `has_membership` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `user_type` (`user_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Triggers `gxc_user`
--
DROP TRIGGER IF EXISTS `gxc_users_klist`;
DELIMITER //
CREATE TRIGGER `gxc_users_klist` AFTER DELETE ON `gxc_user`
 FOR EACH ROW BEGIN insert into gxc_users_klist VALUES (OLD.user_id, OLD.username, OLD.display_name, NOW());
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_users_klist`
--

CREATE TABLE IF NOT EXISTS `gxc_users_klist` (
  `user_id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `display_name` varchar(128) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_users_sphinxklist`
--

CREATE TABLE IF NOT EXISTS `gxc_users_sphinxklist` (
  `id` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_user_company_profile`
--

CREATE TABLE IF NOT EXISTS `gxc_user_company_profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyId` int(11) unsigned NOT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `companyname` varchar(50) DEFAULT NULL,
  `companytype` tinyint(4) DEFAULT NULL,
  `companyposition` tinyint(4) DEFAULT NULL,
  `domain_id` int(10) DEFAULT NULL,
  `vat_code` varchar(11) DEFAULT NULL,
  `region_id` int(10) DEFAULT NULL,
  `province_id` int(10) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `postal_code` char(5) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `website` varchar(512) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_iban` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companyId` (`companyId`),
  KEY `company_name` (`companyname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Triggers `gxc_user_company_profile`
--
DROP TRIGGER IF EXISTS `gxc_company_sphinxklist`;
DELIMITER //
CREATE TRIGGER `gxc_company_sphinxklist` AFTER DELETE ON `gxc_user_company_profile`
 FOR EACH ROW BEGIN
insert into gxc_company_sphinxklist VALUES (OLD.id, NOW());
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_user_company_settings`
--

CREATE TABLE IF NOT EXISTS `gxc_user_company_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `companyId` int(10) unsigned NOT NULL,
  `email_news` tinyint(1) NOT NULL DEFAULT '1',
  `email_message` tinyint(1) NOT NULL DEFAULT '1',
  `email_traffic` tinyint(1) NOT NULL DEFAULT '1',
  `email_inquiry` tinyint(1) NOT NULL DEFAULT '1',
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `companyId` (`companyId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_user_company_shop`
--

CREATE TABLE IF NOT EXISTS `gxc_user_company_shop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `companyId` int(11) unsigned NOT NULL,
  `storeId` int(11) NOT NULL DEFAULT '1',
  `website` varchar(512) DEFAULT NULL,
  `description` text,
  `services` varchar(255) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `shipping_available` tinyint(4) DEFAULT NULL,
  `shipping_description` text,
  `delivery_type` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `companyId` (`companyId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_user_profile`
--

CREATE TABLE IF NOT EXISTS `gxc_user_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `region_id` int(20) DEFAULT NULL,
  `province_id` int(20) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `postal_code` char(5) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `gender` varchar(10) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Triggers `gxc_user_profile`
--
DROP TRIGGER IF EXISTS `gxc_users_sphinxklist`;
DELIMITER //
CREATE TRIGGER `gxc_users_sphinxklist` AFTER DELETE ON `gxc_user_profile`
 FOR EACH ROW BEGIN
insert into gxc_users_sphinxklist VALUES (OLD.id, NOW());
 END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `gxc_user_settings`
--

CREATE TABLE IF NOT EXISTS `gxc_user_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `email_news` tinyint(1) NOT NULL DEFAULT '1',
  `email_message` tinyint(1) NOT NULL DEFAULT '1',
  `email_public` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yiicache`
--

CREATE TABLE IF NOT EXISTS `yiicache` (
  `id` char(128) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `value` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
