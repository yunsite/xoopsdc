<?php
if (false === defined('XOOPS_ROOT_PATH')) {
	exit();
}

class resourcesCategory extends XoopsObject {
	public function __construct() {
		$this->initVar("cat_id", XOBJ_DTYPE_INT, null, false); // 分类ID
		$this->initVar("cat_name", XOBJ_DTYPE_TXTBOX, null, true); // 分类名称
		$this->initVar("cat_description", XOBJ_DTYPE_TXTAREA, null, true); // 分类描述
		$this->initVar("cat_createdate", XOBJ_DTYPE_INT); // 创建时间
	}
	
}
class resourcesCategoryHandler extends XoopsPersistableObjectHandler
{
	public function __construct(&$db) {
        parent::__construct($db,"resources_category","resourcesCategory","cat_id");
    }
    
    public function delCategory($cat_id) {
    	$criteria = new CriteriaCompo(new Criteria("cat_id",$cat_id));    	
    	$resources_handler = xoops_getmodulehandler("resources");
    	if ( $this->deleteAll($criteria) ) {
    		unset($criteria);
    		$criteria = new CriteriaCompo(new Criteria("cat_id",$cat_id));
    		$resources_handler->updateAll("cat_id",0,$criteria);
    		return true;
    	}
    	return false;
    }
    
    public function getCategories() {
        $criteria = new CriteriaCompo();
        $criteria->setOrder("DESC");
        $criteria->setSort("cat_id");
        $categories = $this->getAll(null,null,false);
        $rows = array();
        foreach ( $categories as $k=>$v ) {
            $rows[$k] = $v["cat_name"];
        }
        return $rows;
    }
    
}
?>