<?php
include_once 'header.php';
include_once "include/functions.render.php";

$service_id = isset($_REQUEST['service_id']) ? trim($_REQUEST['service_id']) : '';

$service_handler = xoops_getmodulehandler('service','portfolio');

// Server Menu
$menu_criteria = new CriteriaCompo();
$menu_criteria->add(new Criteria('service_status', 1), 'AND');
$menu_criteria->setSort('service_weight');
$menu_criteria->setOrder('ASC');
$service_menu = $service_handler->getAll($menu_criteria, null, false);

$criteria = new CriteriaCompo();
if(!empty($service_id)){
    $criteria->add(new Criteria('service_id',$service_id));
}else{
    if(!empty($service_menu)) {
        $current_service = current($service_menu);
        $criteria->add(new Criteria('service_id', $current_service['service_id']));
    }
}
$criteria->add(new Criteria('service_status', 1));
$service = current($service_handler->getAll($criteria, null, false, false));	
$xoopsOption['xoops_pagetitle'] = $service['service_menu_name']. ' - ' . $xoopsModule->getVar('name');
$xoopsOption['template_main'] = portfolio_getTemplate("service",  $service['service_tpl']);
include XOOPS_ROOT_PATH.'/header.php';

if(!empty($service)){

    $myts = MyTextSanitizer::getInstance();
    $service['service_desc'] = $myts->undoHtmlSpecialChars($service['service_desc']);
    
    $xoTheme->addMeta('meta','description', $service['service_menu_name']);    
    $xoopsTpl->assign('service_menu', $service_menu);
    $xoopsTpl->assign('service', $service); 
    
    if ($service_id) $xoBreadcrumbs[] = array('title' => $service['service_menu_name']);
}

include_once 'footer.php';
?>
