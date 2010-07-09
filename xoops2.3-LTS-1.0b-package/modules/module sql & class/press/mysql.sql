CREATE TABLE `press_category` (
  `cat_id` int(11) NOT NULL auto_increment,
  `uid` int(8) NOT NULL default '0',
  `cat_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM ;

CREATE TABLE `press_topics` (
  `topic_id` int(8) NOT NULL auto_increment,
  `cat_id` int(4) NOT NULL default '0',
  `uid` int(8) NOT NULL default '0',
  `topic_pid` int(8) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `summary` text,
  `content` text,
  `topic_date` int(10) NOT NULL default '0',
  `access` tinyint(1) NOT NULL default '0',
  `state` tinyint(1) NOT NULL default '0',
  `view` int(8) NOT NULL default '0',
  `post` int(8) NOT NULL default '0',
  `lastdate` int(10) NOT NULL default '0',
  `attachments` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`)
) ENGINE=MyISAM;