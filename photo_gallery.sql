-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photograph_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `author` varchar(255) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `photograph_id` (`photograph_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comments` (`id`, `photograph_id`, `created`, `author`, `body`) VALUES
(1,	2,	'2018-05-11 09:16:40',	'Kevin',	'It\'s my love pictures!'),
(2,	2,	'2018-05-19 10:48:26',	'Doug',	'Милые цветы.'),
(3,	2,	'2018-05-19 10:50:15',	'Doug',	'Милые цветы.'),
(4,	2,	'2018-05-19 10:52:12',	'Doug',	'Милые цветы.'),
(5,	2,	'2018-05-19 10:54:56',	'Duglas',	'Dugalas Duallas/'),
(6,	2,	'2018-05-19 10:57:02',	'dukl',	'dukl'),
(7,	2,	'2018-05-19 11:00:26',	'Dulglas',	'Duglas Maklaut'),
(8,	2,	'2018-05-19 11:12:46',	'Ketty',	'Pretty flowers.'),
(9,	2,	'2018-05-19 11:13:44',	'Katty',	'Super flowers!!!'),
(10,	2,	'2018-05-19 11:24:24',	'Mary',	'I like them too.'),
(11,	2,	'2018-05-19 11:25:58',	'Greg',	'I love this flowers'),
(12,	2,	'2018-05-19 11:28:23',	'Big Man',	'Big floweer.\r\nFlowwers.\r\nFloers.\r\nFlowers.'),
(13,	2,	'2018-05-19 11:28:54',	'Big Man',	'Big floweer.\r\nFlowwers.\r\nFloers.\r\nFlowers.');

DROP TABLE IF EXISTS `photographs`;
CREATE TABLE `photographs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `photographs` (`id`, `filename`, `type`, `size`, `caption`) VALUES
(1,	'bamboo.jpg',	'image/jpeg',	455568,	'Бамбук'),
(2,	'flowers.jpg',	'image/jpeg',	664947,	'Цветы'),
(8,	'roof.jpg',	'image/jpeg',	524574,	'Крыша'),
(9,	'wood.jpg',	'image/jpeg',	564389,	'Дрова'),
(10,	'buddhas.jpg',	'image/jpeg',	456234,	'Статуэтки');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`) VALUES
(1,	'kskoglund',	'secretpwd',	'Kevin',	'Skoglund');

-- 2018-09-24 18:30:32
