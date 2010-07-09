<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class ResourcesLink extends XoopsObject
{
    function __construct() {
        $this->initVar('link_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('res_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('mod_name', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('id', XOBJ_DTYPE_INT, 0);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0);
    }
}

class ResourcesLinkHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'res_link', 'ResourcesLink', 'link_id');
    }
}

?>
