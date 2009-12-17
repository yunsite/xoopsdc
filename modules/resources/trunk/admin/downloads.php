<?php
include "header.php";
$myts =& MyTextSanitizer::getInstance();
$ac = isset($_GET["ac"]) ? $_GET["ac"] : "";
$id = isset($_GET["id"]) ? $_GET["id"] : 0;

$attachment_handler = xoops_getmodulehandler("attachments");

$filepath = XOOPS_VAR_PATH."/resources/";

$downloads_handler = xoops_getmodulehandler("downloads");

$downloads = $downloads_handler->XoopsZipDownloader(".zip","application/x-zip");


if ( !empty($ac) && $ac == "all") {
    $criteria = new CriteriaCompo(new Criteria("resources_id", $id));
    $att_objs = $attachment_handler->getAll($criteria);
    $att_name = time();
    if ( $att_objs ) {
        foreach ( $att_objs as $att_obj ) {
            $downloads = $downloads_handler->addFile($filepath.$att_obj->getVar("att_attachment"),$att_obj->getVar("att_filename"));
            $att_obj->setVar("att_downloads",$att_obj->getVar("att_downloads") + 1 );
            $attachment_handler->insert($att_obj);
        }
    }
    
} else {
    $att_obj = $attachment_handler->get($id);
    $_file_name = $att_obj->getVar("att_filename");
    $att_name = substr( $_file_name , 0, strrpos( $_file_name, '.' ) );
    $downloads = $downloads_handler->addFile($filepath.$att_obj->getVar("att_attachment"),stripslashes($att_obj->getVar("att_filename")));
    $att_obj->setVar("att_downloads",$att_obj->getVar("att_downloads") + 1 );
    $attachment_handler->insert($att_obj);
}
$downloads = $downloads_handler->download($att_name);

include 'footer.php';
?>