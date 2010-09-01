<?php
include "header.php";
$xoopsOption["xoops_pagetitle"] = $xoopsModuleConfig["notestitle"];
$op = isset($_GET["op"]) ? $_GET["op"] : "create";
$topic_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$topic_handler = xoops_getmodulehandler("topics","notes");
switch ($op) {
	default:
	case "create":
		$xoopsOption["template_main"] = "notes_edit.html";
		require_once XOOPS_ROOT_PATH."/header.php";
		require_once(dirname(__FILE__)."/include/form.php");		
	break;
	
	case "edit":
		$xoopsOption["template_main"] = "notes_edit.html";
		require_once XOOPS_ROOT_PATH."/header.php";
		$topic = $topic_handler->get($topic_id);
		if ( !is_object($topic) || $topic->getVar("uid") != $xoopsUser->getVar("uid") ) {
			redirect_header("index.php");
			exit();
		}
		if(is_object($topic)) {
			$cat_id = $topic->getVar("cat_id");
			$subject = $topic->getVar("subject");
			$content = $topic->getVar("content","n");
		} else {
			$topic_id = 0;
		}
		require_once(dirname(__FILE__)."/include/form.php");
	break;
	
}
include "footer.php";
?>