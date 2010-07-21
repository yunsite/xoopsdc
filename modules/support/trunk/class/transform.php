<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SupportTransform extends XoopsObject
{
    function __construct() {
        $this->initVar('tran_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pro_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0);
        $this->initVar('tran_action', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('tran_desc', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('create_time', XOBJ_DTYPE_INT, 0);
        $this->initVar('forword_uid', XOBJ_DTYPE_INT, 0);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);
    }
}

class SupportTransformHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'support_transform', 'SupportTransform', 'tran_id');
    }
}

?>
