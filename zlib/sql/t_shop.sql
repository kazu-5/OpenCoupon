-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 2 月 02 日 16:15
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
-- テーブルの構造 `t_shop`
--

CREATE TABLE IF NOT EXISTS `t_shop` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ショップID',
  `shop_name` varchar(200) NOT NULL COMMENT '店名',
  `shop_description` text NOT NULL COMMENT 'お店の説明',
  `shop_pref` varchar(100) NOT NULL,
  `shop_city` varchar(100) NOT NULL,
  `shop_address` text NOT NULL,
  `shop_building` varchar(100) NOT NULL,
  `shop_tel` varchar(100) NOT NULL,
  `shop_holiday` varchar(100) NOT NULL,
  `shop_open` time NOT NULL,
  `shop_close` time NOT NULL,
  `shop_railway` varchar(100) NOT NULL,
  `shop_station` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`shop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- テーブルのデータをダンプしています `t_shop`
--

INSERT INTO `t_shop` (`shop_id`, `shop_name`, `shop_description`, `shop_pref`, `shop_city`, `shop_address`, `shop_building`, `shop_tel`, `shop_holiday`, `shop_open`, `shop_close`, `shop_railway`, `shop_station`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 'ルノアール銀座', 'ルノアール銀座です。', '東京', '豊島区', '池袋１丁目', '豊島ビル', '03-1234-1234', '年中無休（年末年始を除く）', '07:00:00', '22:00:00', '山手線', 'ＪＲ池袋駅', NULL, NULL, NULL, '2013-02-03 00:43:15');
