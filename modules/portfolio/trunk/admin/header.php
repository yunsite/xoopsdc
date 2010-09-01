<?php

include("../../../include/cp_header.php");
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$xoopsTpl = new XoopsTpl();
}

?>
