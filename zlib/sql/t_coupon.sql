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
  `coupon_sales_start` datetime NOT NULL COMMENT '[GMT] クーポンの販売開始時間',
  `coupon_sales_finish` datetime NOT NULL COMMENT '[GMT] クーポンの販売終了時間',
  `coupon_expire` datetime NOT NULL COMMENT '[GMT] クーポンの利用有効期限',
  `coupon_person_num` int(11) NOT NULL DEFAULT '9' COMMENT '一人が購入できる枚数',
  `coupon_hidden` datetime DEFAULT NULL COMMENT 'クーポンを非表示にする（購入可能）',
  `shop_id` int(11) NOT NULL COMMENT 't_customer.shop_id',
  `memo` text,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`coupon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- テーブルのデータをダンプしています `t_coupon`
--

INSERT INTO `t_coupon` (`coupon_id`, `coupon_title`, `coupon_description`, `coupon_normal_price`, `coupon_sales_price`, `coupon_sales_num_top`, `coupon_sales_num_bottom`, `coupon_sales_start`, `coupon_sales_finish`, `coupon_expire`, `coupon_person_num`, `coupon_hidden`, `shop_id`, `memo`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 'テスト（販売中）', 'テスト用のクーポン', 1000, 500, 100, 50, '2013-03-01 21:56:44', '2013-04-01 21:56:44', '2013-04-01 21:56:44', 9, '0000-00-00 00:00:00', 1, '', NULL, NULL, NULL, '2013-03-03 03:35:48'),
(2, 'テスト（販売待機中）', 'クーポンの説明', 1000, 500, 100, 50, '2021-01-01 19:00:00', '2021-03-30 19:00:00', '2021-03-30 19:00:00', 100, '0000-00-00 00:00:00', 1, '', NULL, NULL, NULL, '2013-02-04 14:43:08'),
(3, 'テスト（販売終了）', 'クーポンの説明', 1000, 500, 100, 50, '2012-01-30 19:00:00', '2012-01-30 19:00:00', '2012-01-30 19:00:00', 100, '0000-00-00 00:00:00', 1, '', NULL, NULL, NULL, '2013-02-04 14:43:35'),
(4, 'パパパパパイン（販売中）', 'パイン味のラーメンです。', 1200, 600, 100, 50, '2013-01-30 19:00:00', '2021-01-30 19:00:00', '2021-01-31 19:00:00', 100, '0000-00-00 00:00:00', 1, '', NULL, NULL, NULL, '2013-02-21 18:28:50'),
(5, 'クククククーポン', 'クーポン味のクーポンです。', 1000, 500, 100, 50, '2021-01-30 19:00:00', '2021-01-30 19:00:00', '2021-01-31 19:00:00', 100, '0000-00-00 00:00:00', 1, '', NULL, NULL, NULL, '2013-02-05 03:02:04'),
(6, '削除済みクーポン', 'このクーポンは削除されました。', 1000, 500, 100, 50, '2021-01-30 19:00:00', '2021-01-30 19:00:00', '2021-01-31 19:00:00', 100, '0000-00-00 00:00:00', 1, '', NULL, NULL, '2013-02-04 16:20:38', '2013-02-05 01:25:08'),
(7, 'テスト（販売中）', '販売中のクーポン', 2000, 1000, 100, 50, '2013-02-01 21:56:44', '2013-03-01 21:56:44', '2013-03-01 21:56:44', 7, '0000-00-00 00:00:00', 1, '', NULL, NULL, NULL, '2013-02-04 17:55:28'),
(8, 'クククククーポン（販売中）', '販売中のクーポンです。', 1000, 850, 100, 90, '2013-03-01 21:56:44', '2013-04-01 21:56:44', '2013-04-01 21:56:44', 9, '0000-00-00 00:00:00', 1, NULL, NULL, NULL, NULL, '2013-03-03 03:37:37');
