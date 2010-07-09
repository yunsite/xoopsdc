<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(3, "");
include_once dirname(dirname(__FILE__)) . "/include/functions.php";

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'list';
$case_id = isset($_REQUEST['case_id']) ? trim($_REQUEST['case_id']) : '';

$service_handler = xoops_getmodulehandler('service','portfolio');
$case_handler = xoops_getmodulehandler('case','portfolio');
$cs_handler = xoops_getmodulehandler('cs','portfolio');
switch ($op) {
default:
case 'list':
    $case_weight = isset($_REQUEST['case_weight']) ? $_REQUEST['case_weight'] : '';

    if(!empty($case_weight)){
        $ac_weight = PortfolioContentOrder($case_weight, 'case', 'case_weight');
        if(!empty($ac_weight)) redirect_header('admin.case.php', 3, '更新成功！');
    }

    $criteria = new CriteriaCompo();
  	$criteria->setSort('case_weight');
  	$criteria->setOrder('ASC');
  	$cases = $case_handler->getAll($criteria, null, false, true);

    if(!empty($cases)) {
        $services = $service_handler->getList();
        foreach ($cases as $k=>$v) $case_ids[] = $k;            
    
        $cs_services = $cs_handler->getServerIds($case_ids);
        foreach($cs_services as $k=>$v) {
            $cs_services[$k]['service'] = $services[$v['service_id']];
        }
                
        foreach ($cases as $k=>$v) {
            $cases[$k]['case_datetime'] = formatTimestamp($v['case_datetime'],'Y-m-d');
            $cases[$k]['service'] = '';
            foreach ($cs_services as $key=>$val) {
                if($k == $val['case_id']) $cases[$k]['service'] .= $val['service'] . '&nbsp;&nbsp;&nbsp;&nbsp;';        
            }       
        }
    }
 
    $xoopsTpl->assign('cases', $cases);
    $template_main = "portfolio_admin_case.html";
    break;

case 'new':
    $case_obj =& $case_handler->create();
    $action = 'action.case.php';
    $form = $case_obj->getForm($action);
    $form->assign($xoopsTpl);
    $xoopsTpl->display("db:portfolio_admin_case_form.html");
    break;

case 'edit':
    $case_obj =& $case_handler->get($case_id);
    $action = 'action.case.php';
    $form = $case_obj->getForm($action);
    $form->assign($xoopsTpl);
    $xoopsTpl->display("db:portfolio_admin_case_form.html");
    break;

}
    
include 'footer.php';

?>
