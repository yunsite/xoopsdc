<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class NewsletterSubscribe extends XoopsObject
{
    function __construct() {
        $this->initVar('subscribe_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('is_subscribe', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updatetime', XOBJ_DTYPE_INT, 0, false);
    }
}

class NewsletterSubscribeHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'newsletter_subscribe', 'NewsletterSubscribe', 'subscribe_id');
    }
    
    function getSubscribeUsers($is_subscribe = 1, $getAll = false)
    {
        $subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');
        
        $criteria = new Criteria('is_subscribe', $is_subscribe);
        $subscribes = $subscribe_handler->getAll($criteria, null, false);
        
        
        $users = array();
        if(!empty($subscribes)) {
            if ($getAll == false) {
                foreach ($subscribes as $k=>$v) {
                    $users[] = $v['uid'];
                }
            } else {
                $users = $subscribes;
            }
        }
        return $users;
    }
}

?>
