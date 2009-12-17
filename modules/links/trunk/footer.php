<?php

if (count($xoBreadcrumbs) > 1) {
    $xoopsTpl->assign('xoBreadcrumbs', $xoBreadcrumbs);
}
include XOOPS_ROOT_PATH . "/footer.php";
?>
