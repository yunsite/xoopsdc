<?php
include '../../../include/cp_header.php';
defined("FRAMEWORKS_ART_FUNCTIONS_INI") || include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.ini.php';
load_functions("admin");
xoops_cp_header();
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$xoopsTpl = new XoopsTpl();
}
?>
