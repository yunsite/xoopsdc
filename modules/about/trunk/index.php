<?php
include_once 'header.php';
include_once "include/functions.render.php";

$page_id = isset($_REQUEST['page_id']) ? $_REQUEST['page_id'] : '';
$page_handler =& xoops_getmodulehandler('page', 'about');

$menu_criteria = new CriteriaCompo();
$menu_criteria->add(new Criteria('page_status', 1), 'AND');
$menu_criteria->add(new Criteria('page_menu_status', 1));
$menu_criteria->setSort('page_order');
$menu_criteria->setOrder('ASC');
$fields = array(
    "page_id", 
    "page_menu_title", 
    "page_blank",
    "page_menu_status",
    "page_status"
);
$page_menu = $page_handler->getAll($menu_criteria, $fields, false);

$criteria = new CriteriaCompo();
if(!empty($page_id)){
   $criteria->add(new Criteria('page_id',$page_id));
}else{
   $criteria->add(new Criteria('page_index', 1));
}
$criteria->add(new Criteria('page_status', 1));

$criteria->setSort('page_order');
$criteria->setOrder('ASC');
$page = current($page_handler->getObjects($criteria, null, false, false));	

$xoopsOption['xoops_pagetitle'] = $page['page_title'];
$xoopsOption['template_main'] = about_getTemplate("page",  $page['page_tpl']);
include XOOPS_ROOT_PATH.'/header.php';
if(!empty($page)){
    $myts = MyTextSanitizer::getInstance();
    $page['page_text'] = $myts->undoHtmlSpecialChars($page['page_text']);
    if (($page['page_type'] == 2)) {
        header("location: ".$page['page_text']);    
    }
    $xoTheme->addMeta('meta','description',$page['page_menu_title']);
    $xoopsTpl->assign('pagemenu',  $page_menu);
    $xoopsTpl->assign('page',  $page);
    if ($page_id) {
        $xoBreadcrumbs[] = array('title' => $page['page_menu_title']);
    }

}

include_once 'footer.php';
?>
