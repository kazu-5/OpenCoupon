-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 3 月 03 日 05:11
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
-- テーブルの構造 `dc_ip_user_id`
--

CREATE TABLE IF NOT EXISTS `dc_ip_user_id` (
  `ip_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `op_uniq_id` char(32) NOT NULL,
  `mailaddr_md5` char(32) NOT NULL,
  `card_number` char(4) NOT NULL DEFAULT '' COMMENT 'クレジットカード番号の末尾4桁',
  `card_expire` char(4) NOT NULL DEFAULT '' COMMENT 'クレジットカードの有効期限（MMYY）',
  `card_brand` enum('VISA','JCB','Master','UC') DEFAULT NULL COMMENT 'クレジットカードのブランド',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ip_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='IP_USER_IDを保存するテーブル' AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `dc_ip_user_id`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `t_account`
--

CREATE TABLE IF NOT EXISTS `t_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_md5` char(32) NOT NULL COMMENT 'md5(email)',
  `email` varchar(200) NOT NULL COMMENT 'Blowfish::Encrypt(email)',
  `password` varchar(32) NOT NULL COMMENT 'md5(password)',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_md5` (`email_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- テーブルのデータをダンプしています `t_account`
--

INSERT INTO `t_account` (`id`, `email_md5`, `email`, `password`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, '7bae8d59595b54f5f41a4e7a8a55d3f8', '35e8256af74c38835baecc77fca138edba1563425f64ac25e47727ca099bcc92ee492789dd60ee9e', 'e10adc3949ba59abbe56e057f20f883e', '2013-01-28 18:17:55', '2013-02-13 12:03:11', NULL, '2013-02-15 20:38:22');

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
(1, 6, '新しい姓', '新しい名', '日本語', 47, '那覇市', '那覇', 'ナハナハ', NULL, NULL, NULL, '2013-02-28 20:36:10'),
(1, 7, '', '', '', 0, '', '', '', NULL, NULL, NULL, '2013-03-03 04:13:50'),
(1, 8, '', '', '', 0, '', '', '', NULL, NULL, NULL, '2013-03-03 04:32:28'),
(1, 9, '', '', '', 0, '', '', '', NULL, NULL, NULL, '2013-03-03 04:32:55');

-- --------------------------------------------------------

--
-- テーブルの構造 `t_buy`
--

CREATE TABLE IF NOT EXISTS `t_buy` (
  `buy_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `num` int(11) NOT NULL COMMENT '数量',
  `sid` varchar(32) NOT NULL COMMENT '取引コード（購入商品と決済情報を紐付ける）',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`buy_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- テーブルのデータをダンプしています `t_buy`
--

INSERT INTO `t_buy` (`buy_id`, `account_id`, `coupon_id`, `num`, `sid`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 1, 1, 1, '', '2013-01-31 04:53:05', NULL, NULL, '2013-01-31 13:53:05'),
(2, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 04:53:33', NULL, NULL, '2013-01-31 13:53:33'),
(3, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:30:14', NULL, NULL, '2013-01-31 21:30:14'),
(4, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:33:22', NULL, NULL, '2013-01-31 21:33:22'),
(5, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:33:36', NULL, NULL, '2013-01-31 21:33:36'),
(6, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:39:48', NULL, NULL, '2013-01-31 21:39:48'),
(7, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:42:14', NULL, NULL, '2013-01-31 21:42:14'),
(8, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:42:44', NULL, NULL, '2013-01-31 21:42:44'),
(9, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:43:53', NULL, NULL, '2013-01-31 21:43:53'),
(10, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:44:09', NULL, NULL, '2013-01-31 21:44:09'),
(11, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:44:20', NULL, NULL, '2013-01-31 21:44:20'),
(12, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:45:31', NULL, NULL, '2013-01-31 21:45:31'),
(13, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:49:05', NULL, NULL, '2013-01-31 21:49:05'),
(14, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:49:35', NULL, NULL, '2013-01-31 21:49:35'),
(15, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:51:43', NULL, NULL, '2013-01-31 21:51:43'),
(16, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 12:55:16', NULL, NULL, '2013-01-31 21:55:16'),
(17, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 13:04:37', NULL, NULL, '2013-01-31 22:04:37'),
(18, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 13:05:33', NULL, NULL, '2013-01-31 22:05:33'),
(19, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 13:06:23', NULL, NULL, '2013-01-31 22:06:23'),
(20, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 13:06:33', NULL, NULL, '2013-01-31 22:06:33'),
(21, 1, 1, 1, '01eb8a9956865747637639482336f5f8', '2013-01-31 13:25:19', NULL, NULL, '2013-01-31 22:25:19');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- テーブルのデータをダンプしています `t_shop`
--

INSERT INTO `t_shop` (`shop_id`, `shop_name`, `shop_description`, `shop_pref`, `shop_city`, `shop_address`, `shop_building`, `shop_tel`, `shop_holiday`, `shop_open`, `shop_close`, `shop_railway`, `shop_station`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, '株式会社オープンクーポン', '株式会社オープンクーポンが発行するオープンなクーポンです。\r\n色々なクーポンを発行しています。', '東京', '豊島区', '池袋１丁目', '豊島ビル', '03-1234-1234', '年中無休（年末年始を除く）', '07:00:00', '22:00:00', 'JR山手線線・西武池袋線', 'ＪＲ池袋駅', NULL, NULL, NULL, '2013-02-13 09:04:34'),
(2, 'ダミー店舗', 'この店舗は表示されてはならない。', '', '', '', '', '', '', '00:00:00', '00:00:00', '', '', NULL, NULL, NULL, '2013-02-19 21:46:42'),
(3, 'ダミー店舗', 'この店舗は表示されてはならない。', '', '', '', '', '', '', '00:00:00', '00:00:00', '', '', NULL, NULL, NULL, '2013-02-19 21:46:42');

-- --------------------------------------------------------

--
-- テーブルの構造 `t_test`
--

CREATE TABLE IF NOT EXISTS `t_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text CHARACTER SET utf8 NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `t_test`
--

