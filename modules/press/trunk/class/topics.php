<?php
if (false === defined("XOOPS_ROOT_PATH")) {
	exit();
}

class PressTopics extends XoopsObject {
	public function __construct() {
		$this->initVar("topic_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("cat_id", XOBJ_DTYPE_INT,0);
		$this->initVar("uid", XOBJ_DTYPE_INT,0);
		$this->initVar("subject", XOBJ_DTYPE_TXTBOX,"");
		$this->initVar("content", XOBJ_DTYPE_TXTAREA, "");
		$this->initVar("topic_date", XOBJ_DTYPE_INT);
		$this->initVar("state", XOBJ_DTYPE_INT,0);
	}
	
	
}
class PressTopicsHandler extends XoopsPersistableObjectHandler
{
	public function __construct(&$db) {
        parent::__construct($db,"press_topics","PressTopics","topic_id","subject");
    }
    public function setTopics($topic_id=0,$cat_id,$uid,$subject,$content,$topic_date){
    	if ( empty($cat_id) || empty($uid) || empty($subject) || empty($content) ) return false;
        if ( !empty($topic_id) ) {
            $_obj = $this->get($topic_id);
            if ( empty($_obj) || !is_object($_obj) ) return false;
        } else {
            $_obj = $this->get();
            $_obj->setVar("uid",$uid);
            $_obj->setVar("topic_date",$topic_date);
        }
        $_obj->setVar("cat_id",$cat_id);
        $_obj->setVar("subject",$subject);
        $_obj->setVar("content",$content);
        $_obj->setVar("topic_date",$topic_date);
        if ( $topic_id = $this->insert($_obj) ) {
            return $topic_id;
        }
        return false;
    }
    
    public function getArrTime($dtime,$uid=0) {
    	$y = intval(date("Y",time())) - intval(date("Y",$dtime));
		$m = intval(date("m",time())) - intval(date("m",$dtime));
		if ( $m >= 0 ) {
			$t = $y * 12 + $m ;
		} else {
			$t = ($y - 1) * 12 + (12 + $m) ;
		}
		if ( $t > 0 ) {
			for ($i=$t; $i>=0; $i--) {
				$arrtime[$i]["date"] = date("Y-m",mktime(0, 0, 0, intval(date("m",$dtime)) + $i, 1, date("Y",$dtime)));
				$arrtime[$i]["time"] = mktime(0, 0, 0, intval(date("m",$dtime)) + $i, 1, date("Y",$dtime));
				$arrtime[$i]["time_x"] = mktime(0, 0, 0, intval(date("m",$dtime)) + $i + 1 , 1, date("Y",$dtime));
				$arrtime[$i]["uid"] = $uid;
			}
		} else {
			$arrtime[0]["date"] = date("Y-m",mktime(0, 0, 0, intval(date("m",$dtime)), 1, date("Y",$dtime)));
			$arrtime[0]["time"] = mktime(0, 0, 0, intval(date("m",$dtime)), 1, date("Y",$dtime));
			$arrtime[0]["time_x"] = mktime(0, 0, 0, intval(date("m",$dtime))+1, 1, date("Y",$dtime));
			$arrtime[0]["uid"] = $uid;
		}
		return self::getTimeCount($arrtime,$uid);
    }
    
    public function getTimeCount($time_rows,$uid=0){
    	$criteria = new CriteriaCompo(new Criteria("topic_pid","0"));
    	if ( $uid ) {
    		$criteria ->add(new Criteria("uid",$uid));
    	} 
    	$topic_time_objs = $this->getAll($criteria,array("0"=>"topic_date"));
    	foreach (array_keys($topic_time_objs) as $key ) {
    		$topic_time_rows[$key]["topic_date"] = $topic_time_objs[$key]->getVar("topic_date");
    	}
    	
    	foreach ( array_keys($time_rows) as $i ) {
    		$time_rows[$i]["count"] = 0;
    		foreach ( array_keys($topic_time_rows) as $n ) {
    			if ( $time_rows[$i]["time"] <= $topic_time_rows[$n]["topic_date"] && $time_rows[$i]["time_x"] > $topic_time_rows[$n]["topic_date"]) {
    				$time_rows[$i]["count"]++;
    			}
    		}
    	}
		return $time_rows;
    }

    public function getUserPostFirstTopicTime($uid=0) {
        $ret = 0;
        $criteria = new CriteriaCompo(new Criteria("topic_pid","0"));
        $criteria ->add(new Criteria("topic_date","0",">"));
        if ( !empty($uid) ) {
            $criteria ->add(new Criteria("uid",$uid));
        }
        $criteria->setOrder("ASC");
        $criteria->setSort("topic_date");
        $criteria->setLimit(1);
        $topics = $this->getAll($criteria,array("topic_date"),false);
        $topics = array_values($topics);
        $ret = $topics["0"]["topic_date"];
        return $ret;
    }
    
    public function getnoHtml($str) {
    	return preg_replace( "@<(.*?)>@is", "", $str );
    }
    
    public function getUserTopics($user,$num=10) {
    	$myts =& MyTextSanitizer::getInstance();
    	$criteria = new CriteriaCompo(new Criteria("topic_pid","0"));
    	$criteria->add(new Criteria("uid",$user->getVar("uid")));
		$criteria->setLimit(intval($num));
		$criteria->setOrder("DESC");
		$topics_objs = $this->getAll($criteria,array("subject","content","topic_date"));
		$rows = array();
		if ( $topics_objs ) {
			foreach ( $topics_objs as $key=>$obj ) {
			    $rows[$key] = $obj->getValues();
				$rows[$key]["subject"] = $topics_objs[$key]->getVar("subject");
				$rows[$key]["topic_date"] = formatTimestamp($topics_objs[$key]->getVar("topic_date"),TIMEFORMAT);
				$rows[$key]["content"] = xoops_substr(self::getnoHtml($topics_objs[$key]->getVar("content","n")),0,120);
			}
			return $rows;
		}
		return false;
    }
    
    public function getUserNotesCounts($uid) {
        $criteria = new CriteriaCompo(new Criteria("topic_pid","0"));
        $criteria->add(new Criteria("uid",$uid));
        return $this->getCount($criteria);
    }
 }
?>
    