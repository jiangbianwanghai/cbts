# ************************************************************
# Sequel Pro SQL dump
# Version 4500
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 192.168.8.91 (MySQL 5.6.10)
# Database: test
# Generation Time: 2016-05-21 00:36:11 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table choc_accept
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_accept`;

CREATE TABLE `choc_accept` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `accept_user` int(11) DEFAULT NULL COMMENT '受理人',
  `accept_time` int(11) DEFAULT NULL COMMENT '受理时间',
  `issue_id` int(11) DEFAULT NULL COMMENT '任务ID',
  `flow` tinyint(4) DEFAULT NULL COMMENT '受理流.1:发起人;2:执行人;3:验证人;4:发布人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_issue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_issue`;

CREATE TABLE `choc_issue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `plan_id` int(11) DEFAULT NULL COMMENT '计划ID',
  `type` tinyint(4) DEFAULT NULL COMMENT '任务类型1:TASK2:BUG',
  `level` tinyint(4) DEFAULT NULL COMMENT '优先级',
  `issue_name` varchar(300) DEFAULT NULL COMMENT '任务名称',
  `url` text COMMENT '可能关联的Tower地址',
  `issue_summary` text COMMENT '说明',
  `add_user` varchar(30) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_user` varchar(30) DEFAULT NULL COMMENT '更新人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后更新时间',
  `repos_id` varchar(100) DEFAULT NULL COMMENT '关联的版本库',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态.-1已删除;1正常;0已关闭',
  `resolve` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:未解决;1:已解决',
  `accept_user` int(11) DEFAULT NULL COMMENT '受理人',
  `accept_time` int(11) DEFAULT NULL COMMENT '受理时间',
  `deadline` int(11) DEFAULT NULL COMMENT '截至时间',
  `repose_num` tinyint(4) DEFAULT NULL COMMENT '影响的代码库数量',
  `bug_num` tinyint(4) DEFAULT NULL COMMENT '产生的BUG量',
  `workflow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '工作流:0新建,1开发中,2开发完毕,3修复中,4修复完毕,5测试中,6测试通过,7上线',
  `watch` varchar(255) DEFAULT NULL COMMENT '关注者',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_issue_bug
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_issue_bug`;

CREATE TABLE `choc_issue_bug` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `plan_id` int(11) DEFAULT NULL COMMENT '计划ID',
  `level` tinyint(4) DEFAULT NULL COMMENT '优先级.1:轻微;2:轻;3:严重;4:超严重',
  `issue_id` int(11) DEFAULT NULL COMMENT '所属任务ID',
  `test_id` int(11) DEFAULT NULL COMMENT '所属提测ID',
  `subject` varchar(300) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `add_user` int(11) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `accept_user` int(11) DEFAULT NULL COMMENT '受理人',
  `accept_time` int(11) DEFAULT NULL COMMENT '受理时间',
  `last_user` int(11) DEFAULT NULL COMMENT '最后修改人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后更改时间',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态.-1非BUG;0未确认,1已确认,3已处理',
  `check_time` int(11) DEFAULT NULL COMMENT '确认时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '1:正常;-1:删除;0:关闭',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务产生的BUG表';



# Dump of table choc_issue_bug_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_issue_bug_comment`;

CREATE TABLE `choc_issue_bug_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `bug_id` int(11) DEFAULT NULL COMMENT 'BUG_ID',
  `content` text COMMENT '评论详情',
  `add_user` int(11) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_user` int(11) DEFAULT NULL COMMENT '最后修改人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后修改时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态.1:正常;-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_issue_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_issue_comment`;

CREATE TABLE `choc_issue_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `issue_id` int(11) DEFAULT NULL COMMENT 'ISSUE_ID',
  `content` text COMMENT '评论详情',
  `add_user` int(11) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_user` int(11) DEFAULT NULL COMMENT '最后修改人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后修改时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态.1:正常;-1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_plan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_plan`;

