 CREATE TABLE `guestbook_messages` (
`id`         INT( 10 ) UNSIGNED         NOT NULL AUTO_INCREMENT ,
`pid`        INT( 4 )                   NOT NULL DEFAULT '0',
`uid`        mediumint( 8 ) unsigned    NOT NULL DEFAULT '0',
`name`       VARCHAR( 255 )             NOT NULL ,
`email`      VARCHAR( 255 )             NOT NULL ,
`title`      VARCHAR( 255 )             NOT NULL ,
`msg_time`    int(10) unsigned        NOT NULL default '0',
`message`    TEXT                       NOT NULL ,
`approve`    TINYINT( 1 )    unsigned           NOT NULL DEFAULT '0',
PRIMARY KEY ( `id` )
) TYPE=MyISAM;