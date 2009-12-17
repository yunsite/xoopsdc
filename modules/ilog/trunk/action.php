<?php

include 'header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['id']) ? "edit" : 'new');

$handler =& xoops_getmodulehandler('article');
switch($op) {
    default:
    case "new":
    $xoopsOption['template_main'] = "ilog_form.html";
    include XOOPS_ROOT_PATH . '/header.php';
    $obj =& $handler->create();
    $form = $obj->getForm();
    $form->assign($xoopsTpl);
    break;
    
    case "edit":
    $xoopsOption['template_main'] = "ilog_form.html";
    include XOOPS_ROOT_PATH . '/header.php';
    $obj = $handler->get($_REQUEST['id']);
    $form = $obj->getForm();
    $form->assign($xoopsTpl);
    break;

    case "save":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }
 
    if (isset($_REQUEST['id'])) {
        $obj =& $handler->get($_REQUEST['id']);
        $obj->setVar('summary', $_REQUEST["summary"]);       
        }else{
        $obj =& $handler->create();
        $obj->setVar('uid', $_REQUEST['uid']);
        $obj->setVar('uname', $_REQUEST['uname']);
        $obj->setVar("time_create", time());
        $obj->setVar("time_publish", time());
        global  $xoopsModuleConfig;
        $summary = trim(strip_tags($_REQUEST["summary"]));
        if(empty($summary) && $contentSubStr = html_substr($_REQUEST["text_body"],$xoopsModuleConfig['summary'])){
            $obj->setVar('summary', $contentSubStr);
        }else{
            $obj->setVar('summary', $_REQUEST["summary"]);
        }  
    }
    
	$obj->setVar('status', $_REQUEST['status']);
    $obj->setVar('keywords', $_REQUEST['keywords']);
    $obj->setVar('title', $_REQUEST['title']);
    $obj->setVar('text_body', $_REQUEST['text_body']);
    
   
    if ($handler->insert($obj)) {
    $tag_handler = xoops_getmodulehandler('tag', 'tag');
    $itemid = $obj->getVar('id');
    $tag_handler->updateByItem($_POST["keywords"], $itemid, $xoopsModule->getVar("dirname"), $catid = 0);
        redirect_header('index.php', 3, sprintf(_MD_GUESTBOOK_SAVEDSUCCESS));
    }
   include XOOPS_ROOT_PATH . '/header.php';
    echo $handler->getHtmlErrors();
    $form =& $handler->getForm();
    $form->display();
    
    break;

    
    case "delete":
    include XOOPS_ROOT_PATH . '/header.php';
    $obj =& $handler->get($_REQUEST['id']);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($handler->delete($obj)) {
            redirect_header('index.php', 3, sprintf(_MD_GUESTBOOK_DELDSUCCESS, _MD_GUESTBOOK_INSERT));
        } else {
            echo $handler->getHtmlErrors();
        }
    } else {
        xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_MD_GUESTBOOK_DELDSUCCESS, $obj->getVar('cat_title')));
    }
    break;
}

include "footer.php";
?>
