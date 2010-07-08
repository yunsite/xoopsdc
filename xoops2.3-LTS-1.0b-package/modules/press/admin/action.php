<?php
include "header.php";
$op = isset($_POST["op"]) ? $_POST["op"] : "list";
$topic_id = isset($_POST["topic_id"]) ? intval($_POST["topic_id"]) : 0 ;
$topic_handler = xoops_getmodulehandler("topics","press");
switch ($op) {
	
	case "deletetopic":		
		if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("index.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
		$topic = $topic_handler->get($topic_id);
		xoops_comment_delete($xoopsModule->getVar('mid'), $topic_id);
		if ( $topic_handler->delete($topic) ) {
			$stop = _PRESS_SUCCESSFULLY;
		} else {
			$stop =_PRESS_DATABASE_FAIL ;
		}
		redirect_header("index.php",5,$stop);		
	break;
	
	case "savecategory":
		if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("category.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
		$cat_id = isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$category_handler = xoops_getmodulehandler("category","press");
		if ( empty($cat_id) ) {
			$cat_obj = $category_handler->get();
		} else {
			$cat_obj = $category_handler->get($cat_id);
		}
		if ( $cat_obj->getVar("cat_name") != trim($_POST["cat_name"]) ) {
			$cat_obj->setVar("cat_name",trim($_POST["cat_name"]));
			if ( $category_handler->insert( $cat_obj ) ) {
				$stop = _MA_PRESS_SAVESUCCESS;
			} else {
				$stop = _MA_PRESS_SAVEFAILED;
			}
		} else {
			if ( !trim($_POST["cat_name"]) ) {
				$stop = _MA_PRESS_INCORRECTNAME;
			} else {
				$stop = _MA_PRESS_SAMETITLE;
			}
		}
		redirect_header("category.php",5,$stop);
	break;
	
	case "delcat":
		$cat_id = isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$cat_handler = xoops_getmodulehandler("category","press");
		if ( true === $cat_handler->delCategory($cat_id) ){
			redirect_header("category.php",5,_MA_PRESS_SUCCESSFULLY);
		} else {                                    
			redirect_header("category.php",5,_MA_PRESS_DATABASE_FAIL);
		}	
	break;
    case "save":
        $category_handler = xoops_getmodulehandler("category","press");
        $count = $category_handler->getCount();
        if(empty($count)) redirect_header('category.php', 3, '请先添加新闻分类');    
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("edit.php?op=edit&id={$topic_id}", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
        $topic_id = isset($_POST["topic_id"]) ? intval($_POST["topic_id"]) :0;
        $subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : "";
        $content = isset($_POST["content"]) ? trim($_POST["content"]) : "";
        $cat_id = isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
        $topic_date = isset($_POST["topic_date"]) ? 
        intval( strtotime( @$_POST["topic_date"]["date"] ) + @$_POST["topic_date"]["time"] )
        : null;
        
        $topic_handler = xoops_getmodulehandler("topics");
        if ( $topic_id = $topic_handler->setTopics($topic_id,$cat_id,$xoopsUser->getVar("uid"),$subject,$content,$topic_date) ) {
            // set a user notes 2=>"notes"
            $_obj = $topic_handler->get($topic_id);
            $subject = $_obj->getVar("subject","n");
            $content = xoops_substr($_obj->getVar("content"),0,240);
            
            
        	redirect_header("index.php",3,_PRESS_SUCCESSFULLY);
        }
        redirect_header("edit.php",3,_PRESS_DATABASE_FAIL);
	break;
}
include "footer.php";
?>