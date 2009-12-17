<?php
include 'header.php';
loadModuleAdminMenu(2);

$cat_id = isset($_GET["cat_id"]) ? intval($_GET["cat_id"]) : 0;
$op = isset($_GET["op"]) ? trim($_GET["op"]) : "";

$category_handler = xoops_getmodulehandler("category");
$cat_obj = $category_handler->get($cat_id);

if ( !empty($op) && $op == "delete" ) {
	xoops_confirm(array("cat_id"=>$cat_id,"op"=>"delcat"),"action.php","确定删除?  [{$cat_obj->getVar("cat_name")}] 这个分类，分类下的主题将全类设为没有分类 ！");
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

$form = new XoopsForm("", "form", "action.php", "post",true);
$form->addElement(new XoopsFormText("分类名称: ","cat_name",16,255,$cat["cat_name"]));
$form->addElement(new XoopsFormHidden("op","savecategory"));
$form->addElement(new XoopsFormHidden("cat_id",$cat["cat_id"]));
$form->addElement(new XoopsFormButton("","submit",_SUBMIT,"submit"));
$form->assign($xoopsTpl);

$cat_list = $category_handler->getAll(null,null,false);

$xoopsTpl->assign("cat_list",$cat_list);

$xoopsTpl->display("db:resources_cp_category.html");
include 'footer.php';
?>