<?php

if (count($xoBreadcrumbs) > 0) {
    $xoopsTpl->assign('xoBreadcrumbs', $xoBreadcrumbs);
}

// user upload
if($xoopsModuleConfig['is_uploader']) {
    $is_uploader = 0;
    if (!empty($xoopsUser)) $is_uploader = 1;
    $xoopsTpl->assign('is_uploader', $is_uploader);
}

include XOOPS_ROOT_PATH . "/footer.php";
?>
