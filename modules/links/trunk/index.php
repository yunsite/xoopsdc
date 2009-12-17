<?php
include "header.php";

$cat_handler =& xoops_getmodulehandler('category', 'links');
$link_handler =& xoops_getmodulehandler('links', 'links');

$xoopsOption['template_main'] = 'links_index.html';
include XOOPS_ROOT_PATH.'/header.php';

$criteria = new CriteriaCompo();
$criteria->setSort('cat_order');
$criteria->setOrder('ASC');
$categories = $cat_handler->getAll($criteria, array('cat_id', 'cat_name', 'cat_order'), false);

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('link_status', 1));
$criteria->setSort('link_order');
$criteria->setOrder('ASC');
$links = $link_handler->getAll($criteria, null, false, false);

foreach($links as $k=>$v){            
    $links[$k]['published'] = formatTimestamp($v['published'],'Y-m-d H:i:s');
    $links[$k]['datetime'] = formatTimestamp($v['datetime'],'Y-m-d H:i:s');
    if(!empty($xoopsModuleConfig['logo'])){
        $links[$k]['link_logo'] = XOOPS_URL.$xoopsModuleConfig['logo_dir'].$v['link_image'];
    }else{ 
        $links[$k]['link_logo'] = $v['link_dir'];
    }
}

foreach($categories as $cat_id=>$category){
    foreach($links as $link_id=>$link){
        if($cat_id == $link['cat_id']) $categories[$cat_id]['links'][$link_id] = $link;
    }
}
$xoopsTpl->assign('categories', $categories);
$xoopsTpl->assign('logo_display', $xoopsModuleConfig['logo']);
$xoopsTpl->assign('application', $xoopsModuleConfig['application']);
include 'footer.php';
?>
