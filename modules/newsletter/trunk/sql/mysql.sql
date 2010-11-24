CREATE TABLE `newsletter_subscribe` (
`subscribe_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`uid` INT( 4 ) NOT NULL DEFAULT '0',
`is_subscribe` tinyint(1) NOT NULL default '0',
`updatetime` INT( 10 ) NOT NULL ,
PRIMARY KEY ( `subscribe_id` )
);

CREATE TABLE `newsletter_subscribe_log` (
`subscribe_log_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`uid` INT( 11 ) NOT NULL DEFAULT '0',
`subscribe_timestamp` INT( 10 ) NOT NULL ,
`subscribe_action` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `subscribe_log_id` )
);

CREATE TABLE `newsletter_model` (
`model_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`peried` VARCHAR( 255 ) NOT NULL ,
`time_difference` INT( 10 ) NOT NULL DEFAULT '0',
`next_create_time` INT( 10 ) NOT NULL DEFAULT '0',
`last_create_time` INT( 10 ) NOT NULL DEFAULT '0',
`tpl_name` VARCHAR( 255 ) NOT NULL ,
`model_type` VARCHAR( 255 ) NOT NULL ,
`model_title` VARCHAR( 255 ) NOT NULL ,
`header_img` VARCHAR( 255 ) NOT NULL ,
`header_desc` TEXT,
`footer_desc` TEXT,
`status` tinyint(1) NOT NULL default '0',
`dohtml` TINYINT( 1 ) NOT NULL DEFAULT '1',
`doxcode` TINYINT( 1 ) NOT NULL DEFAULT '0',
`dosmiley` TINYINT( 1 ) NOT NULL DEFAULT '0',
`doimage` TINYINT( 1 ) NOT NULL DEFAULT '0',
`dobr` TINYINT( 1 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `model_id` )
);

CREATE TABLE `newsletter_content` (
`letter_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`model_id` INT( 11 ) NOT NULL DEFAULT '0',
`letter_title` VARCHAR( 255 ) NOT NULL ,
`letter_content` text NOT NULL ,
`create_time` INT( 10 ) NOT NULL DEFAULT '0',
`is_users` tinyint(1) NOT NULL default '0',
`is_sent` tinyint(1) NOT NULL default '0',
`dohtml` TINYINT( 1 ) NOT NULL DEFAULT '1',
`doxcode` TINYINT( 1 ) NOT NULL DEFAULT '0',
`dosmiley` TINYINT( 1 ) NOT NULL DEFAULT '0',
`doimage` TINYINT( 1 ) NOT NULL DEFAULT '0',
`dobr` TINYINT( 1 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `letter_id` )
);

CREATE TABLE `newsletter_sent_log` (
`sent_log_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`uid` INT( 11 ) NOT NULL DEFAULT '0',
`letter_id` INT( 11 ) NOT NULL ,
`is_sent` tinyint(1) NOT NULL default '0',
`sent_time` INT( 10 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `sent_log_id` )
);
