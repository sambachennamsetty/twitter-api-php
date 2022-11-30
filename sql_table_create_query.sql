CREATE TABLE `tweet` (
  `tweet_id` varchar(100) DEFAULT NULL,
  `text` longtext,
  `time` varchar(45) DEFAULT NULL,
  `profile_url` varchar(200) DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
);