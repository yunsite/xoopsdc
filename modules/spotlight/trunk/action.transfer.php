<?php

 
include '../../mainfile.php';
require_once XOOPS_ROOT_PATH . "/Frameworks/transfer/transfer.php";
Transfer::load_language("spotlight");
$transfer_config = XOOPS_ROOT_PATH . "/Frameworks/transfer/spotlight/config.php";
$myts =& MyTextSanitizer::getInstance();

$page_handler =& xoops_getmodulehandler('page','spotlight');
$sp_handler =& xoops_getmodulehandler('spotlight','spotlight');
$sp_id = isset($_REQUEST['sp_id']) ? $_REQUEST['sp_id'] : '';
$page_obj =& $page_handler->create();

foreach(array_keys($page_obj->vars) as $key) {
    if(isset($_POST[$key]) && $_POST[$key] != $page_obj->getVar($key)) {
        $page_obj->setVar($key, $_POST[$key]);
    }
}

$published["date"] = isset($_POST["published"]["date"]) ? strtotime($_POST["published"]["date"]) : 0 ;
$page_obj->setVar('published', $published["date"] + $_POST["published"]["time"]);

if(!empty($_POST["xoops_upload_file"])){

    include_once dirname(__FILE__) . '/include/functions.php';
    include_once XOOPS_ROOT_PATH."/class/uploader.php";
    $upload_patch = spotlight_mkdirs( XOOPS_ROOT_PATH . $xoopsModuleConfig['spotlight_images'] );
    $sp_obj = $sp_handler->get($sp_id);
    $component = $sp_obj->getVar('component_name');
    include_once dirname(__FILE__) . "/components/{$component}/config.php";
    if(!isset($config['image_size'])) $config['image_size'] = '550|280';
    if(!isset($config['thumbs_size'])) $config['thumbs_size'] = '90|56';
    $image_wh = explode('|', $config['image_size']);
    $thumb_wh = explode('|', $config['thumbs_size']);

    $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
    $uploader = new XoopsMediaUploader($upload_patch, $allowed_mimetypes, $xoopsModuleConfig['upload_size'], 1200, 1200);   
           
    if ($uploader->fetchMedia('page_image')) {
        $uploader->setPrefix('page_');
        if (!$uploader->upload()) {
            $error = $uploader->getErrors();
            redirect_header($page_obj->getVar('page_link'), 3, _AM_SPOTLIGHT_IMEGES_TYPE_WRONG);
        } else {
            spotlight_cutphoto($upload_patch . $uploader->getSavedFileName(), $upload_patch . 'image_'.$uploader->getSavedFileName(), $image_wh[0], $image_wh[1]);
            spotlight_cutphoto($upload_patch . $uploader->getSavedFileName(), $upload_patch . 'thumb_'.$uploader->getSavedFileName(), $thumb_wh[0], $thumb_wh[1]);
            $page_obj->setVar('page_image', $uploader->getSavedFileName());
            if(!empty($page_image)){
                unlink($upload_patch.$page_image);
                unlink($upload_patch.'image_'.$page_image);
                unlink($upload_patch.'thumb_'.$page_image);
            }
        }
    }
                   
}
$page_handler->insert($page_obj);
$message = '添加成功!';
include XOOPS_ROOT_PATH . "/header.php";
echo "<div class=\"resultMsg\">" . $message;
echo "<br clear=\"all\" /><br /><input type=\"button\" value=\"" . _CLOSE . "\" onclick=\"window.close()\"></div>";

include XOOPS_ROOT_PATH . "/footer.php";

?>