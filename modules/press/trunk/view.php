<?php
include "header.php";

$topic_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0 ;

$start = isset($_GET["start"]) ? intval($_GET["start"]) : 0 ;
if (empty($topic_id)) {
echo " <script>history.go(-1);</script>";
}
$perPage = $xoopsModuleConfig["presspagemaxnum"];
$extar = "";
$topic_handler = xoops_getmodulehandler("topics");
$category_handler = xoops_getmodulehandler("category");
$criteria = new CriteriaCompo(new Criteria("topic_id",$topic_id));
$topic_obj = $topic_handler->get($topic_id);
if ( !$topic_obj ) {
	redirect_header("index.php",5,_PRESS_NO_TOPIC);
}


$topics = $topic_obj->getValues(null,"n");
if ( $topics["cat_id"] ) {  
    $cat_obj = $category_handler->get($topics["cat_id"]);
}

$xoopsOption['xoops_pagetitle'] = $topic_obj->getVar("subject") . " - " . $cat_obj->getVar("cat_name") . " - " .$xoopsModule->getVar("name") ;
$xoopsOption["template_main"] = "press_view.html";
include XOOPS_ROOT_PATH."/header.php";

$topics["cat_name"] = $topics["cat_id"] ? $cat_obj->getVar("cat_name") : _PRESS_NOCATEGORY;
$topics["topic_date"] = formatTimestamp($topics["topic_date"]);

$xoopsTpl->assign("date",$topics);
$xoopsTpl->assign("topics",$topics);

$crumb = $topics["cat_name"]." &raquo; ";
$crumb .= '<a href="./">'.$xoopsModule->getVar("name").'</a> &raquo; ';
$crumb .= '<a href="'.XOOPS_URL.'">'._MA_PRESS_GO_INDEX.'</a>';

$xoopsTpl->assign("crumb",$crumb);
$xoTheme->addMeta('meta','description',$topic_obj->getVar("subject"));
//comment
include XOOPS_ROOT_PATH.'/include/comment_view.php';

include "footer.php";
?>