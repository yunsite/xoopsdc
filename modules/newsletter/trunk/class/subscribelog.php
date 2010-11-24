<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class NewsletterSubscribeLog extends XoopsObject
{
    function __construct() {
        $this->initVar('subscribe_log_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('subscribe_timestamp', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('subscribe_action', XOBJ_DTYPE_TXTBOX,"");
    }
}

class NewsletterSubscribeLogHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'newsletter_subscribe_log', 'NewsletterSubscribeLog', 'subscribe_log_id');
    }
}

?>
