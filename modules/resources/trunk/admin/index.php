<?php
include "header.php";
loadModuleAdminMenu(0);

$resources_handler = xoops_getmodulehandler("resources");
$category_handler = xoops_getmodulehandler("category");

$criteria = new CriteriaCompo();
$criteria->add(new Criteria("resources_pid","0"));
$criteria->setOrder("DESC");
$criteria->setSort("resources_id");
$resources_list = $resources_handler->getAll($criteria,null,false);
$categories = $category_handler->getCategories();
unset($criteria);
foreach ( $resources_list as $key=>$val ) {
	$repositories[$key] = $val;
	$repositories[$key]["name"] = !empty($repositories[$key]["uid"]) ? $repositories[$key]["uid"] : "";
	$repositories[$key]["cat_name"] = !empty($repositories[$key]["cat_id"]) ? $categories[$repositories[$key]["cat_id"]] : "";
	$repositories[$key]["resources_dateline"] = formatTimestamp($val["resources_dateline"]);
}

$xoopsTpl->assign("repositories",$repositories);
$xoopsTpl->display("db:resources_cp_index.html");

include "footer.php";
?>