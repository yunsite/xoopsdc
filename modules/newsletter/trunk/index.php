<?php
include_once 'header.php';
include_once "include/functions.render.php";

$newsletter_handler = xoops_getmodulehandler('content','newsletter');
$criteria = new CriteriaCompo();
$criteria->setSort('create_time');
$criteria->setOrder('desc');
$newsletters = $newsletter_handler->getAll($criteria, array('letter_title', 'create_time'), false);

if(!empty($newsletters)) {
    foreach ($newsletters as $k=>$v) {
        $newsletters[$k]['create_time'] = formatTimestamp($v['create_time'], 'Y-m-d');
    }
}

$xoopsOption['template_main'] = 'newsletter_index.html';
include XOOPS_ROOT_PATH . '/header.php';

$xoopsTpl->assign('newsletters', $newsletters);

include_once 'footer.php';
?>
