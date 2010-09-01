<?php
include "header.php";
$myts =& MyTextSanitizer::getInstance();
$ac = isset($_GET["ac"]) ? $_GET["ac"] : "";
$id = isset($_GET["id"]) ? $_GET["id"] : 0;

$att_handler =& xoops_getmodulehandler('attachments', 'resources');
$downloads_handler = xoops_getmodulehandler('downloads', 'resources');

$downloads = $downloads_handler->XoopsZipDownloader(".zip","application/x-zip");
$files_dir = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() .'/';

if ( !empty($ac) && $ac == "all") {
    $criteria = new CriteriaCompo(new Criteria("res_id", $id));
    $att_objs = $att_handler->getAll($criteria);

    $att_name = time();
    if ( $att_objs ) {
        foreach ( $att_objs as $att_obj ) {
            $downloads = $downloads_handler->addFile($files_dir . $att_obj->getVar("att_attachment"), $att_obj->getVar("att_filename"));
            $att_obj->setVar("att_downloads",$att_obj->getVar("att_downloads") + 1 );
            $att_handler->insert($att_obj);
            unset($att_obj);
        }
    }
    
} else {
    $att_obj = $att_handler->get($id);
    $_file_name = $att_obj->getVar("att_filename");
    $att_name = substr( $_file_name , 0, strrpos( $_file_name, '.' ) );
    $downloads = $downloads_handler->addFile($files_dir . $att_obj->getVar("att_attachment"), stripslashes($att_obj->getVar("att_filename")));
    $att_obj->setVar("att_downloads", $att_obj->getVar("att_downloads") + 1 );
    $att_handler->insert($att_obj);
}
$downloads = $downloads_handler->download($att_name);

include 'footer.php';
?>
