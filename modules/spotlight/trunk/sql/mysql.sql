CREATE TABLE `sp_spotlight` (
`sp_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`sp_name` VARCHAR ( 255 ) NOT NULL ,
`sp_desc` TEXT NOT NULL ,
`component_name` VARCHAR ( 255 ) NOT NULL default 'default',
`component_option` VARCHAR ( 255 ) NOT NULL default '',
PRIMARY KEY ( `sp_id` )
);

CREATE TABLE `sp_page` (
`page_id` INT ( 11 ) NOT NULL AUTO_INCREMENT ,
`sp_id` INT ( 11 ) NOT NULL ,
`page_title` VARCHAR ( 255 ) NOT NULL ,
`page_desc` TEXT NOT NULL ,
`page_link` TEXT NOT NULL ,
`page_image` VARCHAR ( 255 ) NOT NULL ,
`published` INT( 10 ) NOT NULL ,
`datetime` INT( 10 ) NOT NULL ,
`page_order` INT ( 2 ) NOT NULL default '99' ,	
`page_status` TINYINT (1) NOT NULL default '1' ,
`mid` INT ( 2 ) NOT NULL default '0' ,
`id` INT ( 11 ) NOT NULL default '0' ,
PRIMARY KEY ( `page_id` ),
KEY `page_status` 		(`page_status`),
KEY `page_order` 		(`page_order`)
);
