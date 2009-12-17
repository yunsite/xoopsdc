CREATE TABLE `links_category` (
`cat_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_name` VARCHAR( 255 ) NOT NULL ,
`cat_desc` text NOT NULL ,
`cat_order` int( 2 ) NOT NULL default '0' ,
`cat_status` tinyint (1) NOT NULL default '0' ,	
PRIMARY KEY ( `cat_id` )
);

CREATE TABLE `links_link` (
`link_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_id` int( 11 ) NOT NULL ,
`link_title` VARCHAR( 255 ) NOT NULL ,
`link_url` text NOT NULL ,
`link_desc` text NOT NULL ,
`link_order` int( 2) NOT NULL ,
`link_status` tinyint (1) NOT NULL default '0' ,	
`link_image` VARCHAR( 255 ) NOT NULL ,
`link_dir` VARCHAR( 255 ) NOT NULL ,
`published` int( 10 ) NOT NULL ,
`datetime` int( 10 ) NOT NULL ,
`link_contact` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `link_id` )
);
