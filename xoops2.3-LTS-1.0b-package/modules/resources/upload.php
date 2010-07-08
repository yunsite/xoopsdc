<?php
include_once 'header.php';

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'upload';
$res_id = isset($_REQUEST["res_id"]) ? trim($_REQUEST["res_id"]) : '';

// Get handler
$category_handler =& xoops_getmodulehandler('category', 'resources');
$resources_handler =& xoops_getmodulehandler('resources', 'resources');
$att_handler =& xoops_getmodulehandler('attachments', 'resources');

if($xoopsModuleConfig['is_uploader']) {
    if (empty($xoopsUser)) redirect_header('index.php', 3, '您没有发布资源的权限！');
} else{
    redirect_header('index.php', 3, '您没有发布资源的权限！');
}

switch ($op) {
    default:
    case 'upload':
    $xoopsOption['template_main'] = 'resources_form.html';
    include XOOPS_ROOT_PATH.'/header.php';
    
    if (!empty($res_id)) {
        $res_obj =& $resources_handler->get($res_id);
        if(!is_object($res_obj) || empty($res_id) || ($res_obj->getVar('uid') != $xoopsUser->getVar("uid"))) redirect_header('index.php', 3, '没有该资源！');
    }else {
        $res_obj =& $resources_handler->create();
    }
    $action = 'upload.php?op=save';
    $form = $res_obj->getForm($action);
    $form->assign($xoopsTpl);

    $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
    $xoBreadcrumbs[] = array("title" => "发布资源");
    break;
    
    case 'save':
    include XOOPS_ROOT_PATH.'/header.php';
    if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));

    if (!empty($res_id)) {
        $res_obj =& $resources_handler->get($res_id);
        if(!is_object($res_obj) || empty($res_id) || ($res_obj->getVar('uid') != $xoopsUser->getVar("uid"))) redirect_header('detail.php?res_id='.$res_id, 3, '您没有发布资源的权限！');
    } else {
        $res_obj =& $resources_handler->create();
    }
    
    //assign value to elements of objects 
    foreach(array_keys($res_obj->vars) as $key) {
        if(isset($_POST[$key]) && $_POST[$key] != $res_obj->getVar($key)) {
            $res_obj->setVar($key, $_POST[$key]);
        }
    }
    
    $res_obj->setVar('update_time', time());
    if (!empty($xoopsUser)) $res_obj->setVar('uid', $xoopsUser->getVar("uid"));
    
    // insert object  
    if ($res_id = $resources_handler->insert($res_obj)) {
    
        // upload annex
            $att_num = $res_obj->getVar('res_attachment');
            if ( !empty($_POST["xoops_upload_file"]) ){
                include_once XOOPS_ROOT_PATH."/modules/resources/include/functions.php";
                include_once XOOPS_ROOT_PATH."/class/uploader.php";
                
                if(Resourcesmkdirs(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname())) $files_dir = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname();
                $allowed_mimetypes = $att_handler->getTypes();
                $maxfilesize = 50000000;
                $uploader = new XoopsMediaUploader($files_dir, $allowed_mimetypes, $maxfilesize);
                foreach($_POST["xoops_upload_file"] as $k=>$v){ 
                    if ($uploader->fetchMedia($v)) {
                    $uploader->setPrefix('support_');
                        if (!$uploader->upload()) {
                            echo $uploader->getErrors();
                        } else {
                            $att_obj =& $att_handler->create();
                            $att_obj->setVar('res_id', $res_id);
                            if (!empty($xoopsUser)) $att_obj->setVar('uid', $xoopsUser->getVar("uid"));
                            $att_obj->setVar("att_filename",$uploader->getMediaName());
                      			$att_obj->setVar("att_attachment",$uploader->getSavedFileName());
                      			$att_obj->setVar("att_type",$uploader->getMediaType());
                      			$att_obj->setVar("att_size",$uploader->getMediaSize());
                      			$att_obj->setVar("grate_time",time());
                      			$att_obj->setVar("update_time",time());
                            $att_handler->insert($att_obj);
                            $att_num = $att_num + 1;
                         }
                         unset($att_obj);
                     }
                }
                
                //update resources res_attachment
                $res_obj =& $resources_handler->get($res_id);
                $res_obj->setVar('res_attachment', $att_num);
                $resources_handler->insert($res_obj);
            }
        redirect_header('detail.php?res_id='.$res_id, 3, '保存成功'); 
    } else {
        redirect_header('detail.php?res_id='.$res_id, 3, '保存有误，请联系管理员！'); 
    }

    break;
}
$xoopsOption['xoops_pagetitle'] = $xoopsModule->getVar('name');

include_once 'footer.php';
?>