CREATE TABLE `choc_plan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `plan_name` varchar(255) DEFAULT NULL COMMENT '计划名称',
  `plan_discription` text COMMENT '计划描述',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '进度.1:新建;2:开发;3:测试中;4;上线',
  `startime` int(11) DEFAULT NULL COMMENT '开始时间',
  `endtime` int(11) DEFAULT NULL COMMENT '结束时间',
  `add_user` int(11) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_user` int(11) DEFAULT NULL COMMENT '最后修改人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后修改时间',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态.1:正常;-1删除',
  `timeline` varchar(255) DEFAULT NULL COMMENT '时间线',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_project
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_project`;

CREATE TABLE `choc_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `md5` char(32) DEFAULT NULL COMMENT 'MD5加密后的项目标识',
  `salt` char(6) DEFAULT NULL COMMENT '盐',
  `project_name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `project_discription` text COMMENT '项目描述',
  `add_user` int(11) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_user` int(11) DEFAULT NULL COMMENT '最后修改人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_repos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_repos`;

CREATE TABLE `choc_repos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `repos_name` varchar(100) DEFAULT NULL COMMENT '代码库名字',
  `repos_name_other` varchar(100) DEFAULT NULL COMMENT '代码库别名',
  `repos_url` varchar(150) DEFAULT NULL COMMENT '代码库地址',
  `repos_group_id` int(11) DEFAULT NULL COMMENT '代码库组别',
  `repos_summary` varchar(200) DEFAULT NULL COMMENT '代码库摘要',
  `merge` tinyint(4) DEFAULT NULL COMMENT '提测前合并:0不需要,1需要',
  `add_user` varchar(30) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `last_user` varchar(30) DEFAULT NULL COMMENT '最后编辑人',
  `last_time` int(11) DEFAULT NULL COMMENT '最后编辑时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0无效;1有效;-1已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_star
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_star`;

CREATE TABLE `choc_star` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `add_user` int(11) DEFAULT NULL COMMENT '添加人',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `star_id` int(11) DEFAULT NULL COMMENT '收藏的id',
  `star_type` int(11) DEFAULT NULL COMMENT '类型.1:issue;2:test;3:bug',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table choc_test
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_test`;

CREATE TABLE `choc_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `plan_id` int(11) DEFAULT NULL COMMENT '计划ID',
  `issue_id` int(11) NOT NULL COMMENT '相关任务ID',
  `repos_id` int(10) NOT NULL COMMENT '相关代码库',
  `br` varchar(100) DEFAULT NULL COMMENT '分支名字',
  `test_flag` varchar(10) NOT NULL DEFAULT '0' COMMENT '提测版本标识',
  `trunk_flag` int(10) NOT NULL DEFAULT '0' COMMENT '主干版本标识',
  `test_summary` varchar(1000) DEFAULT '' COMMENT '说明',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0待测,1测试中,-3不通过,3通过,5已覆盖',
  `rank` tinyint(3) NOT NULL DEFAULT '0' COMMENT '阶段:0开发环境,1:测试环境,2:生产环境',
  `tice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提测:-7上线失败;0未提测,1提测成功,-1提测失败;3提测中;5上线中;7已上线',
  `tice_time` int(11) DEFAULT NULL COMMENT '提测时间',
  `beizhu` varchar(200) DEFAULT NULL COMMENT '备注.存在提测和发布失败原因',
  `add_user` varchar(30) DEFAULT NULL COMMENT '添加人',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `last_user` int(10) DEFAULT NULL COMMENT '修改人',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `accept_user` int(10) DEFAULT NULL COMMENT '受理人',
  `accept_time` int(10) DEFAULT NULL COMMENT '受理时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '-1已删除;1正常',
  PRIMARY KEY (`id`),
  KEY `uid` (`add_user`),
  KEY `addtime` (`add_time`),
  KEY `status` (`status`),
  KEY `rank` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提测代码库';



# Dump of table choc_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `choc_users`;

CREATE TABLE `choc_users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `realname` varchar(30) DEFAULT NULL COMMENT '真实姓名',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `email` varchar(100) DEFAULT '' COMMENT '邮箱',
  `add_time` int(10) DEFAULT NULL COMMENT '添加时间',
  `last_login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `role` tinyint(4) DEFAULT NULL COMMENT '角色,1:测试;2:研发;3:产品',
  `unsubscribe` tinyint(4) NOT NULL DEFAULT '0' COMMENT '退订.0:未退订,1:退订',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
