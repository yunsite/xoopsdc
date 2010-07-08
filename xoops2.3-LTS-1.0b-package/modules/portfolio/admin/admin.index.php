<?php
include 'header.php';
xoops_cp_header();

loadModuleAdminMenu(1, "");

$xoopsTpl->assign('backend', '欢迎使用案例模块后台管理');
$template_main = "portfolio_admin_index.html";
    
include 'footer.php';
?>
