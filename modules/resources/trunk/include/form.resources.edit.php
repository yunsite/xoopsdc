<?php
if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}
$form = new XoopsForm("", "form", "action.php", "post",true);
$form->setExtra("enctype=\"multipart/form-data\"");

$category_handler = xoops_getmodulehandler("category");
$cat_id = new XoopsFormSelect("分类","cat_id",$resources_array["cat_id"]);
$cat_id->addOptionArray($category_handler->getCategories());
$form->addElement($cat_id,true);


$form->addElement( new XoopsFormText("标题: ","resources_subject",60,255,$resources_array["resources_subject"]),true);
$form->addElement( new XoopsFormTextArea("描述: ","resources_content",$resources_array["resources_content"],6,60),true);


for( $i=1; $i<=$xoopsModuleConfig["attnum"]; $i++) {
	$form->addElement(new XoopsFormFile("附件{$i}:","attachments".$i,$xoopsModuleConfig["attsize"]));
}

$form->addElement(new XoopsFormHidden("resources_id",$resources_array["resources_id"]));
$form->addElement(new XoopsFormHidden("op","saveresources"));
$form->addElement(new XoopsFormButton("","submit",_SUBMIT,"submit"));
$form->assign($xoopsTpl);
?>