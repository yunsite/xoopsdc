CREATE TABLE `portfolio_service` (
`service_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`service_pid` INT( 4 ) NOT NULL DEFAULT '0',
`service_name` VARCHAR( 255 ) NOT NULL ,
`service_menu_name` VARCHAR( 255 ) NOT NULL ,
`service_desc` text NOT NULL ,
`service_image` VARCHAR( 255 ) NOT NULL ,
`service_pushtime` INT( 10 ) NOT NULL ,
`service_datetime` INT( 10 ) NOT NULL ,
`service_status` INT( 1 ) NOT NULL DEFAULT '0',
`service_weight` INT( 2 ) NOT NULL DEFAULT '0',
`service_tpl` VARCHAR( 255 ) NOT NULL ,
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
`case_title` VARCHAR( 255 ) NOT NULL ,
`case_menu_title` VARCHAR( 255 ) NOT NULL ,
`case_summary` text NOT NULL ,
`case_description` text NOT NULL ,
`case_image` VARCHAR( 255 ) NOT NULL ,
`case_pushtime` INT( 10 ) NOT NULL ,
`case_datetime` INT( 10 ) NOT NULL ,
`case_status` INT( 1 ) NOT NULL DEFAULT '0',
`case_weight` INT( 2 ) NOT NULL DEFAULT '0',
`case_tpl` VARCHAR( 255 ) NOT NULL ,
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
`image_title` VARCHAR( 255 ) NOT NULL ,
`image_desc` text NOT NULL ,
`image_file` VARCHAR( 255 ) NOT NULL ,
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
