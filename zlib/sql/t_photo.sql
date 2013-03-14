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
-- テーブルの構造 `t_photo`
--

CREATE TABLE IF NOT EXISTS `t_photo` (
  `shop_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `seq_no` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`shop_id`,`coupon_id`,`seq_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Saves photo url';

--
-- テーブルのデータをダンプしています `t_photo`
--

INSERT INTO `t_photo` (`shop_id`, `coupon_id`, `seq_no`, `url`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 0, 1, '/shop/1/1.jpg', '2013-02-22 10:47:36', NULL, NULL, '2013-02-24 01:57:01');
