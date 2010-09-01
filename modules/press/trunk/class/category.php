<?php
if (false === defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PressCategory extends XoopsObject {
	public function __construct() {
		$this->initVar("cat_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("uid", XOBJ_DTYPE_INT,0);
		$this->initVar("cat_name", XOBJ_DTYPE_TXTBOX, "");
	}
	
}
class PressCategoryHandler extends XoopsPersistableObjectHandler
{
	public function __construct(&$db) {
        parent::__construct($db,"press_category","PressCategory","cat_id","cat_name");
    }
    public function setCatname($cat_id=0,$uid,$catname) {
        if ( empty($uid) || empty($catname) ) return false;
        if ( !empty($cat_id) ) {
            $_obj = $this->get($cat_id);
            if ( empty($_obj) || !is_object($_obj) ) return false;
        } else {
            $criteria = new CriteriaCompo( new Criteria("cat_name",trim($catname)) );
            $criteria->add( new Criteria("uid",$uid));
            if ( $this->getCount($criteria) ) return false;
            $_obj = $this->get();
            $_obj->setVar("uid",$uid);
            $_obj->setVar("topic_date",time());
        }
        $_obj->setVar("cat_name",$catname);
        if ( $cat_id = $this->insert($_obj) ) {
            return $cat_id;
        }
        return false;
    }
    public function getCategoryList($uid=0) {
    	$criteria = new CriteriaCompo();
    	if ( !empty($uid) ) {
    	    $criteria->add(new Criteria("uid",$uid));
    	}
    	$criteria->setOrder("ASC");
		$criteria->setSort("cat_id");
    	$categories = $this->getAll($criteria);
    	$catarr = array();
    	if ( $this->getCount($criteria) ) {
    		unset($criteria);
	    	$topic_handler = xoops_getmodulehandler("topics","press");
    	    $criteria = new CriteriaCompo(new Criteria("cat_id","(".implode(",",array_keys($categories)).")","in"));
    	    if ( !empty($uid) ) {
        	    $criteria->add(new Criteria("uid",$uid));
        	}
    	    $criteria->setGroupby("cat_id");
    	    $counts = $topic_handler->getCounts($criteria);
    	    foreach ( $categories as $key=>$obj ) {
				$catarr[$key]["cat_name"] =  $obj->getVar("cat_name");
				$catarr[$key]["cat_id"] =  $key;
				if ( !empty($uid) ) {
				    $catarr[$key]["uid"] =  $uid;
				}
				$catarr[$key]["cat_topics"] = isset($counts[$key]) ? $counts[$key] : 0;
			}   
			return $catarr;
    	}
    }
    
    public function getCategorySelect($uid) {
        $rows = self::getCategoryList($uid);
        $ret = array();
        if ( $rows ) {
        	foreach ( $rows as $k=>$v ) {
        		$ret[$v["cat_id"]] = $v["cat_name"];
        	}
        }
        return $ret;
    }
    public function delCategory($cat_id) {
    	$criteria = new CriteriaCompo(new Criteria("cat_id",$cat_id));
    	$topic_handler = xoops_getmodulehandler("topics","press");
    	$criteria2 = new CriteriaCompo(new Criteria("cat_id",$cat_id));
    	
    	if ( $this->getCount($criteria) ) {
    		$this->deleteAll($criteria);
    		$topic_handler->updateAll("cat_id",0, $criteria2);
    		return true;
    	}
    	return false;
    }
}
?>