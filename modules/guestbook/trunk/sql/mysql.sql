#
# Table structure for table `guestbook_guest`
#
		
CREATE TABLE  `guestbook_guest` (
`guest_id` int (8)   NOT NULL  auto_increment,
`guest_content` text   NULL ,
`guest_name` varchar (255)   NOT NULL ,
`guest_email` varchar (255)   NOT NULL ,
`guest_url` varchar (255)   NOT NULL ,
`guest_submitter` int (10)   NOT NULL default '0',
`guest_date_created` int (10)   NOT NULL default '0',
`guest_online` tinyint (1)   NOT NULL default '0',
PRIMARY KEY (`guest_id`)
) ENGINE=MyISAM;

