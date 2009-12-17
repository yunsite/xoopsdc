<?php
include 'header.php';

$id = isset($_REQUEST['id']) ?  intval($_REQUEST['id']): 0;
$handler =& xoops_getmodulehandler('article');
$obj = $handler->get($id);
global $xoopsUser;

$obj->setVar('time_create', formatTimestamp($obj->getVar("time_create")));
$xoopsOption['xoops_pagetitle'] = $obj->getVar("title") . ' - ' . $xoopsModule->getVar("name");
$xoopsOption['template_main'] = "ilog_view.html";
include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('message',$obj->getValues());

include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php";
$xoopsTpl->assign('tagbar', tagBar($id, $catid = 0));

$xoTheme->addMeta('meta','keywords',$obj->getVar("keywords")) ;
$plainMeta = preg_replace("/(\015\012)|(\015)|(\012)/", "", strip_tags($obj->getVar("summary")));
$xoTheme->addMeta('meta','description',$plainMeta) ;
//xoops_load('syntaxhighlighter', 'framework');
//XoopsSyntaxhighlighter::getInstance(array('bash','css','jsript','php','sql','vb','xml'));
include 'footer.php';
?>