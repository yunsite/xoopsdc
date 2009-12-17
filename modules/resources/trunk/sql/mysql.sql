CREATE TABLE `resources` (
  `resources_id` int(11) NOT NULL auto_increment,
  `cat_id` int(8) NOT NULL,
  `uid` int(8) NOT NULL,
  `resources_pid` int(11) NOT NULL,
  `resources_content` text NOT NULL,
  `resources_subject` varchar(255) NOT NULL,
  `resources_attachment` int(8) NOT NULL,
  `resources_dateline` int(10) NOT NULL,
  `resources_state` tinyint(1) NOT NULL default '0',
  `resources_comments` int(8) NOT NULL,
  PRIMARY KEY  (`resources_id`),
  KEY `cat_id` (`cat_id`,`uid`,`resources_pid`)
) ENGINE=MyISAM;

CREATE TABLE `resources_attachments` (
  `att_id` int(11) NOT NULL auto_increment,
  `resources_id` int(11) NOT NULL,
  `uid` int(8) NOT NULL,
  `att_filename` varchar(255) NOT NULL,
  `att_attachment` varchar(255) NOT NULL,
  `att_type` varchar(128) NOT NULL,
  `att_size` int(11) NOT NULL,
  `att_dateline` int(10) NOT NULL,
  `att_downloads` int(11) NOT NULL,
  PRIMARY KEY  (`att_id`),
  KEY `resources_id` (`resources_id`,`uid`)
) ENGINE=MyISAM;

CREATE TABLE `resources_category` (
  `cat_id` int(8) NOT NULL auto_increment,
  `cat_name` varchar(255) NOT NULL,
  `cat_description` text NOT NULL,
  `cat_createdate` int(10) NOT NULL,
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM ;

CREATE TABLE `resources_clipboard` (
  `clipboard_id` int(11) NOT NULL,
  `uid` int(8) NOT NULL,
  `resources_id` int(11) NOT NULL,
  `clipboard_dateline` int(10) NOT NULL,
  PRIMARY KEY  (`clipboard_id`),
  KEY `uid` (`uid`,`resources_id`)
) ENGINE=MyISAM ;