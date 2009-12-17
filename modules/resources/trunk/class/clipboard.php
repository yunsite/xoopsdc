<?php
if (false === defined('XOOPS_ROOT_PATH')) {
	exit();
}

class ResourcesClipboard extends XoopsObject {
	public function __construct() {
		$this->initVar("clipboard_id", XOBJ_DTYPE_INT, null, false); // 分类ID
		$this->initVar("uid", XOBJ_DTYPE_INT); 
		$this->initVar("resources_id", XOBJ_DTYPE_INT);
		$this->initVar("clipboard_dateline", XOBJ_DTYPE_INT); // 创建时间
	}
	
}
class ResourcesClipboardHandler extends XoopsPersistableObjectHandler
{
	public function __construct(&$db) {
        parent::__construct($db,"resources_clipboard","ResourcesClipboard","clipboard_id");
    }
    
    public function getClipboardList($user=null, $start=0, $perPage=10, $order="DESC", $sort="clipboard_id") {
        $criteria = new CriteriaCompo();
        if ( null != $user && is_object($user) ) {
            $criteria->add(new Criteria("uid",$user->getVar("uid")));
        }
        
        $criteria->setStart($start);
		$criteria->setLimit($perPage);
		$criteria->setOrder($order);
		$criteria->setSort($sort);
		
		$clipboard_objs = $this->getAll($criteria) ;
		
		unset($criteria);
		
		$resources_list = $resources = array();
		if ( $clipboard_objs ) {
		    $_resources_ids = $_clipboard_ids = array();
		    foreach ( $clipboard_objs as $k=>$clipboard_obj ) {
		        $_resources_ids[] = $clipboard_obj->getVar("resources_id");
		        $_clipboard_ids[$clipboard_obj->getVar("resources_id")] = $k;
		    }
		    if ( $_resources_ids ) {
		        $_resources_ids = array_unique($_resources_ids);
		    }
		    $resources_handler = xoops_getmodulehandler("resources");
		    $criteria = new CriteriaCompo(new Criteria("resources_id",$_resources_ids,"in"));
		    $resources_list = $resources_handler->getAll($criteria,array("resources_id","resources_subject"),false);
		    
		    if ( $resources_list ) {
		        foreach ( $resources_list as $key=>$val ) {
		            $resources[$key] = $val;
		            $resources[$key]["clipboard_id"] = !empty($_clipboard_ids[$key]) ? $_clipboard_ids[$key] : 0;
		        }
		    }
		    
		    if ( $resources ) {
        		foreach ($resources as $key => $row) {
    			    $volume[$key]  = $row['friend_id'];
    			}
    			$resources[] = array_multisort($volume, SORT_DESC, $resources);
        	}
        	$count = count($resources)-1;
        	unset($resources[$count]);
		}
		return $resources;
    }
}
?>