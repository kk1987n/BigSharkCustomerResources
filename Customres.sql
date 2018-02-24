/*
Navicat MySQL Data Transfer

Source Server         : 3号
Source Server Version : 50635
Source Host           : 0.0.0.0:3306
Source Database       : Customres

Target Server Type    : MYSQL
Target Server Version : 50635
File Encoding         : 65001

Date: 2018-02-24 17:21:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for m_config
-- ----------------------------
DROP TABLE IF EXISTS `m_config`;
CREATE TABLE `m_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_config
-- ----------------------------
INSERT INTO `m_config` VALUES ('1', '{\"resetTimeCustom\":\"72\"}');

-- ----------------------------
-- Table structure for m_custom
-- ----------------------------
DROP TABLE IF EXISTS `m_custom`;
CREATE TABLE `m_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '0释放的客户,n被客户经理绑定的资源',
  `adder` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '客户姓名',
  `age` int(11) DEFAULT NULL COMMENT '年龄',
  `sex` tinyint(2) DEFAULT NULL COMMENT '性别',
  `phone` varchar(16) DEFAULT NULL COMMENT '手机号',
  `is_success` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未签,1签单',
  `successer` int(11) DEFAULT '0' COMMENT '签单客户经理',
  `status` int(11) DEFAULT '0' COMMENT '对应status表',
  `address` varchar(1024) DEFAULT NULL COMMENT '地址',
  `zuoji` varchar(16) DEFAULT NULL COMMENT '座机号',
  `keyword` varchar(255) DEFAULT NULL COMMENT '客户关键词,政府,广告,媒体等等',
  `des` varchar(255) DEFAULT NULL COMMENT '简短描述',
  `from` varchar(255) DEFAULT NULL COMMENT '客户来源',
  `wx` varchar(32) DEFAULT NULL COMMENT '微信',
  `qq` varchar(16) DEFAULT NULL COMMENT 'qq',
  `email` varchar(255) DEFAULT NULL,
  `web` varchar(255) DEFAULT NULL COMMENT '网址',
  `company` varchar(255) DEFAULT NULL COMMENT '公司名',
  `comp_type` varchar(255) DEFAULT NULL COMMENT '公司类型',
  `comp_scale` varchar(255) DEFAULT NULL COMMENT '公司规模',
  `comp_skill` varchar(255) DEFAULT NULL COMMENT '主营业务',
  `content` text COMMENT '额外重要信息',
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  `atime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_custom
-- ----------------------------

-- ----------------------------
-- Table structure for m_follow
-- ----------------------------
DROP TABLE IF EXISTS `m_follow`;
CREATE TABLE `m_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '客户id',
  `uid` int(11) DEFAULT NULL COMMENT '客户经理Id',
  `status` int(11) NOT NULL COMMENT '对应status表',
  `content` text COMMENT '跟踪描述',
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_follow
-- ----------------------------

-- ----------------------------
-- Table structure for m_status
-- ----------------------------
DROP TABLE IF EXISTS `m_status`;
CREATE TABLE `m_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_status
-- ----------------------------
INSERT INTO `m_status` VALUES ('0', '新客户');
INSERT INTO `m_status` VALUES ('1', '普通客户');
INSERT INTO `m_status` VALUES ('2', '意向客户');
INSERT INTO `m_status` VALUES ('3', '精准客户');
INSERT INTO `m_status` VALUES ('999', '签订客户');
INSERT INTO `m_status` VALUES ('1000', '失败客户');

-- ----------------------------
-- Table structure for m_user
-- ----------------------------
DROP TABLE IF EXISTS `m_user`;
CREATE TABLE `m_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `acc` varchar(32) NOT NULL,
  `salt` varchar(16) DEFAULT NULL,
  `pwd` varchar(32) NOT NULL,
  `follow_cnt` int(11) DEFAULT '0',
  `ltime` int(11) DEFAULT NULL,
  `lip` int(11) DEFAULT NULL,
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_user
-- ----------------------------
INSERT INTO `m_user` VALUES ('1', '阿鑫', 'admin', 'vUePoeRk', '9b1a3d75e6067af6fe00745f5103cee8', '0', '', '', '2018-02-24 15:33:40');
