<?php
include 'header.php';

xoops_cp_header();
loadModuleAdminMenu(1);

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['id']) ? 'edit' : 'list');
$page_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$page_handler =& xoops_getmodulehandler('page', 'about');

switch ($op){
default:
case "list":
	//page order
	if (isset($_POST['page_order'])){
		$page_order = $_POST['page_order'];
	    foreach ($page_order as $page_id=>$order){
	    	$page_obj = $page_handler->get($page_id);
	    	if($page_order[$page_id] != $page_obj->getVar('page_order')){
	    		$page_obj->setVar('page_order',$page_order[$page_id]);
	    		$page_handler->insert($page_obj);
	    	}
	    	unset($page_obj);
	    }
	}
	//set index
	if (isset($_POST['page_index'])){
		$page_obj = $page_handler->get(intval($_POST['page_index']));
		if (intval($_POST['page_index']) != $page_obj->getVar('page_index')){
			$page_obj = $page_handler->get($_POST['page_index']);	    	    		
	    	if( !$page_obj->getVar('page_title') ){
	    		redirect_header('admin.page.php', 3, _AM_ABOUT_PAGE_ORDER_ERROR);
	    	}
			$page_handler->updateAll('page_index', 0, null);
			unset($criteria);
			$page_obj->setVar('page_index',1);
			$page_handler->insert($page_obj);
		}
		unset($page_obj);
	}
	$fields = array(
					"page_id",
					"page_menu_title",
					"page_author",
					"page_pushtime",
					"page_blank",
					"page_menu_status",
					"page_type",
					"page_status",
					"page_order",
					"page_index",
					"page_tpl"
					);

	$criteria = new CriteriaCompo();
	$criteria->setSort('page_order');
	$criteria->setOrder('ASC');
	$pages = $page_handler->getAll($criteria, $fields, false, true);
	$member_handler =& xoops_gethandler('member');
	foreach ($pages as $k=>$v){
		$pages[$k]['page_pushtime'] = formatTimestamp($v['page_pushtime']);
		$thisuser =& $member_handler->getUser($v['page_author']);
    $pages[$k]['page_author'] = $thisuser->getVar('uname');
    unset($thisuser);
		
	}
    $xoopsTpl->assign("pages",$pages);
    $xoopsTpl->display("db:about_admin_page.html");
    break;

case "new":
    $page_obj =& $page_handler->create();
    $form = include "../include/form.page.php";
    $form->display();
    break;

case "edit":
    $page_obj =& $page_handler->get($page_id);
    $form = include "../include/form.page.php";
    $form->display();
    break;

case "save":
    if (isset($page_id)) {
        $page_obj =& $page_handler->get($page_id);
    }else {
        $page_obj =& $page_handler->create();
    }
    //assign value to elements of objects 
    foreach(array_keys($page_obj->vars) as $key) {
        if(isset($_POST[$key]) && $_POST[$key] != $page_obj->getVar($key)) {
            $page_obj->setVar($key, $_POST[$key]);
        }
    }
    //assign menu title
    if(empty($_POST['page_menu_title'])){
        $page_obj->setVar('page_menu_title', $_POST['page_title']);
    }
    //set index     
     if(!$page_handler->getCount()) $page_obj->setVar('page_index', 1);
    
    //
    //set submiter
    $page_obj->setVar('page_author',$xoopsUser->getVar('uid'));
    $page_obj->setVar('page_pushtime',time());
    
    if ($page_handler->insert($page_obj)) {
        redirect_header('admin.page.php?', 3, sprintf(_AM_ABOUT_SAVEDSUCCESS, _AM_ABOUT_PAGE_INSERT));
    }
    echo $page_obj->getHtmlErrors();
    $format = "p";
    $form = include "../include/form.page.php";
    $form->display();
    break;

case "delete":
    $page_obj =& $page_handler->get($page_id);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if($page_handler->delete($page_obj)) {
            redirect_header('admin.page.php', 3, sprintf(_AM_ABOUT_DELETESUCCESS, _AM_ABOUT_PAGE_INSERT));
        }else{
            echo $obj->getHtmlErrors();
        }
    }else{
        xoops_confirm(array('ok' => 1, 'id' => $page_obj->getVar('page_id'), 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_ABOUT_RUSUREDEL, $page_obj->getVar('page_menu_title')));
    }
    break;
}

xoops_cp_footer();
?>