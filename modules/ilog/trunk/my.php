<?php

include 'header.php';
global $xoopsUser;
$ilogUid = !isset($_GET['uid']) ? 0 : intval($_GET['uid']);
$handler =& xoops_getmodulehandler('article');

$xoopsOption['template_main'] = "ilog_index.html";
include XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . "/class/pagenav.php";

$count = $handler->getCount(new Criteria('uid',$ilogUid));
$items_perpage=$xoopsModuleConfig['perpage'];
$start = !isset($_GET['start']) ? 0 : intval($_GET['start']);
$pageNav = new XoopsPageNav($count,$items_perpage, $start, "start");
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('uid',$ilogUid));
$criteria->setLimit($items_perpage);
$criteria->setStart($start);
$article = $handler->getObjects($criteria, true, false);

foreach ($article as $k=>$v){
	$article[$k]["time_create"] = formatTimestamp($article[$k]["time_create"]);
}

//xoops_result($article);
$xoopsTpl->assign('messages',$article);
$xoopsTpl->assign('page', $pageNav->renderNav());

include "footer.php";
?>
