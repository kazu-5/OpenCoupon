-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 3 月 03 日 05:24
-- サーバのバージョン: 5.1.44
-- PHP のバージョン: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `op_coupon`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `t_customer`
--

CREATE TABLE IF NOT EXISTS `t_customer` (
  `account_id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL COMMENT 't_customerからshop_idを分離する',
  `shop_flag` tinyint(1) DEFAULT NULL,
  `memo` text,
  `nick_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `gender` enum('M','F') NOT NULL COMMENT '性別',
  `favorite_pref` int(11) DEFAULT NULL,
  `favorite_city` int(11) DEFAULT NULL,
  `birthday` date NOT NULL,
  `address_seq_no` int(11) NOT NULL DEFAULT '1' COMMENT 't_addressの主たる所在地',
  `uid` varchar(32) NOT NULL COMMENT 'For credit card',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `shop_id` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `t_customer`
--

INSERT INTO `t_customer` (`account_id`, `shop_id`, `shop_flag`, `memo`, `nick_name`, `last_name`, `first_name`, `gender`, `favorite_pref`, `favorite_city`, `birthday`, `address_seq_no`, `uid`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 1, 1, NULL, 'たろうちゃん', '太郎', '&lt;h1&gt;ほげほげ', 'M', 13, 0, '1980-11-09', 1, '7bae8d59595b54f5f41a4e7a8a55d3f8', '2013-01-29 04:18:11', '2013-02-19 12:31:11', NULL, '2013-02-27 02:16:59');
