SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `csndb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `csndb`;

-- --------------------------------------------------------

--
-- Table structure `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `introtext` longtext COLLATE utf8_unicode_ci,
  `fulltext` longtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `allow_comments` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23A0E66727ACA70` (`parent_id`),
  KEY `IDX_23A0E6682F1BAF4` (`language_id`),
  KEY `IDX_23A0E66A76ED395` (`user_id`),
  KEY `IDX_23A0E6689329D25` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure `articles_categories`
--

CREATE TABLE IF NOT EXISTS `articles_categories` (
  `article_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`category_id`),
  KEY `IDX_DE004A0E7294869C` (`article_id`),
  KEY `IDX_DE004A0E12469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lannguage_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C8564FA74` (`lannguage_id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C7294869C` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Table records `language`
--

INSERT INTO `language` (`id`, `name`, `abbreviation`) VALUES
(1, 'Engilsh', 'en'),
(2, 'Български', 'bg');

-- --------------------------------------------------------

--
-- Table structure `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table records `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'guest'),
(2, 'member'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Table structure `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(11) NOT NULL,
  `question` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_salt` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `registration_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_confirmed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8D93D649D60322AC` (`role_id`),
  KEY `IDX_8D93D64982F1BAF4` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints
--

--
-- Constraints `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E6689329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  ADD CONSTRAINT `FK_23A0E66727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_23A0E6682F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `FK_23A0E66A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints `articles_categories`
--
ALTER TABLE `articles_categories`
  ADD CONSTRAINT `FK_DE004A0E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_DE004A0E7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Constraints `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_9474526C8564FA74` FOREIGN KEY (`lannguage_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64982F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `FK_8D93D649D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
