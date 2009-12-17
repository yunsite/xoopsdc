<?php
include "header.php";

$xoopsOption['xoops_pagetitle'] = $xoopsModuleConfig["moduletitle"];

$xoopsOption["template_main"] = "resources_index.html";
include_once XOOPS_ROOT_PATH.'/header.php';

$cat_id = isset($_GET["cat_id"]) ? intval($_GET["cat_id"]) : 0;
$start = isset($_GET["start"]) ? intval($_GET["start"]) : 0;
$perPage = $xoopsModuleConfig["pagenum"];

$resources_handler = xoops_getmodulehandler("resources");

$criteria = new CriteriaCompo();

if ( !empty($cat_id) ) {
    $criteria->add(new Criteria("cat_id",$cat_id));
}

$criteria->add(new Criteria("resources_state","1"));
$criteria->setStart($start);
$criteria->setLimit($perPage);
$counts = $resources_handler->getCount($criteria);

if( $counts > $perPage){
  include XOOPS_ROOT_PATH . "/class/pagenav.php";
	$nav = new XoopsPageNav($counts, $perPage, $start, "start",@$extar);
	$xoopsTpl->assign("pagenav", $nav->renderNav(4));
}

$resources_list = $resources_handler->getResourcesList(null,$cat_id, $start, $perPage);

$xoopsTpl->assign("resources_list",$resources_list);

include 'footer.php';
?>