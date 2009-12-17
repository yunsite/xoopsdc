<?php
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class LinksCategory extends XoopsObject 
{
    function __construct() 
    {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_desc', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_order', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_image', XOBJ_DTYPE_TXTBOX);
    }
    
    function catForm($action = false)
    {      
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }  
        include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
        $format = empty($format) ? "e" : $format;
        $title = $this->isNew() ? _AM_LINKS_ADDCAT : _AM_LINKS_UPDATECAT;
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->addElement(new XoopsFormText(_AM_LINKS_CATNAME, 'cat_name', 60, 255, $this->getVar('cat_name', $format)), true);
        //$form->addElement( new XoopsFormTextArea('简介','cat_desc', $this->getVar('cat_desc'), 5, 80));
        $form->addElement(new XoopsFormHidden('cat_id', $this->getVar('cat_id')));
        $form->addElement(new XoopsFormHidden('ac', 'insert'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;
    }
}

class LinksCategoryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, 'links_category', 'LinksCategory', 'cat_id', 'cat_name');
    }
}
?>
