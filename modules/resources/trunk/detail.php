<?php
include_once 'header.php';

// Parameter
$res_id = isset($_GET["res_id"]) ? trim($_GET["res_id"]) : 0;

// Get handler
$cat_handler =& xoops_getmodulehandler('category', 'resources');
$res_handler =& xoops_getmodulehandler('resources', 'resources');
$att_handler =& xoops_getmodulehandler('attachments', 'resources');

$res_obj = $res_handler->get($res_id);
if(!is_object($res_obj) || empty($res_id)) redirect_header('index.php', 3, '没有该资源！');

$xoopsOption['template_main'] = 'resources_detail.html';
include XOOPS_ROOT_PATH.'/header.php';

                      
// resources
$resources = $res_obj->getValues(null, 'n');

//user is edit
if($xoopsUser){
    if($res_obj->getVar('uid') == $xoopsUser->getVar("uid")) {
        $xoopsTpl->assign('is_editor', 1);
    }
}
//category
$cat_obj = $cat_handler->get($resources['cat_id']);

$resources['cat_name'] = $cat_obj->getVar('cat_name');

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
$resources['update_time'] = formatTimestamp($resources['update_time'],$date);
if($resources['res_rates']){
	$resources['res_rating'] = number_format($resources['res_rating']/$resources['res_rates'], 2);
}    

if($res_obj->getVar('res_status') == 0 || $cat_obj->getVar('cat_status') == 0) redirect_header('index.php', 3, '没有该资源！');
$xoopsTpl->assign('resources', $resources);
$xoopsTpl->assign("canRate", !empty($xoopsModuleConfig["do_rate"]));              
// attachments
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('res_id', $res_id));
$attachments = $att_handler->getAll($criteria, null, false);
foreach ($attachments as $k=>$v) {
    $attachments[$k]['grate_time'] = formatTimestamp($v['grate_time'], $date);
    $attachments[$k]['update_time'] = formatTimestamp($v['update_time'], $date);  
}
$xoopsTpl->assign('attachments', $attachments);

$xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
$xoBreadcrumbs[] = array("title" => $resources['res_subject']);
                    
$xoopsOption['xoops_pagetitle'] = $xoopsModule->getVar('name');

include XOOPS_ROOT_PATH . "/include/comment_view.php";
include_once 'footer.php';
?>
