<?php

include 'header.php';

$handler =& xoops_getmodulehandler('article');

$xoopsOption['template_main'] = "ilog_index.html";
include XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . "/class/pagenav.php";

$count = $handler->getCount();
$items_perpage=$xoopsModuleConfig['perpage'];
$start = !isset($_GET['start']) ? 0 : intval($_GET['start']);
$pageNav = new XoopsPageNav($count,$items_perpage, $start, "start");
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status',1));
$criteria->setLimit($items_perpage);
$criteria->setStart($start);
$criteria->setSort('time_publish');
$criteria->setOrder('DESC');
$field = array('id','uid','uname','title','keywords','summary','time_publish','status','counter','comments','dohtml');
$article = $handler->getAll($criteria,$field, false, true);

global $xoopsUser;

foreach ($article as $k=>$v){
	$article[$k]["time_publish"] = formatTimestamp($article[$k]["time_publish"]);
}
     

$xoopsTpl->assign('messages',$article);
$xoopsTpl->assign('page', $pageNav->renderNav());

include "footer.php";
?>
