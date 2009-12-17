<?php
if (false === defined('XOOPS_ROOT_PATH')) {
	exit();
}

class ResourcesResources extends XoopsObject {
	public function __construct() {
		$this->initVar('resources_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('cat_id', XOBJ_DTYPE_INT);
		$this->initVar('uid', XOBJ_DTYPE_INT);
		$this->initVar('resources_pid', XOBJ_DTYPE_INT);
		$this->initVar('resources_subject', XOBJ_DTYPE_TXTBOX);
		$this->initVar('resources_content', XOBJ_DTYPE_TXTAREA);
		$this->initVar('resources_attachment', XOBJ_DTYPE_INT); // 0 没有 0 < 有附件，数为附件个数
		$this->initVar('resources_dateline', XOBJ_DTYPE_INT); 
		$this->initVar('resources_state', XOBJ_DTYPE_INT); // 0 待审核 1 审核通过
		$this->initVar('resources_comments', XOBJ_DTYPE_INT); //评论总数
	}
	
}
class ResourcesResourcesHandler extends XoopsPersistableObjectHandler
{
	public function __construct(&$db) {
        parent::__construct($db,'resources','ResourcesResources','resources_id');
    } 
    
    public function setResources() {
    	global $xoopsUser, $xoopsModuleConfig;
    	$myts =& MyTextSanitizer::getInstance();
    	if ( !empty($_POST["resources_id"]) ) {
    	    $resources_obj = $this->get(intval($_POST["resources_id"]));
    	} else {
    	   $resources_obj = $this->get();
    	}
    	foreach ($_POST as $key => $val) {
			if (!isset($resources_obj->vars[$key]) || $myts->stripSlashesGPC($val) == $resources_obj->getVar($key)) continue;
			$resources_obj->setVar($key, $val);
		}
		$resources_obj->setVar("uid",$xoopsUser->getVar("uid"));
		if ( $xoopsUser->isadmin() ) {
			$state = 1 ;
		} else {
		    if ( !$xoopsModuleConfig["useruploadscheck"] ) {
			    $state = 1;
		    } else {
		        $state = 0;
		    }
		}
		$resources_obj->setVar("resources_state",$state);
		$resources_obj->setVar("resources_dateline",time());
    	if ( $resources_id = $this->insert($resources_obj) ) {
    		return $resources_id;
    	}
		return false;    	
    }
    
    public function getResourcesList($user=null, $cat_id=0, $start=0, $perPage=10, $order="DESC", $sort="resources_id", $state=1 ) {
        $criteria = new CriteriaCompo();
        if ( null != $user && is_object($user) ) {
            $criteria->add(new Criteria("uid",$user->getVar("uid")));
        }
        
        if ( !empty($cat_id) ) {
            $criteria->add(new Criteria("cat_id",$cat_id));
        }
        
        if ( !empty($state) ) {
            $criteria->add(new Criteria("resources_state",$state));
        }
        
        $criteria->setStart($start);
		$criteria->setLimit($perPage);
		$criteria->setOrder($order);
		$criteria->setSort($sort);

		$resources_objs = $this->getAll($criteria) ;
		$resources_list = array();
		$category_handler = xoops_getmodulehandler("category");
        $categories = $category_handler->getCategories();
        	foreach ( $resources_objs as $k=>$resources_obj ) {
		        $resources_list[$k] = $resources_obj->getValues();
		        $resources_list[$k]["name"] = $resources_obj->getVar("uid") ? $resources_obj->getVar("uid") : "";
		        $resources_list[$k]["cat_name"] = $resources_obj->getVar("cat_id") ? $categories[$resources_obj->getVar("cat_id")] : "";
		        $resources_list[$k]["resources_dateline"] = formatTimestamp($resources_obj->getVar("resources_dateline"));
		    }
		return $resources_list;
    }
}