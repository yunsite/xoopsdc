<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SupportProcess extends XoopsObject
{
    function __construct() {
        $this->initVar('pro_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('subject', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('infomation', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('customer_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('support_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('create_time', XOBJ_DTYPE_INT, 0);
        $this->initVar('update_time', XOBJ_DTYPE_INT, 0);
        $this->initVar('last_reply_time', XOBJ_DTYPE_INT, 0);
        $this->initVar('status', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);
    }
    
    function getForm($action = false, $title = _MA_SUPPORT_QUESTIONMANAGEMENT)
    {
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        
        $category_handler =& xoops_getmodulehandler('category', 'support');
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $format = empty($format) ? "e" : $format;
        
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");

        // category
        $criteria = new CriteriaCompo();
      	$criteria->setSort('cat_weight');
      	$criteria->setOrder('ASC');
      	$categories = $category_handler->getList($criteria);
        if(empty($categories)) $categories = array(_MA_SUPPORT_DEFAULTCAT);
        $cat_select = new XoopsFormSelect(_MA_SUPPORT_BELONGCAT, "cat_id", $this->getVar("cat_id"));
        $cat_select->addOptionArray($categories);
        $form->addElement($cat_select);
        
        // subject
        $form->addElement(new XoopsFormText(_MA_SUPPORT_QUESTIONNAME, 'subject', 60, 255, $this->getVar('subject', $format)), true);

        // infomation
        $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>$this->getVar('infomation')); 
        $form->addElement(new XoopsFormEditor(_MA_SUPPORT_QUESTIONDESC, 'infomation',$configs), false);
        
        // annex
        $annex = new XoopsFormElementTray(_MA_SUPPORT_ANNEX,'','annex');
        $annex_file = new XoopsFormFile('','annex','');
        $annex_multiLabel = new XoopsFormLabel('','<div><a id="addMore" href="javascript:void(0);">'._MA_SUPPORT_ADDANNEX.'</a></div>');
        $annex->addElement($annex_file);
        $annex->addElement($annex_multiLabel);  
        $form->addElement($annex);  
        
        // gratetime
        if($this->isNew()) $form->addElement(new XoopsFormHidden('create_time', time()));
        $form->addElement(new XoopsFormHidden('update_time', time()));
        
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;         
    }
    
    function replyForm($action = false, $title = _MA_SUPPORT_QUESTIONREPLY, $type = 'reply')
    {
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        
        $member_handler =& xoops_gethandler('member');
        $category_handler =& xoops_getmodulehandler('category', 'support');
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $format = empty($format) ? "e" : $format;
        
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");

        // subject
        $form->addElement(new XoopsFormLabel(_MA_SUPPORT_QUESTIONNAME, $this->getVar('subject', $format)));
        
        // forword
        if($type == 'forword') {
            $criteria = new CriteriaCompo();
          	$criteria->setSort('cat_weight');
          	$criteria->setOrder('ASC');
          	$categories = $category_handler->getList($criteria);
            if(empty($categories)) $categories = array(_MA_SUPPORT_DEFAULTCAT);
            $cat_select = new XoopsFormSelect(_MA_SUPPORT_FORWARDCAT, "cat_id", '');
            $cat_select->addOption(0, _MA_SUPPORT_CHOICE);
            $cat_select->addOptionArray($categories);
            $form->addElement($cat_select);
            
            $support = new XoopsFormElementTray(_MA_SUPPORT_FORWORDMANGER,'','support');
            $support_select = new XoopsFormSelect('', "forword", '');
            $support_select->addOption('', _MA_SUPPORT_CHOICE);
            $support->addElement($support_select);
            $support_multiLabel = new XoopsFormLabel('',_MA_SUPPORT_NOCHOICEMANGER);
            $support->addElement($support_multiLabel);
            $form->addElement($support);
        }
        
        // infomation
        $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>''); 
        $form->addElement(new XoopsFormEditor(_MA_SUPPORT_QUESTIONDESC, 'infomation',$configs));
        
        // annex
        $annex = new XoopsFormElementTray(_MA_SUPPORT_ANNEX,'','annex');
        $annex_file = new XoopsFormFile('','annex','');
        $annex_multiLabel = new XoopsFormLabel('','<div><a id="addMore" href="javascript:void(0);">'._MA_SUPPORT_ADDANNEX.'</a></div>');
        $annex->addElement($annex_file);
        $annex->addElement($annex_multiLabel);  
        $form->addElement($annex);  
        
        // gratetime
        if($this->isNew()) $form->addElement(new XoopsFormHidden('create_time', time()));
        
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;         
    }
}

class SupportProcessHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'support_process', 'SupportProcess', 'pro_id', 'subject');
    }
}

?>
