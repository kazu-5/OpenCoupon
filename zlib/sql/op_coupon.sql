-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 1 月 29 日 14:12
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
(1, '7bae8d59595b54f5f41a4e7a8a55d3f8', '35e8256af74c38835baecc77fca138edba1563425f64ac25e47727ca099bcc92ee492789dd60ee9e', 'e10adc3949ba59abbe56e057f20f883e', '2013-01-28 18:17:55', '2013-01-29 04:36:38', NULL, '2013-01-29 13:36:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `t_address`
--

CREATE TABLE IF NOT EXISTS `t_address` (
  `customer_id` int(11) NOT NULL,
  `seq_no` int(11) NOT NULL COMMENT '順番号',
  `last_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `postcode` int(11) NOT NULL,
  `pref` varchar(10) CHARACTER SET utf8 NOT NULL,
  `city` varchar(10) CHARACTER SET utf8 NOT NULL,
  `address` varchar(30) CHARACTER SET utf8 NOT NULL,
  `building` varchar(20) CHARACTER SET utf8 NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`customer_id`,`seq_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- テーブルのデータをダンプしています `t_address`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `t_buy`
--

CREATE TABLE IF NOT EXISTS `t_buy` (
  `buy_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `num` int(11) NOT NULL COMMENT '数量',
  `payment_id` int(11) NOT NULL COMMENT '支払方法',
  `settle_flag` int(11) NOT NULL COMMENT '決済フラグ',
  `SID` varchar(16) NOT NULL COMMENT '取引コード（購入商品と決済情報を紐付ける）',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`buy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `t_buy`
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
  `coupon_sales_limit` datetime NOT NULL COMMENT 'クーポンの終了時間',
  `coupon_expire` datetime NOT NULL COMMENT 'クーポンの有効期限',
  `coupon_person_num` int(11) NOT NULL DEFAULT '9' COMMENT '一人が購入できる枚数',
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

INSERT INTO `t_coupon` (`coupon_id`, `coupon_title`, `coupon_description`, `coupon_normal_price`, `coupon_sales_price`, `coupon_sales_num_top`, `coupon_sales_num_bottom`, `coupon_sales_limit`, `coupon_expire`, `coupon_person_num`, `shop_id`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, 'テスト', 'テスト用のクーポン', 1000, 500, 100, 50, '2013-01-31 21:56:44', '0000-00-00 00:00:00', 9, 1, NULL, NULL, NULL, '2013-01-12 21:57:07');

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

INSERT INTO `t_customer` (`account_id`, `shop_id`, `shop_flag`, `memo`, `nick_name`, `last_name`, `first_name`, `gender`, `pref`, `city`, `birthday`, `address_seq_no`, `IP_USER_ID`, `created`, `updated`, `deleted`, `timestamp`) VALUES
(1, NULL, NULL, NULL, 'たろうちゃん', '太郎', '&lt;h1&gt;山田', 'M', '13', '', '1980-01-02', 0, '', '2013-01-29 04:18:11', '2013-01-29 04:36:38', NULL, '2013-01-29 13:36:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `t_shop`
--

CREATE TABLE IF NOT EXISTS `t_shop` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ショップID',
  `shop_name` varchar(200) NOT NULL COMMENT '店名',
  `shop_description` text NOT NULL COMMENT 'お店の説明',
  `shop_address` text NOT NULL,
  `shop_telephone` text NOT NULL,
  `shop_holiday` text NOT NULL,
  `shop_opening_hour` text NOT NULL,
  `shop_nearest_station` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
  PRIMARY KEY (`shop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- テーブルのデータをダンプしています `t_shop`
--

INSERT INTO `t_shop` (`shop_id`, `shop_name`, `shop_description`, `shop_address`, `shop_telephone`, `shop_holiday`, `shop_opening_hour`, `shop_nearest_station`, `timestamp`) VALUES
(1, 'テストショップ', 'テスト用のショップです。', '青山一丁目', '03-1234-5678', '日曜日', '９：００〜１９：００', '青山一丁目', '2013-01-19 16:46:16');

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

