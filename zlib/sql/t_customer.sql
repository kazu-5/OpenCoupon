-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 1 月 29 日 04:18
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
  `shop_id` int(11) DEFAULT NULL,
  `shop_flag` tinyint(1) DEFAULT NULL,
  `memo` text,
  `nick_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `gender` enum('M','F') NOT NULL COMMENT '性別',
  `pref` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `address_seq_no` int(11) NOT NULL COMMENT 't_addressの主たる所在地',
  `IP_USER_ID` varchar(16) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `t_customer`
--

