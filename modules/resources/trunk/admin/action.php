<?php
include "header.php";
$op = isset($_POST["op"]) ? $_POST["op"] : "list";
switch ($op) {
	case "savecategory":
		if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("category.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
		$cat_id = isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$category_handler = xoops_getmodulehandler("category");
		if ( empty($cat_id) ) {
			$cat_obj = $category_handler->get();
		} else {
			$cat_obj = $category_handler->get($cat_id);
		}
		if ( $cat_obj->getVar("cat_name") != trim($_POST["cat_name"]) ) {
			$cat_obj->setVar("cat_name",trim($_POST["cat_name"]));
			if ( $category_handler->insert( $cat_obj ) ) {
				$stop = "保存成功";
			} else {
				$stop = "保存失败";
			}
		} else {
			if ( !trim($_POST["cat_name"]) ) {
				$stop = "分类名称有误";
			} else {
				$stop = "修改名与原分类名相同";
			}
		}
		redirect_header("category.php",5,$stop);
	break;
	
	case "delcat":
		$cat_id = isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$cat_handler = xoops_getmodulehandler("category");	
		if ( true === $cat_handler->delCategory($cat_id) ){
			redirect_header("category.php",5,"保存成功");
		} else {
			redirect_header("category.php",5,"保存失败");
		}	
	break;
	
	case "saveresources":
		if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("index.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
            exit();
        }
        $resources_handler = xoops_getmodulehandler("resources");
        if ( $resources_id = $resources_handler->setresources() ) {
        	$attachments_handler = xoops_getmodulehandler("attachments");
        	$attachments_handler->setAttachments($resources_id);
        	$stop = "ok";
        } else {
        	$stop = "no";
        }
        redirect_header("index.php",5,$stop);
	break;
}
include "footer.php";
?>