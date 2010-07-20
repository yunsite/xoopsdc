CREATE TABLE `support_category` (
`cat_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_name` VARCHAR( 255 )  NOT NULL default '',
`cat_desc` text,
`cat_image` VARCHAR( 255 ) NOT NULL default '',
`cat_status` INT( 1 ) NOT NULL DEFAULT '0',
`cat_weight` INT( 2 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `cat_id` )
);

CREATE TABLE `support_cat_users_link` (
`link_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_id` INT( 11 ) NOT NULL DEFAULT '0',
`uid` INT( 11 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `link_id` )
);

CREATE TABLE `support_process` (
`pro_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_id` INT( 11 ) NOT NULL DEFAULT '0',
`subject` VARCHAR( 255 ) NOT NULL default '',
`infomation` text,
`customer_id` INT( 11 ) NOT NULL DEFAULT '0',
`support_id` INT( 11 ) NOT NULL DEFAULT '0',
`grate_time` INT( 10 ) NOT NULL DEFAULT '0',
`update_time` INT( 10 ) NOT NULL DEFAULT '0',
`last_reply_time` INT( 10 ) NOT NULL DEFAULT '0',
`status` VARCHAR( 100 ) NOT NULL DEFAULT '0',
`dohtml` tinyint(1) NOT NULL default '1',
`dosmiley` tinyint(1) NOT NULL default '0',
`doxcode` tinyint(1) NOT NULL default '0',
`doimage` tinyint(1) NOT NULL default '0',
`dobr` tinyint(1) NOT NULL default '0',
PRIMARY KEY ( `pro_id` )
);

CREATE TABLE `support_transform` (
`tran_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`pro_id` INT( 11 ) NOT NULL DEFAULT '0',
`tran_action` VARCHAR( 100 ) NOT NULL DEFAULT '0',
`uid` INT( 11 ) NOT NULL DEFAULT '0',
`tran_desc` text,
`grate_time` INT( 10 ) NOT NULL DEFAULT '0',
`forword_uid` INT( 11 ) NOT NULL DEFAULT '0',
`dohtml` tinyint(1) NOT NULL default '1',
`dosmiley` tinyint(1) NOT NULL default '0',
`doxcode` tinyint(1) NOT NULL default '0',
`doimage` tinyint(1) NOT NULL default '0',
`dobr` tinyint(1) NOT NULL default '0',
PRIMARY KEY ( `tran_id` )
);

CREATE TABLE `support_annex` (
`annex_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`pro_id` INT( 11 ) NOT NULL DEFAULT '0',
`tran_id` INT( 11 ) NOT NULL DEFAULT '0',
`annex_file` VARCHAR( 255 )  NOT NULL default '',
`annex_title` VARCHAR( 255 )  NOT NULL default '',
`annex_type` VARCHAR( 255 )  NOT NULL default '',
`uid` INT( 11 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `annex_id` )
);
