<?php
include '../../mainfile.php';

$subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');

$user = '';
if (!empty($xoopsUser)) {
    $user = array(
        'uid'          => $xoopsUser->getVar('uid'),
        'name'         => $xoopsUser->getVar('name'),
        'uname'        => $xoopsUser->getVar('uname'),
        'is_subscribe' => 0,
        'isAdmin'      => $xoopsUser->isAdmin()
    );
// 整理代码
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('uid', $xoopsUser->getVar('uid')));
    $criteria->add(new Criteria('is_subscribe', 1));
    if( $subscribe_handler->getCount($criteria) ) $user['is_subscribe'] = 1;
}

$is_subscribe = 0;

if (!empty($xoopsUser)) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('uid', $xoopsUser->getVar('uid')));
    $criteria->add(new Criteria('is_subscribe', 1));
    if( $subscribe_handler->getCount($criteria) ) $is_subscribe = 1;
}

$xoBreadcrumbs = array();
$xoBreadcrumbs[] = array("title" => _YOURHOME, 'link' =>  XOOPS_URL);
$xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');

?>
