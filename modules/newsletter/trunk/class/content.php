<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class NewsletterContent extends XoopsObject
{
    function __construct() {
        $this->initVar('letter_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('model_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('letter_title', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('letter_content', XOBJ_DTYPE_TXTAREA);
        $this->initVar('create_time', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('is_users', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('is_sent', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 0);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);
    }
}

class NewsletterContentHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'newsletter_content', 'NewsletterContent', 'letter_id');
    }
    
    function ActionCreateLetter($model_id = null)
    {
        $model_handler = xoops_getmodulehandler('model','newsletter');
        $newsletter_handler = xoops_getmodulehandler('content','newsletter');
        
        $model_obj = $model_handler->get($model_id);
        
        if(!empty($model_id) && is_object($model_obj)) {
            
            if($model_obj->getVar('model_type') == 'automatic' && $model_obj->getVar('status') && ($model_obj->getVar('next_create_time') <= time())) {
                $letter_id = $this->CreateLetter($model_id);
            }
            if($model_obj->getVar('model_type') == 'manual' && $model_obj->getVar('status') && ($model_obj->getVar('next_create_time') <= time()) && ($model_obj->getVar('next_create_time') != $model_obj->getVar('last_create_time')) ) {
            //if($model_obj->getVar('model_type') == 'manual' && $model_obj->getVar('status')) {
                $letter_id = $this->CreateLetter($model_id);
            }
            
            if(isset($letter_id)) {
                return $letter_id;
            }
        }
    }
    
    function CreateLetter($model_id = null)
    {
        $model_handler = xoops_getmodulehandler('model','newsletter');
        $newsletter_handler = xoops_getmodulehandler('content','newsletter');
        
        $model_obj = $model_handler->get($model_id);
        
        if(!empty($model_id) && is_object($model_obj)) { 
            include_once XOOPS_ROOT_PATH . "/modules/newsletter/include/functions.render.php";
            include_once XOOPS_ROOT_PATH . '/class/template.php';
            $xoopsTpl = new XoopsTpl();
            
            $xoopsTpl->assign('letter_title', $model_obj->getVar('model_title'));
            $xoopsTpl->assign('header_img', $model_obj->getVar('header_img'));
            $xoopsTpl->assign('header_desc', $model_obj->getVar('header_desc'));
            $xoopsTpl->assign('footer_desc', $model_obj->getVar('footer_desc'));
            
            $tplName = newsletter_getTemplate($model_obj->getVar('model_type'), $model_obj->getVar('tpl_name'));
            $tplName = XOOPS_ROOT_PATH . "/modules/newsletter/templates/".$tplName; 
            $letter_content = $xoopsTpl->fetch($tplName);
            
            //create letter 
            $newsletter_obj = $newsletter_handler->create();
            $newsletter_obj->setVar('model_id', $model_obj->getVar('model_id'));
            $newsletter_obj->setVar('letter_title', $model_obj->getVar('model_title'));
            $newsletter_obj->setVar('letter_content', $letter_content);
            $newsletter_obj->setVar('create_time', time());
            $newsletter_obj->setVar('is_users', 0);
            $newsletter_obj->setVar('is_sent', 0);
            $letter_id = $newsletter_handler->insert($newsletter_obj);
            
            //update model field in "last_create_time"
            $next_create_time = 0;
            
            switch ($model_obj->getVar('peried')) {
            case 'day':
                $next_create_time = $model_obj->getVar('next_create_time')+24*3600;
                break;
            case 'week':
                $next_create_time = $model_obj->getVar('next_create_time')+7*24*3600;
                break;
            case 'date':
                $next_create_time = $model_obj->getVar('next_create_time')+date('t')*24*3600;
                break;
            case 'manual':
                $next_create_time = time();
                break;
            }

            $model_obj->setVar('next_create_time', $next_create_time);
            $model_obj->setVar('last_create_time', time());
            $model_handler->insert($model_obj);
            
            return $letter_id;
        }
    }
}

?>
