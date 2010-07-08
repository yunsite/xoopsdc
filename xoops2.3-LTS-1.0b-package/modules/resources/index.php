<?php
include_once 'header.php';

// Parameter
$cat_id = isset($_GET["cat_id"]) ? trim($_GET["cat_id"]) : 0;
$start = isset( $_REQUEST['start'] ) ? trim($_REQUEST['start']) : 0;
$limit = $xoopsModuleConfig['pagenav'];
if($start) $ext = 'cat_id='.$cat_id;

// Get handler
$cat_handler =& xoops_getmodulehandler('category', 'resources');
$res_handler =& xoops_getmodulehandler('resources', 'resources');
$att_handler =& xoops_getmodulehandler('attachments', 'resources');

if($cat_id > 0) {
    $cat_obj = $cat_handler->get($cat_id);
    if(!is_object($cat_obj)) redirect_header('index.php', 3, '没有该分类！');
}

$xoopsOption['template_main'] = 'resources_index.html';
include XOOPS_ROOT_PATH.'/header.php';

//category
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_status', 1));
$criteria->setSort('cat_weight');
$criteria->setOrder('ASC');
$categories = $cat_handler->getAll($criteria, null, false);


// rescorces
$criteria = new CriteriaCompo();
if(!empty($cat_id)) $criteria->add(new Criteria('cat_id', $cat_id));
$criteria->add(new Criteria('res_status', 1));
$criteria->setSort('res_weight');
$criteria->setOrder('ASC');
$criteria->setLimit($limit);
$criteria->setStart($start);

// pageNav
if ($res_handler->getCount($criteria) > $limit ){
    include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
    $pageNav = new XoopsPageNav($res_handler->getCount($criteria), $limit, $start, 'start', @$ext);
    $xoopsTpl->assign('pagenav', $pageNav->renderNav(4));
}

$rescorces = $res_handler->getAll($criteria, null, false);

if($xoopsModuleConfig['timeformat'] == '1'){
    $date = 'y-m-d';
}elseif($xoopsModuleConfig['timeformat'] == '2'){
    $date = 'y-m-d h:i:s';
}elseif($xoopsModuleConfig['timeformat'] == '3'){
    $date = 'Y年m月d日';
}elseif($xoopsModuleConfig['timeformat'] == '4'){
    $date = 'Y年m月d日  h小时:i分钟:s秒';
}else{
    $date = '';
}
 
if(!empty($rescorces)) {
    foreach ($rescorces as $k=>$v) {               
        $rescorces[$k]['grate_time'] = formatTimestamp($v['grate_time'], $date);
        $rescorces[$k]['update_time'] = formatTimestamp($v['update_time'],$date);
        if(array_key_exists($v['cat_id'], $categories)){
            $rescorces[$k]['cat_name'] = $categories[$v['cat_id']]['cat_name'];    
        } else {
            unset($rescorces[$k]);
        }
    }
}
$xoopsTpl->assign('rescorces', $rescorces);

if(!empty($cat_id)) {            
    $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
    $xoBreadcrumbs[] = array("title" => $categories[$cat_id]['cat_name']);
} else {
    $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'));
}

$xoopsOption['xoops_pagetitle'] = $xoopsModule->getVar('name');
include_once 'footer.php';
?>
