<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class ResourcesRate extends XoopsObject
{
    function __construct() {
        $this->initVar('rate_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('res_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('rate_rating', XOBJ_DTYPE_INT, null, false);
        $this->initVar('rate_time', XOBJ_DTYPE_INT, null, false);
        return;
    }
}
class ResourcesRateHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, 'res_rate', 'ResourcesRate', 'rate_id');
    }
}


?>
