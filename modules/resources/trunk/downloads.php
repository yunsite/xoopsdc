<?php
include "header.php";

$id = isset($_GET["id"]) ? trim($_GET["id"]) : 0;

$att_handler =& xoops_getmodulehandler('attachments', 'resources');

$file_dir = XOOPS_UPLOAD_PATH."/resources/";

$att_obj = $att_handler->get($id);
if (!is_object($att_obj) || empty($id)) redirect_header("index.php");

$filename = $att_obj->getVar("att_filename", "n");
$att_obj->setVar("att_downloads", $att_obj->getVar("att_downloads") + 1 );
$att_handler->insert($att_obj);

$file = $file_dir . $att_obj->getVar("att_attachment");

$ext = substr( $file, strrpos( $file, "." ) + 1 );
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
