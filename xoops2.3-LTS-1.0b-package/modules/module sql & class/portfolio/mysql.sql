CREATE TABLE `portfolio_service` (
`service_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`service_pid` INT( 4 ) NOT NULL DEFAULT '0',
`service_name` VARCHAR( 255 ) 	NOT NULL default '',
`service_menu_name` VARCHAR( 255 ) 	NOT NULL default '',
`service_desc` text,
`service_image` VARCHAR( 255 ) 	NOT NULL default '',
`service_pushtime` INT( 10 ) NOT NULL default '0',
`service_datetime` INT( 10 ) NOT NULL default '0',
`service_status` INT( 1 ) NOT NULL DEFAULT '0',
`service_weight` INT( 2 ) NOT NULL DEFAULT '0',
`service_tpl` VARCHAR( 255 ) NOT NULL default '',
`dohtml` tinyint(1) NOT NULL default '1',
`dosmiley` tinyint(1) NOT NULL default '0',
`doxcode` tinyint(1) NOT NULL default '0',
`doimage` tinyint(1) NOT NULL default '0',
`dobr` tinyint(1) NOT NULL default '0',
PRIMARY KEY ( `service_id` )
);

CREATE TABLE `portfolio_case` (
`case_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`service_id` INT( 11 ) NOT NULL DEFAULT '0',
`case_title` VARCHAR( 255 )  NOT NULL default '',
`case_menu_title` VARCHAR( 255 ) NOT NULL default '',
`case_summary` text,
`case_description` text,
`case_image` VARCHAR( 255 ) NOT NULL default '',
`case_pushtime` INT( 10 ) NOT NULL default '0',
`case_datetime` INT( 10 ) NOT NULL default '0',
`case_status` INT( 1 ) NOT NULL DEFAULT '0',
`case_weight` INT( 2 ) NOT NULL DEFAULT '0',
`case_tpl` VARCHAR( 255 )  NOT NULL default '',
`dohtml` tinyint(1) NOT NULL default '1',
`dosmiley` tinyint(1) NOT NULL default '0',
`doxcode` tinyint(1) NOT NULL default '0',
`doimage` tinyint(1) NOT NULL default '0',
`dobr` tinyint(1) NOT NULL default '0',
PRIMARY KEY ( `case_id` )
);

CREATE TABLE `portfolio_images` (
`image_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`case_id` INT( 11 ) NOT NULL DEFAULT '0',
`image_title` VARCHAR( 255 )  NOT NULL default '',
`image_desc` text,
`image_file` VARCHAR( 255 ) NOT NULL default '',
`image_status` INT( 1 ) NOT NULL DEFAULT '0',
`image_weight` INT( 2 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `image_id` )
);

CREATE TABLE `portfolio_cs` (
`cs_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`case_id` INT( 11 ) NOT NULL DEFAULT '0',
`service_id` INT( 11 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `cs_id` )
);
