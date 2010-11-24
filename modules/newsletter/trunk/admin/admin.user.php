<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(5, "");

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';

$subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');
$subscribelog_handler = xoops_getmodulehandler('subscribelog','newsletter');
$member_handler =& xoops_gethandler('member');

switch ($op) {
default:
case 'list':
    $users = $subscribe_handler->getSubscribeUsers(1, true);
    
    if(!empty($users)) {
        foreach ($users as $k=>$v) $user_ids[] = $v['uid'];
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("uid","(".implode(", ",$user_ids). ")","in"), 'AND');
        $user_objs = $member_handler->getUsers($criteria);
        foreach ($users as $k=>$v) {
            $users[$k]['updatetime'] = formatTimestamp($v['updatetime']);;
            foreach ($user_objs as $obj) {
                if($v['uid'] == $obj->getVar('uid')) {
                    $users[$k]['uname'] = $obj->getVar('uname');
                    $users[$k]['email'] = $obj->getVar('email');
                }
            }
        }
    }
    
    $xoopsTpl->assign('users', $users);
    $xoopsTpl->display("db:newsletter_admin_user.html");
    break;
    
case 'search':
    $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : '';
    

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('uname', '%'.$query.'%', 'like'), 'AND');
    $user_objs = $member_handler->getUsers($criteria);
    
    $users = '';
    if(!empty($user_objs)) {
        foreach ($user_objs as $k=>$v) {
            $user_ids[] = $v->getVar('uid');
        }
        
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("uid","(".implode(", ",$user_ids). ")","in"), 'AND');
        $criteria = new Criteria('is_subscribe', 1);
        $users = $subscribe_handler->getAll($criteria, null, false);
        
        if(!empty($users)) {
            foreach ($users as $k=>$v) {
                $users[$k]['updatetime'] = formatTimestamp($v['updatetime']);;
                foreach ($user_objs as $obj) {
                    if($v['uid'] == $obj->getVar('uid')) {
                        $users[$k]['uname'] = $obj->getVar('uname');
                        $users[$k]['email'] = $obj->getVar('email');
                    }
                }
            }
        }
    }
    $xoopsTpl->assign('users', $users);
    $xoopsTpl->display("db:newsletter_admin_user.html");
    break;
    
case 'delete':
    $criteria = new Criteria("uid", $uid); 
    $user_by_subscribe_obj = $subscribe_handler->getAll($criteria);
    $user_by_subscribe_obj = current($subscribe_handler->getAll($criteria));

    if($user_by_subscribe_obj->getVar('is_subscribe') == 0) redirect_header('admin.user.php', 3, '該用戶已退訂了電子報！');   
    
    $user_by_subscribe_obj->setVar('is_subscribe', 0);
    $user_by_subscribe_obj->setVar('updatetime', time());
    $subscribe_handler->insert($user_by_subscribe_obj);
    
    //log
    $subscribelog_obj = $subscribelog_handler->create();
    $subscribelog_obj->setVar('uid', $uid);
    $subscribelog_obj->setVar('subscribe_timestamp', time());
    $subscribelog_obj->setVar('subscribe_action', 0);
    $subscribelog_handler->insert($subscribelog_obj);
    
    redirect_header('admin.user.php', 3, '操作已成功！'); 
    break;
}

include 'footer.php';

?>
