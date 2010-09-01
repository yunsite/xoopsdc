<?php
if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}

$time = isset($_GET["time"]) ? intval($_GET["time"]) : 0;

$start = isset($_GET["start"]) ? intval($_GET["start"]) : 0 ;
$perPage = $userConfig["page_num"] ;
$extar = "op=list";

$criteria = new CriteriaCompo(new Criteria("topic_pid","0"));
$criteria->add(new Criteria("uid",$xoopsUser->getVar("uid")));
if ( !empty($time) ) {
	$extar .= "&amp;time=".$time;
	$criteria ->add(new Criteria("topic_date",$time,">="));
	$timen = mktime(0, 0, 0, date("m",$time), intval(date("d",$time)) + 30, date("Y",$time));
	$criteria ->add(new Criteria("topic_date",$timen,"<"));
}
if ( !empty($cat_id) ) {
	$extar .= "&amp;cat_id=".$cat_id;
	$criteria ->add(new Criteria("cat_id",$cat_id));
}
$criteria->setStart($start);
$criteria->setLimit($perPage);
$criteria->setOrder("DESC");
$criteria->setSort("lastdate");
if($topic_handler->getCount($criteria) > $perPage){	
	$nav = new XoopsPageNav($topic_handler->getCount($criteria), $perPage, $start, "start",@$extar);
	$xoopsTpl->assign("pagenav", $nav->renderNav(4));
}
// get topic ids
$topic_list = $topic_handler->getAll($criteria,null,false);

if ( $topic_list )	{
	foreach ( $topic_list as $k=>$v ) {
		$_uids[] = $v["uid"];
		$_cat_ids[] = $v["cat_id"];
	}
	$_uids = $_uids ? array_unique($_uids) : 0;
	$_cat_ids = $_cat_ids ? array_unique($_cat_ids) : 0;
	
	if ( $_uids ) {
		$member_handler = xoops_gethandler("member");
		$criteria = new CriteriaCompo(new Criteria("uid",$_uids,"in"));
		$users = $member_handler->getUsers($criteria,"uid");
		foreach ( array_keys($users) as $key ) {
			$user[$key]["name"] =  $users[$key]->getVar("name") ?  $users[$key]->getVar("name") :  $users[$key]->getVar("uname");
			$user[$key]["avatar"] =  $users[$key]->getVar("user_avatar") ;
		}
	}
	
	if ( $_cat_ids ) {
		$category_handler = xoops_getmodulehandler("category","notes");
		$criteria = new CriteriaCompo(new Criteria("cat_id",$_cat_ids,"in"));
		$categoies = $category_handler->getAll($criteria,null,false);
		foreach ( $categoies as $key=>$val ) {
			$category[$key] = $val["cat_name"];
		}
	}
	
	foreach ( $topic_list as $k=>$v ) {
		$topics[$k] = $v;
		$topics[$k]["name"] = $user[$v["uid"]]["name"];
		$topics[$k]["user_avatar"] = $user[$v["uid"]]["avatar"];
		$topics[$k]["cat_name"] = !empty($v["cat_id"]) ? $category[$v["cat_id"]] : _PRESS_NOCATEGORY;
		$topics[$k]["content"] = xoops_substr($topic_handler->getnoHtml($v["content"]),0,320);
		$topics[$k]["topic_date"] = formatTimestamp($v["topic_date"]);
		
	}
	
}
$xoopsTpl->assign("topic_datas",$topics);

$criteria = new CriteriaCompo(new Criteria("topic_pid","0"));
$criteria ->add(new Criteria("topic_date","0",">"));
$criteria ->add(new Criteria("uid",$xoopsUser->getVar("uid")));
$criteria->setOrder("ASC");
$criteria->setSort("topic_date");
$criteria->setLimit(1);
$topic_date = $topic_handler->getOne($criteria);
if ( is_object($topic_date) ) {
	$topic_dates = $topic_handler->getArrTime($topic_date->getVar("topic_date"),$xoopsUser->getVar("uid"));	
}
$xoopsTpl->append("topic_dates",$topic_dates);

?>