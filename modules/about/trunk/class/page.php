<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class AboutPage extends XoopsObject
{
    function __construct() {
        $this->initVar('page_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_title', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('page_menu_title', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('page_text', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('page_author', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('page_pushtime', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_blank', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_menu_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_type', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_order', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_url', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('page_index', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_tpl', XOBJ_DTYPE_TXTBOX,"");
    }
}


class AboutPageHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'about_page', 'AboutPage', 'page_id', 'page_title');
    }
}

?>
