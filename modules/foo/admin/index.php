<?php
include 'header.php';
xoops_cp_header();

loadModuleAdminMenu(1, "");

    $xoopsTpl->assign('backend', _FOO_AM_BACKEND);
    $template_main = "foo_admin_index.html";
    
if (isset($template_main)) {
    $xoopsTpl->display("db:{$template_main}");
}
xoops_cp_footer();
?>