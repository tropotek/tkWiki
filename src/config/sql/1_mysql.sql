

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) NOT NULL DEFAULT '',
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `hash` varchar(255) NOT NULL DEFAULT '',
  `modified` DATETIME NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

INSERT INTO `user` (`id`, `name`, `email`, `username`, `password`, `role`, `active`, `hash`, `modified`, `created`)
VALUES
  (NULL, 'Administrator', 'admin@example.com', 'admin', MD5('password'), 'admin', 1, MD5('1:admin:admin@example.com'), NOW() , NOW()),
  (NULL, 'User 1', 'user@example.com', 'user1', MD5('password'), 'user', 1, MD5('2:user:user@example.com'), NOW() , NOW())
;











