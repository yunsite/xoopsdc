<?php

if (count($xoBreadcrumbs) > 0) {
    $xoopsTpl->assign('xoBreadcrumbs', $xoBreadcrumbs);
}

$xoopsTpl->assign('user', $user);

include XOOPS_ROOT_PATH . "/footer.php";
?>
