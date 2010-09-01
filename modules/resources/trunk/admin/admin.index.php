<?php
include 'header.php';
xoops_cp_header();

loadModuleAdminMenu(1, "");

$xoopsTpl->assign('backend', '欢迎使用资源模块后台管理');
$template_main = "resources_admin_index.html";

include 'footer.php';
?>
