<?php
include "header.php";
$myts =& MyTextSanitizer::getInstance();

$id = isset($_GET["id"]) ? $_GET["id"] : 0;

$attachment_handler = xoops_getmodulehandler("attachments");

$filepath = XOOPS_UPLOAD_PATH."/resources/";

$att_obj = $attachment_handler->get($id);

if ( empty($att_obj) || !is_object($att_obj) || $att_obj->isNew() ) {
    redirect_header("index.php");
}
$filename = $att_obj->getVar("att_filename","n");
$att_obj->setVar("att_downloads",$att_obj->getVar("att_downloads") + 1 );
$attachment_handler->insert($att_obj);

$path = $filepath . $att_obj->getVar("att_attachment");

$file = $path;

$ext = substr( $path, strrpos( $path, "." ) + 1 );
$types = include(dirname(__FILE__)."/include/mimetypes.inc.php");
$content_type = isset($types[$ext]) ? $types[$ext] : "text/plain";

header( "Content-type: {$content_type}; charset="._CHARSET);
header( "Content-Disposition: attachment; filename={$filename} ");
$handle = fopen($file, "rb");
while (!feof($handle)) {
   $buffer = fread($handle, 4096);
   echo $buffer;
}
fclose($handle); 

include 'footer.php';
?>