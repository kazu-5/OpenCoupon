-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成時間: 2013 年 1 月 29 日 04:16
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
(1, '7bae8d59595b54f5f41a4e7a8a55d3f8', '35e8256af74c38835baecc77fca138edba1563425f64ac25e47727ca099bcc92ee492789dd60ee9e', 'e10adc3949ba59abbe56e057f20f883e', '2013-01-28 18:17:55', '2013-01-29 04:14:00', NULL, '2013-01-29 13:14:00');
