<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(0, "");
include XOOPS_ROOT_PATH."/class/pagenav.php";
$topic_handler = xoops_getmodulehandler("topics");
$category_handler = xoops_getmodulehandler("category");

$cat_id = isset($_GET["cat_id"]) ? intval($_GET["cat_id"]) : 0;

$start = isset($_GET["start"]) ? intval($_GET["start"]) : 0 ;
$perPage = 20 ;
$extar = "op=list";

$criteria = new CriteriaCompo(new Criteria("topic_pid","0"));

if ( !empty($cat_id) ) {
	$extar .= "&amp;cat_id=".$cat_id;
	$criteria ->add(new Criteria("cat_id",$cat_id));
}
$criteria->setStart($start);
$criteria->setLimit($perPage);
$criteria->setOrder("DESC");
$criteria->setSort("topic_date");
if($topic_handler->getCount($criteria) > $perPage){
	$nav = new XoopsPageNav($topic_handler->getCount($criteria), $perPage, $start, "start",@$extar);
	$xoopsTpl->assign("pagenav", $nav->renderNav(4));
}
// get topic ids
$topic_list = $topic_handler->getAll($criteria,array("cat_id","uid","topic_pid","subject","topic_date","post","view"),false);

if ( $topic_list )	{
	foreach ( $topic_list as $k=>$v ) {
		$_cat_ids[] = $v["cat_id"];
	}

	$_cat_ids = $_cat_ids ? array_unique($_cat_ids) : 0;

	if ( $_cat_ids ) {
		$categoies = $category_handler->getCategorySelect(null);
	}

	foreach ( $topic_list as $k=>$v ) {
		$topic_list[$k] = $v;
		$topic_list[$k]["cat_name"] = !empty($v["cat_id"]) ? $categoies[$v["cat_id"]] : _PRESS_NOCATEGORY;
		$topic_list[$k]["topic_date"] = formatTimestamp($v["topic_date"]);
	}

}
$xoopsTpl->assign("topic_datas",$topic_list);

$template_main = "press_cp_index.html";

include 'footer.php';
?>