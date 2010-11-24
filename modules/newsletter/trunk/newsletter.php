<?php
include_once 'header.php';
header("Content-Type:text/html; charset="._CHARSET);

$letter_id = isset($_REQUEST['letter_id']) ? intval($_REQUEST['letter_id']) : '';

$newsletter_handler = xoops_getmodulehandler('content','newsletter');

$letter_obj = $newsletter_handler->get($letter_id);

if(empty($letter_id) || !is_object($letter_obj)) redirect_header('index.php', 3, '您所訪問的電子報不存在！');

$letter = $letter_obj->getValues();
$letter['create_time'] = formatTimestamp($letter['create_time'], 'Y-m-d');

$tplName = dirname(__FILE__) . "/templates/newsletter_newsletter.html"; 

include_once XOOPS_ROOT_PATH . '/class/template.php';
$xoopsTpl = new XoopsTpl();
$xoopsTpl->assign('letter', $letter);
$xoopsTpl->assign('is_subscribe', $is_subscribe);
echo $xoopsTpl->fetch($tplName);
?>
