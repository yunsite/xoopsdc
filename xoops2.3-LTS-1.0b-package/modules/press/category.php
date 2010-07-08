<?php
include "header.php";
$cat_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$start = isset($_GET["start"]) ? intval($_GET["start"]) : 0 ;

$topic_handler = xoops_getmodulehandler("topics");
$category_handler = xoops_getmodulehandler("category");



$criteria = new CriteriaCompo(new Criteria("topic_pid","0"));
$extar = "";

if ( !empty($cat_id) ) {
	$extar .= "&amp;id=".$cat_id;
	$criteria ->add(new Criteria("cat_id",$cat_id));
}
$perpage = $xoopsModuleConfig["presspagemaxnum"];
$criteria->setStart($start);
$criteria->setLimit($perpage);
$criteria->setSort("topic_date");
$criteria->setOrder("DESC");
$counts = $topic_handler->getCount($criteria);

// get topic ids
$topic_list = $topic_handler->getAll($criteria,null,false);
$categories = $category_handler->getList();


$xoopsOption["xoops_pagetitle"] = $categories[$cat_id] . " - " . $xoopsModule->getVar("name");
$xoopsOption["template_main"] = "press_category.html";
include XOOPS_ROOT_PATH."/header.php";
if( $counts > $perpage){
include XOOPS_ROOT_PATH."/class/pagenav.php";
	$nav = new XoopsPageNav( $counts, $perpage, $start, "start",$extar);
	$xoopsTpl->assign("pagenav", $nav->renderNav(4));
}

if ( $topic_list )	{
	foreach ( $topic_list as $k=>$v ) {
		$topics[$k] = $v;
		$topics[$k]["content"] = xoops_substr($topic_handler->getnoHtml($v["content"]),0,$xoopsModuleConfig["presssummarymax"]);
		$topics[$k]["topic_date"] = formatTimestamp($v["topic_date"],'Y-m-d');
		$topics[$k]["category"] = $categories[$v["cat_id"]];
	}
	$xoopsTpl->assign("topics",$topics);
}

$xoopsTpl->assign('category',$categories[$cat_id]);

$crumb = "";
if ( !empty($cat_id) ) {
$crumb .= $categories[$cat_id]." &raquo; ";
}
$crumb .= '<a href="./">'.$xoopsModule->getVar("name").'</a> &raquo; ';
$crumb .= '<a href="'.XOOPS_URL.'">'._MA_PRESS_GO_INDEX.'</a>';

$xoopsTpl->assign("crumb",$crumb);
include "footer.php";
?>