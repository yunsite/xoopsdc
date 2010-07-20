<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SupportAnnex extends XoopsObject
{
    function __construct() {
        $this->initVar('annex_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pro_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('tran_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('annex_file', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('annex_title', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('annex_type', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('uid', XOBJ_DTYPE_INT, 0);
    }
}

class SupportAnnexHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'support_annex', 'SupportAnnex', 'annex_id', 'annex_file');
    }
}

?>
