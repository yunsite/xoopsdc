<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(2, "");
include_once dirname(dirname(__FILE__)) . "/include/functions.php";

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'list';
$cat_id = isset($_REQUEST['cat_id']) ? trim($_REQUEST['cat_id']) : '';

$category_handler =& xoops_getmodulehandler('category','support');

switch ($op) {
default:
case 'list':
    $cat_weight = isset($_REQUEST['cat_weight']) ? $_REQUEST['cat_weight'] : '';

    if(!empty($cat_weight)){
        $ac_weight = SupportContentOrder($cat_weight, 'category', 'cat_weight');

        if(!empty($ac_weight)) redirect_header('admin.category.php', 3, '更新成功！');
    }

    $criteria = new CriteriaCompo();
  	$criteria->setSort('cat_weight');
  	$criteria->setOrder('ASC');
  	$categories = $category_handler->getAll($criteria, null, false);

    $xoopsTpl->assign('categories', $categories);
    $template_main = "support_admin_category.html";
    break;

case 'new':
    $cat_obj =& $category_handler->create();
    $action = 'action.category.php';
    $form = $cat_obj->getForm($action);
    $form->display();
    break;

case 'edit':
    $cat_obj =& $category_handler->get($cat_id);
    $action = 'action.category.php';
    $form = $cat_obj->getForm($action);
    $form->display();
    break;

}
    
include 'footer.php';

?>
