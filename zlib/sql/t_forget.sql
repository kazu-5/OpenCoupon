-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
<<<<<<< HEAD
-- 生成時間: 2013 年 4 月 22 日 12:19
=======
-- 生成時間: 2013 年 4 月 30 日 19:42
>>>>>>> eb80b7666deb73b7614beafe5b6b60cf3ddfd052
-- サーバのバージョン: 5.5.8
-- PHP のバージョン: 5.3.5

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
-- テーブルの構造 `t_forget`
--

CREATE TABLE IF NOT EXISTS `t_forget` (
<<<<<<< HEAD
  `sent_id` int(11) NOT NULL AUTO_INCREMENT,
=======
  `forget_id` int(11) NOT NULL AUTO_INCREMENT,
>>>>>>> eb80b7666deb73b7614beafe5b6b60cf3ddfd052
  `ip_address` varchar(39) NOT NULL,
  `email_forget` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
<<<<<<< HEAD
  PRIMARY KEY (`sent_id`)
=======
  PRIMARY KEY (`forget_id`)
>>>>>>> eb80b7666deb73b7614beafe5b6b60cf3ddfd052
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
