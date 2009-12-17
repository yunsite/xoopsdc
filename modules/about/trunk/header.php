<?php
include '../../mainfile.php';
$xoopsOption['xoops_module_header'] = '<link rel="stylesheet" type="text/css" href="templates/style.css" />';

$xoBreadcrumbs = array();
$xoBreadcrumbs[] = array("title" => _YOURHOME, 'link' =>  XOOPS_URL);
$xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');

?>
