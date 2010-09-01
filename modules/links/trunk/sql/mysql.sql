CREATE TABLE `links_category` (
`cat_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_name` VARCHAR( 255 ) NOT NULL default '',
`cat_desc` text,
`cat_order` int( 2 ) NOT NULL default '0' ,
`cat_status` tinyint (1) NOT NULL default '0' ,	
PRIMARY KEY ( `cat_id` )
);

CREATE TABLE `links_link` (
`link_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_id` int( 11 ) NOT NULL default '0' ,
`link_title` VARCHAR( 255 ) NOT NULL default '',
`link_url` text,
`link_desc` text,
`link_order` int(2) NOT NULL default '0' ,
`link_status` tinyint (1) NOT NULL default '0' ,	
`link_image` VARCHAR( 255 ) NOT NULL default '',
`link_dir` VARCHAR( 255 ) NOT NULL default '',
`published` int( 10 ) NOT NULL default '0' ,
`datetime` int( 10 ) NOT NULL default '0' ,
`link_contact` VARCHAR( 255 )  NOT NULL default '',
PRIMARY KEY ( `link_id` )
);