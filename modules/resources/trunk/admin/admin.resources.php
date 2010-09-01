<?php
include 'header.php';

xoops_cp_header();
loadModuleAdminMenu(3, "");

include_once dirname(dirname(__FILE__)) . "/include/functions.php";

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'list';
$res_id = isset($_REQUEST['res_id']) ? trim($_REQUEST['res_id']) : '';

$category_handler =& xoops_getmodulehandler('category','resources');
$resources_handler =& xoops_getmodulehandler('resources','resources');
$attachments_handler =& xoops_getmodulehandler('attachments','resources');
$count_cat = $category_handler->getCount();
if(empty($count_cat)) redirect_header('admin.category.php', 3, '请添加资源分类');

switch ($op) {
default:
case 'list':
    $res_weight = isset($_REQUEST['res_weight']) ? $_REQUEST['res_weight'] : '';

    if(!empty($res_weight)){
        $ac_weight = ResourcesContentOrder($res_weight, 'resources', 'res_weight');
        if(!empty($ac_weight)) redirect_header('admin.resources.php', 3, '更新成功！');
    }
    
    //category
    $categories = $category_handler->getList();
    //resources
    $criteria = new CriteriaCompo();
  	$criteria->setSort('res_weight');
  	$criteria->setOrder('ASC');
  	$resources = $resources_handler->getAll($criteria, null, false);
 
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
     
    foreach($resources as $k=>$v) {
        $resources[$k]['cat_name'] = $categories[$v['cat_id']]; 
        $resources[$k]['grate_time'] = formatTimestamp($v['grate_time'],$date);
        $resources[$k]['update_time'] = formatTimestamp($v['update_time'],$date);
    }
    
    $xoopsTpl->assign('resources', $resources);
    $template_main = "resources_admin_resources.html";

    break;

case 'new':
    $res_obj =& $resources_handler->create();
    $action = 'action.resources.php?ac=insert';
    $form = $res_obj->getForm($action);
    $form->assign($xoopsTpl);
    $template_main = "resources_admin_form.html";
    break;

case 'edit':
    //atts
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('res_id', $res_id));
    $attachments = $attachments_handler->getAll($criteria, null, false);
    foreach ($attachments as $k=>$v) {
        $attachments[$k]['grate_time'] = formatTimestamp($v['grate_time'], 'Y-m-d');
        $attachments[$k]['update_time'] = formatTimestamp($v['update_time'], 'Y-m-d');
    }
    $xoopsTpl->assign('attachments', $attachments);
    
    //resources
    $xoopsTpl->assign('res_id', $res_id);
    
    $res_obj =& $resources_handler->get($res_id);
    $action = 'action.resources.php?ac=insert';
    $form = $res_obj->getForm($action);
    $form->assign($xoopsTpl);
    $template_main = "resources_admin_form.html";
    break;
}
    
include 'footer.php';

?>
