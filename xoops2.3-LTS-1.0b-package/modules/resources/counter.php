<?php
include_once 'header.php';
include_once "include/functions.php";

$res_id = empty($_GET['res_id']) ? 0 : intval($_GET['res_id']);
$uid = (is_object($xoopsUser)) ? $xoopsUser->getVar("uid") : 0;
$ip = item_getIP();
if (empty($res_id)) return;
if (item_getcookie("res_" . $res_id) > 0) return;
$res_handler =& xoops_getmodulehandler('resources', 'resources');

$counter_handler =& xoops_getmodulehandler('rescounter', 'resources');
$counter_obj =& $counter_handler->create();
$counter_obj->setVar("res_id", $res_id );
$counter_obj->setVar("uid", $uid);
$counter_obj->setVar("ip", $ip);
$counter_obj->setVar("counter_time",  time());
$counter_handler->insert($counter_obj, true);


$res_obj =& $res_handler->get($res_id);
$res_obj->setVar( "res_counter", $res_obj->getVar("res_counter") + 1, true );
$res_handler->insert($res_obj, true);
item_setcookie("res_" . $res_obj, time());

return;
?>