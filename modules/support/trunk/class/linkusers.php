<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SupportLinkusers extends XoopsObject
{
    function __construct() {
        $this->initVar('link_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0);
    }
}

class SupportLinkusersHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'support_cat_users_link', 'SupportLinkusers', 'link_id');
    }
    
    function getUids($cat_ids = null) 
    {
        if($cat_ids == null) return false;

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("cat_id","(".implode(", ",$cat_ids). ")","in"), 'AND');
        $uids = parent::getAll($criteria, null, false); 
        
        return $uids;
    }
    
    function getCatIds($uids = null) 
    {
        if($uids == null) return false;
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("uid","(".implode(", ",$uids). ")","in"), 'AND');
        $cats = parent::getAll($criteria, null, false); 

        return $cats;
    }
}

?>
