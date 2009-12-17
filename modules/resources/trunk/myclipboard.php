<?php
include "header.php";

$xoopsOption['xoops_pagetitle'] = $xoopsModuleConfig["moduletitle"];

if ( !is_object($xoopsUser) ) {
    redirect_header("index.php", 5, _NOPERM);
}

$start = isset($_GET["start"]) ? intval($_GET["start"]) : 0;
$perPage = $xoopsModuleConfig["pagenum"];
$extar = "";

$xoopsOption["template_main"] = "resources_myclipboard.html";
include_once XOOPS_ROOT_PATH.'/header.php';		

$clipboard_handler = xoops_getmodulehandler("clipboard");

$criteria = new CriteriaCompo();
$criteria->add(new Criteria("uid",$xoopsUser->getVar("uid")));
$criteria->setStart($start);
$criteria->setLimit($perPage);
$counts = $clipboard_handler->getCount($criteria);

if( $counts > $perPage){	
	$nav = new XoopsPageNav($counts, $perPage, $start, "start",@$extar);
	$xoopsTpl->assign("pagenav", $nav->renderNav(4));
}

$clipboard_list = $clipboard_handler->getClipboardList($xoopsUser, $start, $perPage);

$xoopsTpl->assign("clipboard_list",$clipboard_list);
include 'footer.php';
?>