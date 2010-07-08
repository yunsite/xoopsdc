CREATE TABLE `press_category` (
  `cat_id` int(11) NOT NULL auto_increment,
  `uid` int(8) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM ;

CREATE TABLE `press_topics` (
  `topic_id` int(8) NOT NULL auto_increment,
  `cat_id` int(4) NOT NULL,
  `uid` int(8) NOT NULL,
  `topic_pid` int(8) NOT NULL default '0',
  `subject` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `topic_date` int(10) NOT NULL,
  `access` tinyint(1) NOT NULL default '0',
  `state` tinyint(1) NOT NULL,
  `view` int(8) NOT NULL,
  `post` int(8) NOT NULL default '0',
  `lastdate` int(10) NOT NULL,
  `attachments` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`)
) ENGINE=MyISAM;