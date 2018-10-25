/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 100121
Source Host           : localhost:3306
Source Database       : tk_social

Target Server Type    : MYSQL
Target Server Version : 100121
File Encoding         : 65001

Date: 2017-06-04 23:00:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for friend
-- ----------------------------
DROP TABLE IF EXISTS `friend`;
CREATE TABLE `friend` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `user1` int(9) unsigned NOT NULL,
  `user2` int(9) unsigned NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user1` (`user1`),
  KEY `user2` (`user2`),
  CONSTRAINT `friend_ibfk_1` FOREIGN KEY (`user1`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `friend_ibfk_2` FOREIGN KEY (`user2`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of friend
-- ----------------------------
INSERT INTO `friend` VALUES ('18', '1', '3', '2017-06-03 20:06:04');
INSERT INTO `friend` VALUES ('19', '2', '3', '2017-06-03 20:06:07');
INSERT INTO `friend` VALUES ('20', '2', '1', '2017-06-03 20:07:38');
INSERT INTO `friend` VALUES ('21', '5', '1', '2017-06-03 20:17:12');
INSERT INTO `friend` VALUES ('22', '2', '7', '2017-06-04 21:24:26');
INSERT INTO `friend` VALUES ('28', '7', '1', '2017-06-04 22:51:02');

-- ----------------------------
-- Table structure for friend_request
-- ----------------------------
DROP TABLE IF EXISTS `friend_request`;
CREATE TABLE `friend_request` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user1` int(9) unsigned NOT NULL,
  `user2` int(9) unsigned NOT NULL,
  `status` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user1` (`user1`),
  KEY `user2` (`user2`),
  CONSTRAINT `friend_request_ibfk_1` FOREIGN KEY (`user1`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `friend_request_ibfk_2` FOREIGN KEY (`user2`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of friend_request
-- ----------------------------
INSERT INTO `friend_request` VALUES ('5', '1', '4', null);
INSERT INTO `friend_request` VALUES ('12', '5', '4', null);
INSERT INTO `friend_request` VALUES ('26', '2', '7', '1');
INSERT INTO `friend_request` VALUES ('27', '5', '1', '1');

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `user1` int(9) unsigned NOT NULL,
  `user2` int(9) unsigned NOT NULL,
  `txt` varchar(2048) NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user1` (`user1`),
  KEY `user2` (`user2`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user1`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`user2`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of message
-- ----------------------------
INSERT INTO `message` VALUES ('64', '1', '4', 'hello', '1496595091', '0');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `surname` varchar(127) NOT NULL,
  `age` int(3) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `birthday` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'Tigran', 'Koshetsyan', '20', 'admin@gmail.com', '$2y$10$TDBz2JsvN7EvrYrvhPVR3O09COxC2mhBKAISYbEmiCch0/n11Gjqy', '2017-06-04 21:51:21', '2017-06-04 21:51:21', '1');
INSERT INTO `user` VALUES ('2', 'John', 'Smith', '41', 'anything@example.com', '$2y$10$zh/RtRJp66EAlB0OQi/1aulqnLbrQRCqHqgDQNyUcPogmH2zF6Cj.', '2017-06-04 21:11:39', '0000-00-00 00:00:00', '0');
INSERT INTO `user` VALUES ('3', 'Jack', 'Smith', '62', 'anything2@example.com', '$2y$10$XXrKcBZbBBH.kF6n6v0Z2.enP1JQ83seeFpztSrLbyb32lmMhDfQC', '2017-06-04 21:11:36', '0000-00-00 00:00:00', '0');
INSERT INTO `user` VALUES ('4', 'rwet', 'ert', '32', 'anything1@example.com', '$2y$10$urZ.L8b9huob9Qw6ZfEPf.CLlEFMO20R.63lkX5HQUmqKmff/JCFq', '2017-06-04 21:11:32', '0000-00-00 00:00:00', '0');
INSERT INTO `user` VALUES ('5', 'asd', 'qwe', '12', 'anything3@example.com', '$2y$10$azzPNWn2zKWAIfyoBSHhuu1rnpzZyG0pN5j.TTVTOInHygqQqoW8O', '2017-06-04 21:11:30', '0000-00-00 00:00:00', '0');
INSERT INTO `user` VALUES ('7', 'Arman', 'Hovhannisyan', '25', 'arman@gmail.com', '$2y$10$mJWngpmMYnRfTJQSHIPUC.lU2BAY9yeqTs0E1sfxvohR73eKxcz0K', '2017-06-04 22:59:50', '2017-06-04 22:59:50', '0');

-- ----------------------------
-- Table structure for user_image
-- ----------------------------
DROP TABLE IF EXISTS `user_image`;
CREATE TABLE `user_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `user_id` int(9) unsigned NOT NULL,
  `main` int(1) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `image` (`image`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `user_image_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of user_image
-- ----------------------------

-- ----------------------------
-- View structure for profileimage
-- ----------------------------
DROP VIEW IF EXISTS `profileimage`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `profileimage` AS SELECT user_id id,image FROM user_image
WHERE main = 1 ;

-- ----------------------------
-- View structure for requestinfo
-- ----------------------------
DROP VIEW IF EXISTS `requestinfo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `requestinfo` AS SELECT fr.user1, fr.user2, fr.id request_id, ui.`name`, ui.surname, ui.image, fr.`status`
FROM friend_request fr
JOIN userinfo ui ON fr.user2 = ui.id
WHERE fr.`status` is not NULL 
UNION 
SELECT fr.user2 user1, fr.user1 user2, fr.id request_id, ui.`name`, ui.surname, ui.image, fr.`status`
FROM friend_request fr
JOIN userinfo ui ON fr.user1 = ui.id
WHERE fr.`status` is NULL ;

-- ----------------------------
-- View structure for userinfo
-- ----------------------------
DROP VIEW IF EXISTS `userinfo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `userinfo` AS SELECT `user`.id, `name`, surname, image, email, `status` FROM `user`
LEFT JOIN profileImage ON profileImage.id = `user`.id ;
SET FOREIGN_KEY_CHECKS=1;
