<?php

include("../../../include/cp_header.php");
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";
defined("FRAMEWORKS_ART_FUNCTIONS_INI") || include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.ini.php';
load_functions("admin");

// include the default language file for the admin interface
if ( !@include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/language/" . $xoopsConfig['language'] . "/main.php")) {
    include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/language/english/main.php");
}

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$xoopsTpl = new XoopsTpl();
}

?>
