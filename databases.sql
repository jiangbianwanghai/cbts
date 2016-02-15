-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-02-15 17:33:22
-- 服务器版本： 5.6.10
-- PHP Version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- 表的结构 `choc_issue`
--

CREATE TABLE IF NOT EXISTS `choc_issue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `issue_name` varchar(300) DEFAULT NULL COMMENT '任务名称',
  `url` varchar(150) DEFAULT NULL COMMENT '可能关联的Tower地址',
  `issue_summary` varchar(300) DEFAULT NULL COMMENT '说明',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `add_user` varchar(30) DEFAULT NULL COMMENT '添加人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后更新时间',
  `last_user` varchar(30) DEFAULT NULL COMMENT '更新人',
  `repos_id` varchar(100) DEFAULT NULL COMMENT '关联的版本库',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态.-1已删除;1正常;0已关闭',
  `resolve` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:未解决;1:已解决',
  `accept_user` int(11) DEFAULT NULL COMMENT '受理人',
  `accept_time` int(11) DEFAULT NULL COMMENT '受理时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `choc_repos`
--

CREATE TABLE IF NOT EXISTS `choc_repos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `repos_name` varchar(100) DEFAULT NULL COMMENT '代码库名字',
  `repos_name_other` varchar(100) DEFAULT NULL COMMENT '代码库别名',
  `repos_url` varchar(150) DEFAULT NULL COMMENT '代码库地址',
  `repos_group_id` int(11) DEFAULT NULL COMMENT '代码库组别',
  `repos_summary` varchar(200) DEFAULT NULL COMMENT '代码库摘要',
  `merge` tinyint(4) DEFAULT NULL COMMENT '提测前合并:0不需要,1需要',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `add_user` varchar(30) DEFAULT NULL COMMENT '添加人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后编辑时间',
  `last_user` varchar(30) DEFAULT NULL COMMENT '最后编辑人',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0无效;1有效;-1已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- 表的结构 `choc_test`
--

CREATE TABLE IF NOT EXISTS `choc_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `issue_id` int(11) NOT NULL COMMENT '相关任务ID',
  `repos_id` int(10) NOT NULL COMMENT '相关代码库',
  `test_flag` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提测版本标识',
  `trunk_flag` int(10) NOT NULL DEFAULT '0' COMMENT '主干版本标识',
  `test_summary` varchar(300) DEFAULT '' COMMENT '说明',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0待测,1测试中,-3不通过,3通过',
  `rank` tinyint(3) NOT NULL DEFAULT '0' COMMENT '阶段:0开发环境,1:测试环境,2:生产环境',
  `tice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提测:0未提测,1提测成功,-1提测失败',
  `tice_time` int(11) DEFAULT NULL COMMENT '提测时间',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `add_user` varchar(30) DEFAULT NULL COMMENT '添加人',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `last_user` int(10) DEFAULT NULL COMMENT '修改人',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '-1已删除;1正常',
  PRIMARY KEY (`id`),
  KEY `uid` (`add_user`),
  KEY `addtime` (`add_time`),
  KEY `status` (`status`),
  KEY `rank` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提测代码库' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `choc_users`
--

CREATE TABLE IF NOT EXISTS `choc_users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `realname` varchar(30) DEFAULT NULL COMMENT '真实姓名',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `email` varchar(100) DEFAULT '' COMMENT '邮箱',
  `add_time` int(10) DEFAULT NULL COMMENT '添加时间',
  `last_login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=12 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
