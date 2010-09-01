<?php
include "header.php";
$cat_id = isset($_GET["cat_id"]) ? intval($_GET["cat_id"]) : 0;
$start = isset($_GET["start"]) ? intval($_GET["start"]) : 0 ;

$topic_handler = xoops_getmodulehandler("topics");
$category_handler = xoops_getmodulehandler("category");


$xoopsOption["xoops_pagetitle"] = $xoopsModule->getVar("name");
$xoopsOption["template_main"] = "press_index.html";
include XOOPS_ROOT_PATH."/header.php";

$perpage = $xoopsModuleConfig["presspagemaxnum"];

$criteria = new CriteriaCompo();
$criteria->setSort('cat_id');
$criteria->setOrder('ASC');
$categories = $category_handler->getList($criteria);

if ( $categories )	{
foreach ($categories as $k=>$v){
	$criteria = new Criteria('cat_id',$k);
	$criteria->setLimit(10);
	$topic_list[$k] = $topic_handler->getAll($criteria,null,false);
	unset($criteria);
}



if ( $topic_list )	{
	foreach ( $topic_list as $k=>$v ) {
		$topics[$k]["category"] = $categories[$k];
		foreach ($v as $key=>$value){
			$topics[$k]["contents"][$key] = $value;
			$topics[$k]["contents"][$key]['content'] = xoops_substr($topic_handler->getnoHtml($value["content"]),0,$xoopsModuleConfig["presssummarymax"]);
			$topics[$k]["contents"][$key]["topic_date"] = formatTimestamp($value["topic_date"],'Y-m-d');
		}

	}
	$xoopsTpl->assign("topics",$topics);
}
}
$xoTheme->addMeta('meta','description',$xoopsModule->name());

$crumb = $xoopsModule->getVar("name")." &raquo; ";
$crumb .= '<a href="'.XOOPS_URL.'">'._MA_PRESS_GO_INDEX.'</a>';
$xoopsTpl->assign("crumb",$crumb);
include "footer.php";
?>
