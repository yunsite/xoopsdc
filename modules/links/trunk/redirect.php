<?php
include "header.php";
$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';

if (empty($url)) {
    redirect_header($_SERVER['HTTP_REFERER'], 5, implode("<br />", $GLOBALS["xoopsSecurity"]->getErrors()) );
}else{
    header("location: $url");
}

include XOOPS_ROOT_PATH.'/header.php';
include 'footer.php';
?>
