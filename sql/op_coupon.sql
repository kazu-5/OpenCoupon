-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 1 月 06 日 15:42
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
  `mailaddr_md5` char(32) NOT NULL COMMENT '平文メールアドレスをmd5化したハッシュ',
  `mailaddr` varchar(200) NOT NULL COMMENT '平文メールアドレスを暗号化したもの',
  `password` varchar(32) NOT NULL COMMENT 'パスワードをmd5でハッシュ化したもの',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `t_account`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `t_address`
--

CREATE TABLE IF NOT EXISTS `t_address` (
  `customer_id` int(11) NOT NULL,
  `seq_no` int(11) NOT NULL COMMENT '順番号',
  `last_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `postal_code` int(11) NOT NULL,
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
  `coupon_expire` datetime NOT NULL COMMENT 'クーポンの有効期限',
  `coupon_sales_limit` datetime NOT NULL COMMENT 'クーポンの終了時間',
  `shop_id` int(11) NOT NULL COMMENT 'ショップID（現在はt_account.idと同一）',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`coupon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `t_coupon`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `t_customer`
--

CREATE TABLE IF NOT EXISTS `t_customer` (
  `customer_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL COMMENT '廃止',
  `shop_id` int(11) DEFAULT NULL COMMENT '廃止',
  `shop_flag` tinyint(1) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `gender` enum('M','F') NOT NULL COMMENT '性別',
  `myarea` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  `address_seq_no` int(11) NOT NULL COMMENT 't_addressの主たる所在地',
  `IP_USER_ID` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- テーブルのデータをダンプしています `t_customer`
--


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `t_shop`
--


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

