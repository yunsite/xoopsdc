<?php
include "header.php";
$GLOBALS["xoops"]->services["logger"]->activated = false ;
if (isset($xoops->services["http"])) { $xoops->services["http"]->setCachingPolicy("nocache"); } 
header("Content-Type:text/html; charset="._CHARSET);

include_once XOOPS_ROOT_PATH ."/class/template.php";
$op = isset($_GET["op"]) ? $_GET["op"] : "";
$topic_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$xoops_redirect = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";


$topic_handler = xoops_getmodulehandler("topics","notes");

switch ($op) {
	case "delete":
		$topic = $topic_handler->get($topic_id);
		if ( !is_object($topic) || $topic->getVar("uid") != $xoopsUser->getVar("uid") ) {
			echo _NOTES_POST_ACCESS_NOREPLY;
			exit();
		}
		echo xoops_confirm_notes(array("topic_id"=>$topic_id,"op"=>"delete"),"action.php",sprintf(_MA_NOTES_DELETETOPIC,$topic->getVar("subject","n")),_SUBMIT);
		exit();
	break;
	
	case "addcat":
		$cat_name = isset($_POST["cat_name"]) ? trim($_POST["cat_name"]) : "";
		if ( empty($cat_name) ) {
			echo 0;
			exit();
		}
		$cat_handler = xoops_getmodulehandler("category","notes");
		if (  $cat_id = $cat_handler->setCatname(0,$xoopsUser->getVar("uid"),$cat_name) ) {
		    $catArr = $cat_handler->getCategorySelect($xoopsUser->getVar("uid"));
			$str = "<select id=\"cat_id\" name=\"cat_id\" size=\"1\">";
			foreach ( $catArr as $k=>$v ) {
				if ( $cat_id == $k) {
					$str .= "<option value=\"{$k}\" selected >{$v}</option>";
				} else {
					$str .= "<option value=\"{$k}\" >{$v}</option>";
				}
			}
			$str .= "</select>";
			echo $str;
		} else {
			echo 0;
			exit();
		}
	break;
	
	default:
	    exit();
	break;
}
?>