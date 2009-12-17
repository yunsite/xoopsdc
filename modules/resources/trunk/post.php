<?php
include "header.php";

$xoopsOption['xoops_pagetitle'] = $xoopsModuleConfig["moduletitle"] . " - Post Resources";

if ( !$xoopsModuleConfig["useruploads"] ) {
    redirect_header("index.php", 5, "抱歉，暂时不开放用户上传");
}

if ( !is_object($xoopsUser) ) {
    redirect_header(XOOPS_URL."/user.php", 5, _NOPERM);
}

$xoopsOption["template_main"] = "resources_post.html";
include_once XOOPS_ROOT_PATH.'/header.php';		

$resources_id = isset($_GET["resources_id"]) ? intval($_GET["resources_id"]) : 0;
$resources_handler = xoops_getmodulehandler("resources");
$attachment_list = array();
if ( !empty($resources_id) ) {
    $resources_obj = $resources_handler->get($resources_id);
    if ( $resources_obj->getVar("uid") != $xoopsUser->getVar("uid") ) {
        redirect_header("myresources.php", 5, _NOPERM);
    }
    if ( $resources_obj->getVar("resources_state") == 1 ) {
        redirect_header("myresources.php", 5, "抱歉，该资源已通过审核，无法在修改，如有问题请和管理员联系。");
    }
    $attachment_handler = xoops_getmodulehandler("attachments");
    $attachment_list = $attachment_handler->getAttachmentList($resources_id);
} else {
    $resources_obj = $resources_handler->get();
}

$resources_array = $resources_obj->getValues();
$resources_array["resources_content"] = $resources_obj->getVar("resources_content","n");
include_once(dirname(__FILE__)."/include/form.resources.edit.php");
$xoopsTpl->assign("attachment_list",$attachment_list);
include "footer.php";
?>