SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `enroll_wellstudio` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `enroll_wellstudio`;

DROP TABLE IF EXISTS `u_info`;
CREATE TABLE IF NOT EXISTS `u_info` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(11) NOT NULL,
  `sex` enum('男','女') NOT NULL,
  `nation` enum('汉族','壮族','满族','回族','苗族','维吾尔族','土家族','彝族','蒙古族','藏族','布依族','侗族','瑶族','朝鲜族','白族','哈尼族','哈萨克族','黎族','傣族','畲族','傈僳族','仡佬族','东乡族','高山族','拉祜族','水族','佤族','纳西族','羌族','土族','仫佬族','锡伯族','柯尔克孜族','达斡尔族','景颇族','毛南族','撒拉族','布朗族','塔吉克族','阿昌族','普米族','鄂温克族','怒族','京族','基诺族','德昂族','保安族','俄罗斯族','裕固族','乌孜别克族','门巴族','鄂伦春族','独龙族','塔塔尔族','赫哲族','珞巴族') NOT NULL,
  `native` varchar(40) NOT NULL,
  `birth` bigint(20) unsigned NOT NULL,
  `class` varchar(15) NOT NULL,
  `domitory` varchar(20) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `qq` varchar(13) NOT NULL,
  `introduction` text NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
