<?php
include_once 'header.php';

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'exit';

$subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');
$subscribelog_handler = xoops_getmodulehandler('subscribelog','newsletter');

if(empty($xoopsUser)) redirect_header(XOOPS_URL . '/user.php', 3, '請您登錄后，再進行操作！');     

$criteria = new Criteria("uid", $xoopsUser->getVar('uid'));
$user_by_subscribe_obj = $subscribe_handler->getAll($criteria);

if(empty($user_by_subscribe_obj)) {
    $user_by_subscribe_obj = $subscribe_handler->create();
    $user_by_subscribe_obj->setVar('uid', $xoopsUser->getVar('uid'));
} else {
    $user_by_subscribe_obj = current($subscribe_handler->getAll($criteria));
}

switch ($op) {
    case 'add':
    if($user_by_subscribe_obj->getVar('is_subscribe') == 1) redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname(), 3, '您已訂閱了電子報！'); 
       
    $user_by_subscribe_obj->setVar('is_subscribe', 1);
    $user_by_subscribe_obj->setVar('updatetime', time());
    $subscribe_handler->insert($user_by_subscribe_obj);
    
    //log
    $subscribelog_obj = $subscribelog_handler->create();
    $subscribelog_obj->setVar('uid', $xoopsUser->getVar('uid'));
    $subscribelog_obj->setVar('subscribe_timestamp', time());
    $subscribelog_obj->setVar('subscribe_action', 1);
    $subscribelog_handler->insert($subscribelog_obj);
    
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname(), 3, '訂閱成功！');     
break;

    case 'delete':
    if($user_by_subscribe_obj->getVar('is_subscribe') == 0) redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname(), 3, '您已退訂了電子報！');   
    
    $user_by_subscribe_obj->setVar('is_subscribe', 0);
    $user_by_subscribe_obj->setVar('updatetime', time());
    $subscribe_handler->insert($user_by_subscribe_obj);
    
    //log
    $subscribelog_obj = $subscribelog_handler->create();
    $subscribelog_obj->setVar('uid', $xoopsUser->getVar('uid'));
    $subscribelog_obj->setVar('subscribe_timestamp', time());
    $subscribelog_obj->setVar('subscribe_action', 0);
    $subscribelog_handler->insert($subscribelog_obj);
    
    redirect_header('index.php', 3, '退訂成功！'); 
break;

    default:
    case 'exit':
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname());   
break;
}

include_once 'footer.php';
?>
