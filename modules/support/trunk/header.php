<?php

include '../../mainfile.php';
$member_handler =& xoops_gethandler('member');  

if (empty($xoopsUser)) {
    redirect_header(XOOPS_URL . '/user.php', 3, _MA_SUPPORT_LOGIN);
    exit();
}

$user = array(
        'uid'        => $xoopsUser->getVar('uid'),
        'uname'      => $xoopsUser->getVar('uname'),
        'level'      => 'customer',
        'permission' => array('create', 'read', 'action', 'reply', 'reject', 'forword', 'finish', 'close', 'lgnore')
    );
    
$support_manager_uids = $member_handler->getUsersByGroup($xoopsModuleConfig['support_manager']);
$support_uids = $member_handler->getUsersByGroup($xoopsModuleConfig['support']);

if(in_array($user['uid'], $support_uids)) $user['level'] = 'support';
if(in_array($user['uid'], $support_manager_uids)) {
    $user['level'] = 'manager';
    array_push($user['permission'], 'edit', 'shield');
} 

$xoBreadcrumbs = array();
$xoBreadcrumbs[] = array("title" => _YOURHOME, 'link' =>  XOOPS_URL);

$status = array(
    'create'  => _MA_SUPPORT_QUESTIONSUMBIT,
    'read'    => _MA_SUPPORT_READ,
    'action'  => _MA_SUPPORT_DOING, 
    'reply'   => _MA_SUPPORT_QUESTIONREPLY,
    'reject'  => _MA_SUPPORT_USERFEEDBACK,
    'forword' => _MA_SUPPORT_QUESTIONFORWARD,
    'finish'  => _MA_SUPPORT_DONE, 
    'close'   => _MA_SUPPORT_CLOSE, 
    'lgnore'  => _MA_SUPPORT_IGNORE,
    'hand'    => _MA_SUPPORT_QUESTIONRESUMBIT        
);

$status_info = array(
    'create'  => _MA_SUPPORT_SUBMITINFO_UNSURE,
    'read'    => _MA_SUPPORT_INFOREAD_DOING,
    'action'  => _MA_SUPPORT_INFOACTON_WAITING, 
    'reply'   => '',
    'reject'  => _MA_SUPPORT_USERRESUBMIT,
    'forword' => _MA_SUPPORT_QUESTIONFORWARDOTHER,
    'finish'  => _MA_SUPPORT_QUESTIONFINISH, 
    'close'   => _MA_SUPPORT_QUESTIONCLOSE, 
    'lgnore'  => _MA_SUPPORT_QUESTIONIGNORE,
    'hand'    => ''   
);
//juan
if($xoopsModuleConfig['timeformat'] == '1'){
    $date = _MA_SUPPORT_TIMEFORMATC;
}elseif($xoopsModuleConfig['timeformat'] == '2'){
    $date = _MA_SUPPORT_TIMEFORMATE;
}elseif($xoopsModuleConfig['timeformat'] == '3'){
    $date = _MA_SUPPORT_TIMEFORMATS;
}elseif($xoopsModuleConfig['timeformat'] == '4'){
    $date = _MA_SUPPORT_TIMEFORMATL;
}else{
    $date = '';
}
?>
