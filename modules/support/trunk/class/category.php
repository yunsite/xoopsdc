<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SupportCategory extends XoopsObject
{
    function __construct() {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_name', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('cat_desc', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('cat_image', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('cat_status', XOBJ_DTYPE_INT, 0);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT, 0);
    }
    
    function getForm($action = false)
    {
        global $xoopsModuleConfig;
        
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $linkusers_handler =& xoops_getmodulehandler('linkusers','support');
        $member_handler =& xoops_gethandler('member');
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $title = $this->isNew() ? _MA_SUPPORT_ADDCAT : _MA_SUPPORT_UPDATECAT;
        $format = empty($format) ? "e" : $format;
        $status = $this->isNew() ? 1 : $this->getVar('cat_status');
        
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");
        
        //名稱
        $form->addElement(new XoopsFormText(_MA_SUPPORT_CATNAME, 'cat_name', 60, 255, $this->getVar('cat_name', $format)), true);
        
        //題頭圖片
        $cat_image = new XoopsFormElementTray(_MA_SUPPORT_CATLOGO,'', 'image');
        if( $this->getVar('cat_image') ){
            $cat_image->addElement(new XoopsFormLabel('', '<img width="100" src="'.XOOPS_URL.'/uploads/support/'.$this->getVar('cat_image').'"><br><br>'));
            $delete_check = new XoopsFormCheckBox('','delete_image');
            $delete_check->addOption(1,_DELETE);
            $cat_image->addElement($delete_check);
            $display = _MA_SUPPORT_REUPLOADLOGOTIP;
        }else{
            $display = '';
        }
        $cat_image->addElement(new XoopsFormFile('','cat_image',1024*1024*2));
        $cat_image->addElement(new XoopsFormLabel('',_MA_SUPPORT_UPLOADSTYLE));
        $cat_image->addElement(new XoopsFormLabel('', $display)); 	
        $form->addElement($cat_image);

        //服務介紹
        $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>$this->getVar('cat_desc')); 
        $form->addElement(new XoopsFormEditor(_MA_SUPPORT_CATDESC, 'cat_desc',$configs));
       
        //狀態
        $form->addElement(new XoopsFormRadioYN(_MA_SUPPORT_VISIBLE, 'cat_status', $status));
        
        //管理員
        $uids = $member_handler->getUsersByGroup($xoopsModuleConfig['support']);
        $criteria = new CriteriaCompo(new Criteria("uid","(".implode(", ",$uids). ")","in"));
        $members = $member_handler->getUserList($criteria);     
        $support_ids = array();
        if(!$this->isNew()){
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('cat_id', $this->getVar('cat_id')));
            $linkusers = $linkusers_handler->getAll($criteria, array('uid'), false);
            if(!empty($linkusers)){
                foreach($linkusers as $k=>$v){
                    $support_ids[] = $v['uid'];
                }
            }
        }

        $support = new XoopsFormSelect(_MA_SUPPORT_MANGER,'support_ids', $support_ids, 5, true);    
        $support -> addOptionArray($members);
        $form->addElement($support, true);
        
        $form->addElement(new XoopsFormHidden('cat_id', $this->getVar('cat_id')));
        $form->addElement(new XoopsFormHidden('ac', 'insert'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;         
    }
}

class SupportCategoryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'support_category', 'SupportCategory', 'cat_id', 'cat_name');
    }
}

?>