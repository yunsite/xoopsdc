<?php
include_once 'header.php';
include_once "include/functions.render.php";

$service_id = isset($_REQUEST['service_id']) ? trim($_REQUEST['service_id']) : '';
$case_id = isset($_REQUEST['case_id']) ? trim($_REQUEST['case_id']) : '';

$service_handler = xoops_getmodulehandler('service','portfolio');
$case_handler = xoops_getmodulehandler('case','portfolio');
$cs_handler = xoops_getmodulehandler('cs','portfolio');
$images_handler = xoops_getmodulehandler('images','portfolio');

$service_obj = $service_handler->get($service_id);
//if(!is_object($service_obj) || empty($service_id)) redirect_header('index.php', 3, '您所访问的服务不存在！');
// Server Menu
$servermenu_criteria = new CriteriaCompo();
$servermenu_criteria->add(new Criteria('service_status', 1), 'AND');
$servermenu_criteria->setSort('service_weight');
$servermenu_criteria->setOrder('ASC');
$service_menu = $service_handler->getAll($servermenu_criteria, null, false);

// Case Menu
$cs = $cs_handler->getCaseIds(array($service_id));
$case_menu = '';
if(!empty($cs)) {
    foreach ($cs as $k=>$v) {
        $case_ids[] = $v['case_id'];
    }
    $menu_criteria = new CriteriaCompo();
    $menu_criteria->add(new Criteria("case_id","(".implode(", ",$case_ids). ")","in"), 'AND');
    $menu_criteria->add(new Criteria('case_status', 1));
    $menu_criteria->setSort('case_weight');
    $menu_criteria->setOrder('ASC');
    $case_menu = $case_handler->getAll($menu_criteria, null, false);
}

$criteria = new CriteriaCompo();
if(!empty($case_id)){
    $criteria->add(new Criteria('case_id',$case_id));
}else{
    if(!empty($case_menu)) {
        $current_case = current($case_menu);
        $criteria->add(new Criteria('case_id',$current_case['case_id']));
    }
}
$criteria->add(new Criteria('case_status', 1));
$case = current($case_handler->getAll($criteria, null, false, false));	

$xoopsOption['xoops_pagetitle'] = $case['case_menu_title'] . ' - ' . $service_obj->getVar('service_menu_name') . ' - ' . $xoopsModule->getVar('name');
$xoopsOption['template_main'] = portfolio_getTemplate("case",  $case['case_tpl']);
include XOOPS_ROOT_PATH.'/header.php';
$xoopsTpl->assign('service_menu', $service_menu); 
if(!empty($case)){ 
    //Case
    $case['service_id'] = $service_id;
    $myts = MyTextSanitizer::getInstance();
    $case['case_summary'] = $myts->undoHtmlSpecialChars($case['case_summary']);
    $case['case_description'] = $myts->undoHtmlSpecialChars($case['case_description']);
    
    // Case Servers
    $cs = $cs_handler->getServerIds(array($case['case_id']));
 
    if(!empty($cs)) {
        foreach ($cs as $k=>$v) {
            $service_ids[] = $v['service_id'];
        }
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("service_id","(".implode(", ",$service_ids). ")","in"), 'AND');
        $criteria->add(new Criteria('service_status', 1));
        $criteria->setSort('service_weight');
        $criteria->setOrder('ASC');
        $case['case_services'] = $service_handler->getList($criteria);
    }
    
    // Case gallery
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('case_id', $case['case_id']));
    $case['case_gallery'] = $images_handler->getAll($criteria, null, false);

    $xoTheme->addMeta('meta', 'description', $case['case_menu_title']);
    $xoopsTpl->assign('case_menu', $case_menu);
    $xoopsTpl->assign('case', $case);
    
    if ($service_id) $xoBreadcrumbs[] = array('title' => $service_obj->getVar('service_menu_name'), 'link' =>  'index.php?service_id='.$service_id);
    if ($case_id) $xoBreadcrumbs[] = array('title' => $case['case_menu_title']);
    $xoopsTpl->assign('service_id',  $service_id);
    $xoopsTpl->assign('case_id',  $case_id); 
}

include_once 'footer.php';
?>
