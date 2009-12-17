CREATE TABLE `ilog_article` (
`id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uid` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
`uname` VARCHAR( 25 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`keywords` VARCHAR( 255 ) NOT NULL ,
`summary` TEXT NULL ,
`text_body` 		mediumtext,	
`time_create` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
`time_publish` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
`status` tinyint( 1 ) UNSIGNED NOT NULL DEFAULT '0',
`counter` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
`comments` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
`trackbacks` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
`dohtml` tinyint( 1 ) UNSIGNED NOT NULL DEFAULT '1',
`doxcode` tinyint( 1 ) UNSIGNED NOT NULL DEFAULT '0',
`dosmiley` tinyint( 1 ) UNSIGNED NOT NULL DEFAULT '0',
`doimage` tinyint( 1 ) UNSIGNED NOT NULL DEFAULT '0',
`dobr` tinyint( 1 ) UNSIGNED NOT NULL DEFAULT '0',

PRIMARY KEY ( `id` )
) TYPE=MyISAM;
