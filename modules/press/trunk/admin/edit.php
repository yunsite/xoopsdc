<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(0, "");
xoops_loadLanguage("main","press");
$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : "create";
$topic_id = isset($_GET["topic_id"]) ? intval($_GET["topic_id"]) : 0;
$topic_handler = xoops_getmodulehandler("topics");
$topic = $topic_handler->get($topic_id);
switch ($op) {
	default:
	case "create":
		require_once(dirname(__FILE__)."../../include/form.php");	
		$template_main = "press_cp_edit.html";
	break;
	
	case "edit":
		if( is_object($topic) ) {
			$cat_id = $topic->getVar("cat_id");
			$subject = $topic->getVar("subject");
			$content = $topic->getVar("content","n");
			$date = $topic->getVar("topic_date");
		} else {
			$topic_id = 0;
			$date = time();
		}
		require_once(dirname(__FILE__)."../../include/form.php");
		$template_main = "press_cp_edit.html";
	break;
	
	case "delete":
		xoops_confirm(array("topic_id"=>$topic_id,"op"=>"deletetopic"),"action.php","确定删除 [ {$topic->getVar("subject")} ] 这篇主题.");
	break;
}

include 'footer.php';
?>