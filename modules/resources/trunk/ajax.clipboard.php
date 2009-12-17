<?php
include "header.php";
header('Content-Type:text/html; charset='._CHARSET);
$GLOBALS["xoops"]->services["logger"]->activated = false ;

$resources_id = isset($_GET["resources_id"]) ? intval($_GET["resources_id"]) : 0;

if ( !is_object($xoopsUser) ) {
	echo "Login";
	exit();
}

if ( empty($resources_id) ) redirect_header("index.php");

$resources_handler = xoops_getmodulehandler("resources");
$clipboard_handler = xoops_getmodulehandler("clipboard");

$resources_obj = $resources_handler->get($resources_id);

if ( !is_object($resources_obj) ) redirect_header("index.php");

$criteria = new CriteriaCompo(new Criteria("resources_id",$resources_id));
$criteria->add(new Criteria("uid",$xoopsUser->getVar("uid")));

if ( $clipboard_handler->getCount($criteria) ) {
    echo "已存在收藏夹！";
    exit();
}

$clipboard_obj = $clipboard_handler->get();
$clipboard_obj->setVar("uid",$xoopsUser->getVar("uid"));
$clipboard_obj->setVar("resources_id",$resources_id);
$clipboard_obj->setVar("clipboard_dateline",time());
if ( $clipboard_handler->insert($clipboard_obj) ) {
    echo "Clipboard succeed";
} else {
    echo "Clipboard failed";
}
exit();
include "footer.php";
?>