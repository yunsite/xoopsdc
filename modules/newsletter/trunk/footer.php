<?php

if (count($xoBreadcrumbs) > 0) {
    $xoopsTpl->assign('xoBreadcrumbs', $xoBreadcrumbs);
}

$xoopsTpl->assign('is_subscribe', $is_subscribe);

include XOOPS_ROOT_PATH . "/footer.php";
?>
