<?php

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class ResourcesRescounter extends XoopsObject
{
    function __construct() 
    {
        $this->initVar('counter_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('res_id',  XOBJ_DTYPE_INT, 0);
        $this->initVar('uid',  XOBJ_DTYPE_INT, 0);
        $this->initVar('ip', XOBJ_DTYPE_INT,0);
        $this->initVar('counter_time',  XOBJ_DTYPE_INT);

    }
    
    function CatalogItemcounter()
    {
        $this->__construct();
    }
   
}

class ResourcesRescounterHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, "res_counter", "ResourcesRescounter", "counter_id");
    }
}
?>