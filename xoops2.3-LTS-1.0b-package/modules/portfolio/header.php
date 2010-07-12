<?php

include '../../mainfile.php';

$xoBreadcrumbs = array();
$xoBreadcrumbs[] = array("title" => _YOURHOME, 'link' =>  XOOPS_URL);
$xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');

?>
