<?php
include "header.php";
$op = isset($_POST["op"]) ? $_POST["op"] : "";
$topic_id = isset($_POST["topic_id"]) ? intval($_POST["topic_id"]) : 0 ;
switch ($op) {
	case "delete":		
		$topic_handler = xoops_getmodulehandler("topics","notes");
		$topic = $topic_handler->get($topic_id);
		if ( is_object($topic) && ( $topic->getVar("uid") == $xoopsUser->getVar("uid") || $xoopsUserIsAdmin) ) {
		    $uid = $topic->getVar("uid");
		    if  ( $topic_handler->delete($topic) ) {
		        $stop = _NOTES_SUCCESSFULLY ;
		    } else {
		        $stop = _NOTES_DATABASE_FAIL;
		    }
		} else {
			$stop = _NOTES_DATABASE_FAIL;
		}	
		redirect_header("index.php?uid={$uid}",5,$stop);
	break;
	
	case "save";
	   if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("edit.php?op=edit&id={$topic_id}", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
        $topic_id = isset($_POST["topic_id"]) ? intval($_POST["topic_id"]) :0;
        $subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : "";
        $content = isset($_POST["content"]) ? trim($_POST["content"]) : "";
        $cat_id = isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
        
		$topic_handler = xoops_getmodulehandler("topics","notes");
		if ( $topic_id = $topic_handler->setTopics($topic_id,$cat_id,$xoopsUser->getVar("uid"),$subject,$content) ) {
		    // set a user notes 2=>"notes"
            $_obj = $topic_handler->get($topic_id);
            $subject = $_obj->getVar("subject","n");
            $content = xoops_substr($_obj->getVar("content"),0,240);
            $userevent_handler = xoops_getmodulehandler("events","user");
            $userevent_handler->setUserEvent($xoopsUser->getVar("uid"),"notes",$topic_id,$subject,$content);
            
			redirect_header("view.php?id={$topic_id}",5,_NOTES_SUCCESSFULLY);
		}
		redirect_header("edit.php",5,_NOTES_DATABASE_FAIL);	
	break;
		
    default:
        header("index.php");
    break;
}
include "footer.php";
?>