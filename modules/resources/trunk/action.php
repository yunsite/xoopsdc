<?php
include "header.php";
include_once(dirname(__FILE__)."/include/functions.php");

if ( !is_object($xoopsUser) ) {
    redirect_header("index.php");
}

$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : "";
$ac = isset($_POST["ac"]) ? $_POST["ac"] : "";
$resources_handler = xoops_getmodulehandler("resources");
$attachments_handler = xoops_getmodulehandler("attachments");
switch ($op) {
    case "delete":
        switch ($ac) {
            default:
                $xoopsOption["template_main"] = "resources_action.html";
                include_once XOOPS_ROOT_PATH.'/header.php';	
                $resources_id = isset($_GET["resources_id"]) ? intval($_GET["resources_id"]) : 0;
                $resources_obj = $resources_handler->get($resources_id);
                if ( $resources_obj->getVar("uid") != $xoopsUser->getVar("uid") ) {
                    redirect_header("index.php");
                }
                $resources = $resources_obj->getVar("resources_subject");
                $confirm_resources = xoops_confirm_resources(array("resources_id"=>$resources_id,"ac"=>"delete","op"=>"delete"),"action.php","确定删除 [{$resources}] 这条记录!");
                $xoopsTpl->assign("confirm_resources",$confirm_resources);
            break;
            
            case "delete":
                $resources_id = isset($_POST["resources_id"]) ? intval($_POST["resources_id"]) : 0;
                $resources_obj = $resources_handler->get($resources_id);
                if ( $resources_obj->getVar("uid") == $xoopsUser->getVar("uid") ) {
                    if ( $resources_handler->delete($resources_obj) ) {
                        $criteria = new CriteriaCompo(new Criteria("resources_id",$resources_id));
                        $attachment_objs = $attachments_handler->getAll($criteria);
                        if ( $attachment_objs ) {
                            foreach ( $attachment_objs as $attachment_obj ) {
                                @unlink(XOOPS_VAR_PATH."/resources/".$att_obj->getVar("att_attachment"));
                                $attachments_handler->delete($attachment_obj);
                            }
                        }
//                        $attachments_handler->deleteAll($criteria);
                        redirect_header("myresources.php", 5, "Delete succeed");
                    }
                }
                redirect_header("myresources.php", 5, "Delete failed");
            break;
        }
    break;
    
    case "attachmentdel":
        switch ($ac) {
            default:
                $xoopsOption["template_main"] = "resources_action.html";
                include_once XOOPS_ROOT_PATH.'/header.php';	
                $att_id = isset($_GET["att_id"]) ? intval($_GET["att_id"]) : 0;
                $attachment_obj = $attachments_handler->get($att_id);
                if ( $attachment_obj->getVar("uid") != $xoopsUser->getVar("uid") ) {
                    redirect_header("index.php");
                }
                $attachment = $attachment_obj->getVar("att_filename");
                $confirm_resources = xoops_confirm_resources(array("resources_id"=>$attachment_obj->getVar("resources_id"),"att_id"=>$att_id,"ac"=>"delete","op"=>"attachmentdel"),"action.php","确定删除 [{$attachment}] 这条记录!");
                $xoopsTpl->assign("confirm_resources",$confirm_resources);
            break;
            
            case "delete":
                $resources_id = isset($_POST["resources_id"]) ? intval($_POST["resources_id"]) : 0;
                $att_id = isset($_POST["att_id"]) ? intval($_POST["att_id"]) : 0;
                $resources_obj = $resources_handler->get($resources_id);
                $attachment_obj = $attachments_handler->get($att_id);
                
                if ( !$resources_obj->getVar("resources_state") && $attachment_obj->getVar("uid") == $xoopsUser->getVar("uid")) {
                    $att_attachment = $attachment_obj->getVar("att_attachment");
                    if ( $attachments_handler->delete($attachment_obj) ) {
                        @unlink(XOOPS_VAR_PATH."/resources/".$att_attachment);
                        $resources_obj->setVar("resources_attachment",$resources_obj->getVar("resources_attachment") - 1 );
                        $resources_handler->insert($resources_obj);
                        redirect_header("post.php?resources_id={$resources_id}", 5, "Delete succeed");
                    }
                }
                redirect_header("myresources.php", 5, "Delete failed");
            break;
        }
    break;
    
    case "clipboarddel":
        switch ($ac) {
            default:
                $xoopsOption["template_main"] = "resources_action.html";
                include_once XOOPS_ROOT_PATH.'/header.php';	
                $clipboard_id = isset($_GET["clipboard_id"]) ? intval($_GET["clipboard_id"]) : 0;
                $clipboard_handler = xoops_getmodulehandler("clipboard");
                $clipboard_obj = $clipboard_handler->get($clipboard_id);
                if ( $clipboard_obj->getVar("uid") != $xoopsUser->getVar("uid") ) {
                    redirect_header("index.php");
                }
                $confirm_resources = xoops_confirm_resources(array("clipboard_id"=>$clipboard_id,"ac"=>"delete","op"=>"clipboarddel"),"action.php","确定删除这条记录!");
                $xoopsTpl->assign("confirm_resources",$confirm_resources);
            break;
            
            case "delete":
                $clipboard_id = isset($_POST["clipboard_id"]) ? intval($_POST["clipboard_id"]) : 0;
                $clipboard_handler = xoops_getmodulehandler("clipboard");
                $clipboard_obj = $clipboard_handler->get($clipboard_id);
                if ( $clipboard_obj->getVar("uid") == $xoopsUser->getVar("uid") ) {
                    if ( $clipboard_handler->delete($clipboard_obj) ) {
                        redirect_header("myclipboard.php", 5, "Delete succeed");
                    }
                }
                redirect_header("myclipboard.php", 5, "Delete failed");
            break;
        }
    break;
    
	case "saveresources":
		if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("myresources.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
        if ( !trim($_POST["resources_subject"]) || !trim($_POST["resources_content"]) ) {
            redirect_header("post.php",5,"Subject Or Content Error");
        }
        if ( $resources_id = $resources_handler->setResources() ) {
        	$attachments_handler->setAttachments($resources_id);
        	$stop = "uploads succeed";
        	if ( !$xoopsModuleConfig["useruploadscheck"] ) {
        	    redirect_header("detail.php?resources_id={$resources_id}",5,$stop);
        	} else {
        	    if ( !empty($resources_id) ) {
        	       redirect_header("post.php?resources_id={$resources_id}",5,$stop . ", waiting admin check");
        	    } else {
        	        redirect_header("myresources.php",5,$stop . ", waiting admin check");
        	    }
        	}
        }
        redirect_header("post.php",5,"uploads failed");
	break;
	default:
	    redirect_header("index.php");
	break;
}
include "footer.php";
?>