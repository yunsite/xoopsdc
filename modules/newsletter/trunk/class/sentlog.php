<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class NewsletterSentlog extends XoopsObject
{
    function __construct() {
        $this->initVar('sent_log_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('letter_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('is_sent', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('sent_time', XOBJ_DTYPE_INT, 0, false);
    }
}

class NewsletterSentlogHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'newsletter_sent_log', 'NewsletterSentlog', 'sent_log_id');
    }
    
    function createLog($letter_id = null, $uid = null)
    {
        $sentlog_handler = xoops_getmodulehandler('sentlog','newsletter');
        
        $log_id = null;
        
        if(!empty($letter_id) && !empty($uid)) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('uid', $uid));
            $criteria->add(new Criteria('letter_id', $letter_id));
            if(!$sentlog_handler->getCount($criteria)) {
                $sentlog_obj = $sentlog_handler->create();
                $sentlog_obj->setVar('uid', $uid);
                $sentlog_obj->setVar('letter_id', $letter_id);
                $sentlog_obj->setVar('is_sent', 0);
                $sentlog_obj->setVar('sent_time', 0);
                $log_id = $sentlog_handler->insert($sentlog_obj);
            }
        } 
        
        return $log_id;
    }
    
    function IsCreateUsers($letter_id = null)
    {
        $sentlog_handler = xoops_getmodulehandler('sentlog','newsletter');
        $subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');
        
        $user_ids = array();
        if(!empty($letter_id)) {
            $criteria = new Criteria('letter_id', $letter_id);
            $sentlog = $sentlog_handler->getAll($criteria, null, false);

            if(!empty($sentlog)) {
                foreach ($sentlog as $k=>$v) {
                    $user_ids[] = $v['uid'];
                }
                
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('is_subscribe', 1), 'AND');
                $criteria->add(new Criteria("uid","(".implode(", ",$user_ids). ")","not in"), 'AND');
                $subscribe = $subscribe_handler->getAll($criteria, null, false);
                
                foreach ($subscribe as $k=>$v) {
                    $user_ids[] = $v['uid'];
                }
            }
        }
        
        return $user_ids;
    }
}

?>
