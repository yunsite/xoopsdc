<?php

include('header.php');

xoops_cp_header();
loadModuleAdminMenu(0);

$path = isset($_REQUEST['path']) ? $_REQUEST['path'] : '';
$cat_handler =& xoops_getmodulehandler('category', 'links');
$links_handler =& xoops_getmodulehandler('links', 'links');

$create = array(
    //'category_dir' => XOOPS_ROOT_PATH.$xoopsModuleConfig['category_dir'],
    'logo_dir' => XOOPS_ROOT_PATH.$xoopsModuleConfig['logo_dir']
    );
    
if(!empty($path)){
    include_once("../include/functions.php");
    switch ($path) {
        case 'category_dir':
            mkdirs($create['category_dir']);
        break;
        case 'logo_dir':
            mkdirs($create['logo_dir']);
        break;
        default:
        break;
    }
}    

foreach ($create as $k=>$v){
    if(!is_dir($v)){
        $create[$k] = $v.'&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.index.php?path='.$k.'">' ._AM_LINKS_CREATE. '</a>';
    }else{
        $create[$k] = $v.'&nbsp;&nbsp;&nbsp;&nbsp;' ._AM_LINKS_DIRCREATED;
    }
}
$count['category'] = $cat_handler->getCount();
$count['links_Total'] = $links_handler->getCount();
$count['links_Release'] = $links_handler->getCount(new Criteria('link_status', 1));
$count['links_Draft'] = $links_handler->getCount(new Criteria('link_status', 0));

$xoopsTpl->assign('create', $create);
$xoopsTpl->assign('count', $count);
$xoopsTpl->assign('logo', $xoopsModuleConfig['logo']);
$xoopsTpl->display("db:links_admin_index.html");

xoops_cp_footer();
?>
