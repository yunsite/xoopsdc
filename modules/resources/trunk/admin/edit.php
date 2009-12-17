<?php
include "header.php";
loadModuleAdminMenu(1);

$resources_id = isset($_GET["resources_id"]) ? intval($_GET["resources_id"]) : 0;
$resources_handler = xoops_getmodulehandler("resources");
$attachment_list = array();
if ( !empty($resources_id) ) {
    $resources_obj = $resources_handler->get($resources_id);
    if ( $resources_obj->getVar("resources_attachment") ) {
        $attachment_handler = xoops_getmodulehandler("attachments");
        $attachment_list = $attachment_handler->getAttachmentList($resources_id);
    }
} else {
    $resources_obj = $resources_handler->get($resources_id);
}

$resources_array = $resources_obj->getValues();
$resources_array["resources_content"] = $resources_obj->getVar("resources_content","n");
include_once(dirname(__FILE__)."../../include/form.resources.edit.php");

$xoopsTpl->assign("attachment_list",$attachment_list);

$xoopsTpl->display("db:resources_cp_edit.html");
include "footer.php";
?>