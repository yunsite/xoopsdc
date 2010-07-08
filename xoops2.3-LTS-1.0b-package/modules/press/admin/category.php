<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(1, "");
$cat_id = isset($_GET["cat_id"]) ? intval($_GET["cat_id"]) : 0;
$op = isset($_GET["op"]) ? trim($_GET["op"]) : "";

$category_handler = xoops_getmodulehandler("category","press");
$cat_obj = $category_handler->get($cat_id);

if ( !empty($op) && $op == "delete" ) {
	xoops_confirm(array("cat_id"=>$cat_id,"op"=>"delcat"),"action.php",sprintf(_AM_PROFILE_FORM_RUSUREDEL, $cat_obj->getVar("cat_name")));
	include 'footer.php';
	exit();
}

$cat = array();
if ( $cat_obj ) {
	$cat = $cat_obj->getValues();
} else {
	$cat["cat_id"] = 0;
	$cat["cat_name"] = "";
}
include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$form = new XoopsForm("", "form", "action.php", "post",true);
$form->addElement(new XoopsFormText(_AM_PRESS_FORM_TITLE,"cat_name",60,255,$cat["cat_name"]));
$form->addElement(new XoopsFormHidden("op","savecategory"));
$form->addElement(new XoopsFormHidden("cat_id",$cat["cat_id"]));
$form->addElement(new XoopsFormButton("","submit",_SUBMIT,"submit"));
$form->assign($xoopsTpl);

$criteria = new CriteriaCompo();
$criteria->setSort('cat_id');         
$criteria->setOrder('ASC');

$cat_list = $category_handler->getAll($criteria,null,false);

$xoopsTpl->assign("cat_list",$cat_list);
$template_main = "press_cp_category.html";
include 'footer.php';
?>