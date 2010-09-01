<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(2, "");
include_once dirname(dirname(__FILE__)) . "/include/functions.php";

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'list';
$service_id = isset($_REQUEST['service_id']) ? trim($_REQUEST['service_id']) : '';

$service_handler = xoops_getmodulehandler('service','portfolio');

switch ($op) {
default:
case 'list':
    $service_weight = isset($_REQUEST['service_weight']) ? $_REQUEST['service_weight'] : '';
    
    if(!empty($service_weight)){
        $ac_weight = PortfolioContentOrder($service_weight, 'service', 'service_weight');
        if(!empty($ac_weight)) redirect_header('admin.service.php', 3, '更新成功！');
    }

    $services =& $service_handler->getTrees(0, "&nbsp;&nbsp;");
    
    foreach ($services as $k=>$v) {
        $services[$k]['service_datetime'] = formatTimestamp($v['service_datetime'],'Y-m-d');
    }
    
    $xoopsTpl->assign('services', $services);
    $template_main = "portfolio_admin_service.html";
    break;

case 'new':
    $service_obj =& $service_handler->create();
    $action = 'action.service.php';
    $form = $service_obj->getForm($action);
    $form->display();
    break;

case 'edit':
    $service_obj =& $service_handler->get($service_id);
    $action = 'action.service.php';
    $form = $service_obj->getForm($action);
    $form->display();
    break;

}
    
include 'footer.php';

?>
