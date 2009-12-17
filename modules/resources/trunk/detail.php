<?php
include "header.php";

$resources_id = isset($_GET["resources_id"]) ? intval($_GET["resources_id"]) : 0;

if ( empty($resources_id) ) {
    redirect_header("index.php");
}

$resources_handler = xoops_getmodulehandler("resources");

$resources_obj = $resources_handler->get($resources_id);

if ( !is_object($resources_obj) || ( $resources_obj->getVar("resources_state") != 1 && ( !is_object($xoopsUser) || $xoopsUser->getVar("uid") != $resources_obj->getVar("uid") ) )) {
    redirect_header("index.php");
}

$xoopsOption['xoops_pagetitle'] = $xoopsModuleConfig["moduletitle"] . " - " .$resources_obj->getVar("resources_subject");
$xoopsOption["template_main"] = "resources_detail.html";
include_once XOOPS_ROOT_PATH.'/header.php';		

$resources = $resources_obj->getValues();
$resources["resources_content"] = $resources_obj->getVar("resources_content","n");
$member_handler = xoops_gethandler("member");
$user_obj = $member_handler->getUser($resources_obj->getVar("uid"));
$resources["name"] = $user_obj->getVar("name") ? $user_obj->getVar("name") : $user_obj->getVar("uname");

$cat_name = "";
if ( $resources_obj->getVar("cat_id") ) {
    $category_handler = xoops_getmodulehandler("category");
    $cat_obj = $category_handler->get($resources_obj->getVar("cat_id"));
    $cat_name = $cat_obj->getVar("cat_name");
}

$resources["cat_name"] = $cat_name;
$resources["resources_dateline"] = formatTimestamp($resources_obj->getVar("resources_dateline"));
if ( $resources["resources_attachment"] ) {
    $attachment_handler = xoops_getmodulehandler("attachments");
    $attachments = $attachment_handler->getAttachmentList($resources_id);
}
$resources["attachments"] = $attachments;
$xoopsTpl->assign("resources",$resources);
include 'footer.php';
?>