<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class PortfolioCs extends XoopsObject
{
    function __construct() {
        $this->initVar('cs_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('service_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('case_id', XOBJ_DTYPE_INT, 0, false);
    }
}

class PortfolioCsHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'portfolio_cs', 'PortfolioCs', 'cs_id');
    }
    
    function getCaseIds($service_ids = null) 
    {
        if($service_ids == null) return false;

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("service_id","(".implode(", ",$service_ids). ")","in"), 'AND');
        $cs = parent::getAll($criteria, null, false); 
        
        return $cs;
    }
    
    function getServerIds($case_ids = null) 
    {
        if($case_ids == null) return false;
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("case_id","(".implode(", ",$case_ids). ")","in"), 'AND');
        $cs = parent::getAll($criteria, null, false); 

        return $cs;
    }
}

?>
