-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Ven 14 Février 2014 à 20:22
-- Version du serveur: 5.5.27-log
-- Version de PHP: 5.4.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `ctn-market`
--

-- --------------------------------------------------------

--
-- Structure de la table `ctn_market_ads`
--

CREATE TABLE IF NOT EXISTS `ctn_market_ads` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `resume` text NOT NULL,
  `content` text NOT NULL,
  `media_id` bigint(20) NOT NULL,
  `features_km` int(11) NOT NULL,
  `features_energy` int(11) NOT NULL,
  `features_year` int(11) NOT NULL,
  `imm_location` int(11) NOT NULL,
  `imm_bnpieces` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `featured` tinyint(2) NOT NULL DEFAULT '0',
  `featured_expires` datetime NOT NULL,
  `is_active` tinyint(2) NOT NULL DEFAULT '0',
  `is_editable` tinyint(2) NOT NULL DEFAULT '1',
  `is_draft` tinyint(2) NOT NULL DEFAULT '1',
  `ip_address` varchar(255) NOT NULL,
  `validated_date` datetime NOT NULL,
  `closing_date` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `ctn_market_categories`
--

CREATE TABLE IF NOT EXISTS `ctn_market_categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) DEFAULT NULL,
  `foreign_key` bigint(20) NOT NULL,
  `model` varchar(255) NOT NULL,
  `record_count` int(11) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `ads_published` int(11) NOT NULL DEFAULT '0',
  `ads_unpublished` int(11) NOT NULL DEFAULT '0',
  `category_thumb` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`foreign_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Structure de la table `ctn_market_credits`
--

CREATE TABLE IF NOT EXISTS `ctn_market_credits` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `pack` varchar(20) NOT NULL,
  `credits` bigint(20) NOT NULL DEFAULT '0',
  `active` tinyint(2) NOT NULL DEFAULT '0',
  `pack_expires` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `ctn_market_medias`
--

CREATE TABLE IF NOT EXISTS `ctn_market_medias` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ref` varchar(255) NOT NULL,
  `ref_id` bigint(20) NOT NULL,
  `file` varchar(255) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_id` (`ref_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ctn_market_shops`
--

CREATE TABLE IF NOT EXISTS `ctn_market_shops` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `adress` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `facebook_url` varchar(255) NOT NULL,
  `website_url` varchar(255) NOT NULL,
  `shop_thumb` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '0',
  `shop_expires` datetime NOT NULL,
  `active` tinyint(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Structure de la table `ctn_market_users`
--

CREATE TABLE IF NOT EXISTS `ctn_market_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `has_shop` tinyint(2) NOT NULL DEFAULT '0',
  `shop_id` bigint(20) NOT NULL,
  `enable_slug` tinyint(1) NOT NULL DEFAULT '0',
  `enable_features` tinyint(2) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL,
  `password_token` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` int(11) NOT NULL,
  `email_token` tinyint(1) NOT NULL,
  `email_token_expires` datetime NOT NULL,
  `tos` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `user_status` tinyint(2) NOT NULL DEFAULT '1',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL,
  `civilite` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `show_my_phone` tinyint(1) NOT NULL DEFAULT '1',
  `adress` varchar(255) NOT NULL,
  `black_list` tinyint(1) NOT NULL DEFAULT '0',
  `user_can_add_post` tinyint(1) NOT NULL DEFAULT '1',
  `ads_published` int(11) NOT NULL DEFAULT '0',
  `ads_unpublished` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
