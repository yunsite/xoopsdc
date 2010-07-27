<?php
include 'header.php';
xoops_cp_header();

loadModuleAdminMenu(1, "");

$xoopsTpl->assign('backend', '歡迎使用客服模組後台管理');
$template_main = "support_admin_index.html";

include 'footer.php';
?>