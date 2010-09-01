<?php
if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}
include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$form = new XoopsForm("", "form", "action.php", "post",true);
$category_handler = xoops_getmodulehandler("category","press");
$catarr = $category_handler->getCategorySelect(null);

$cat_id = new XoopsFormSelect(_AM_PRESS_CATEGORIES,"cat_id",@$cat_id);
$cat_id->addOptionArray($catarr);
$form->addElement($cat_id,true);

$subject = new XoopsFormText(_AM_PRESS_SUBJECT,"subject",60,255,@$subject);
$form->addElement($subject,true);

$configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>@$content); 
$form->addElement(new XoopsFormEditor(_AM_PRESS_CONTENT, 'content',$configs), true);
$form->addElement(new XoopsFormDateTime("发布时间","topic_date","",@$date));
$form->addElement(new XoopsFormHidden("topic_id",@$topic_id));
$form->addElement(new XoopsFormHidden("op","save"));
$form->addElement(new XoopsFormButton("","submit",_SUBMIT,"submit"));

$form->assign($xoopsTpl);
?>
