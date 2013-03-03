-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 3 月 03 日 05:23
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
-- テーブルの構造 `t_address`
--

CREATE TABLE IF NOT EXISTS `t_address` (
  `account_id` int(11) NOT NULL,
  `seq_no` int(11) NOT NULL DEFAULT '1' COMMENT '順番号',
  `last_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `zipcode` varchar(100) CHARACTER SET utf8 NOT NULL,
  `pref` int(10) NOT NULL,
  `city` varchar(10) CHARACTER SET utf8 NOT NULL,
  `address` varchar(30) CHARACTER SET utf8 NOT NULL,
  `building` varchar(20) CHARACTER SET utf8 NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`,`seq_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='住所テーブル。１つのアカウントに複数の住所が紐づく';

--
-- テーブルのデータをダンプしています `t_address`
--

INSERT INTO `t_address` (`account_id`, `seq_no`, `last_name`, `first_name`, `zipcode`, `pref`, `city`, `address`, `building`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 2, '太郎', '<h1>山田', '177-0041', 13, '千代田区', '神田１丁目', '神田明神', '2013-01-29 16:34:02', '2013-02-24 08:01:12', NULL, '2013-02-24 17:14:28'),
(1, 1, '田中', '星人', '100-0001', 1, '札幌市', '札幌町１−２−１０', '札幌ビル１F', NULL, NULL, NULL, '2013-02-28 09:27:03'),
(1, 3, '田中', '星人', '100-0001', 1, '札幌市', '札幌町１−２−１０', '札幌ビル１F', NULL, NULL, '2013-02-26 21:39:18', '2013-02-28 09:39:52'),
(1, 4, 'デモ', 'デモ', '100-0001', 1, '札幌市', '札幌', '札幌ビル', NULL, '2013-02-28 11:15:48', NULL, '2013-02-28 20:15:48'),
(1, 5, 'テスト', 'テスト', 'テスト', 1, 'テスト', 'テスト', '', NULL, NULL, NULL, '2013-02-28 20:28:38'),
(1, 6, '新しい姓', '新しい名', '日本語', 47, '那覇市', '那覇', 'ナハナハ', NULL, NULL, NULL, '2013-02-28 20:36:10');
