-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-02-21 16:06:06
-- 服务器版本： 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `siteboard`
--
CREATE DATABASE IF NOT EXISTS `siteboard` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `siteboard`;

-- --------------------------------------------------------

--
-- 表的结构 `auth_key`
--

CREATE TABLE IF NOT EXISTS `auth_key` (
  `uid` varchar(10) NOT NULL,
  `key` varchar(100) NOT NULL,
  `sessionid` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `checktime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `auth_user`
--

CREATE TABLE IF NOT EXISTS `auth_user` (
  `uid` varchar(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(512) NOT NULL,
  `level` int(2) NOT NULL DEFAULT '1',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `auth_user`
--

INSERT INTO `auth_user` (`uid`, `username`, `password`, `level`, `createtime`) VALUES
('Qho3ymqg5w', 'admin', 'sha512:5000:b3cBUfCMhrvWG0J9L72uNtKMFh2SIScb99HCegHQbE4=:NAA7HIBNsqNqIOWqFCGBrjmtqoJRSvDK521lwy7B2HQ=', 0, '2016-02-17 08:56:35');

-- --------------------------------------------------------

--
-- 表的结构 `siteboard_project`
--

CREATE TABLE IF NOT EXISTS `siteboard_project` (
  `uid` varchar(10) NOT NULL,
  `pid` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text,
  `type` varchar(100) NOT NULL,
  `giturl` varchar(1000) NOT NULL,
  `folder` varchar(1000) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

