-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 1 月 17 日 18:05
-- サーバのバージョン: 5.5.16
-- PHP のバージョン: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `op_coupon`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `t_coupon`
--

CREATE TABLE IF NOT EXISTS `t_coupon` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'クーポンID',
  `coupon_title` varchar(30) NOT NULL COMMENT 'クーポンのタイトル',
  `coupon_description` text NOT NULL COMMENT 'クーポンの説明',
  `coupon_normal_price` int(11) NOT NULL COMMENT 'クーポンの通常販売金額',
  `coupon_sales_price` int(11) NOT NULL COMMENT 'クーポンの販売価格',
  `coupon_sales_num_top` int(11) NOT NULL COMMENT '販売枚数の上限',
  `coupon_sales_num_bottom` int(11) NOT NULL COMMENT '販売間数の下限',
  `coupon_expire` datetime NOT NULL COMMENT 'クーポンの有効期限',
  `coupon_sales_limit` datetime NOT NULL COMMENT 'クーポンの終了時間',
  `shop_id` int(11) NOT NULL COMMENT 'ショップID（現在はt_account.idと同一）',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`coupon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- テーブルのデータをダンプしています `t_coupon`
--

INSERT INTO `t_coupon` (`coupon_id`, `coupon_title`, `coupon_description`, `coupon_normal_price`, `coupon_sales_price`, `coupon_sales_num_top`, `coupon_sales_num_bottom`, `coupon_expire`, `coupon_sales_limit`, `shop_id`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 'テストクーポン', 'これはテスト用のクーポンです。', 1000, 500, 100, 50, '2014-01-01 00:00:00', '2014-01-01 00:00:00', 1, NULL, NULL, NULL, '2013-01-17 16:59:22');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
