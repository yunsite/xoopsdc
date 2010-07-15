<?php
include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : '';
$service_id = isset($_REQUEST['service_id']) ? trim($_REQUEST['service_id']) : '';

$service_handler =& xoops_getmodulehandler('service', 'portfolio');

switch ($ac) {      
    case 'insert':
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.service.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        
        global $xoopsUser, $xoopsModule;
        include_once XOOPS_ROOT_PATH."/modules/portfolio/include/functions.php";
        
        if (isset($service_id)) {
            $service_obj =& $service_handler->get($service_id);
        }else {
            $service_obj =& $service_handler->create();
        }
        //assign value to elements of objects 
        foreach(array_keys($service_obj->vars) as $key) {
            if(isset($_POST[$key]) && $_POST[$key] != $service_obj->getVar($key)) {
                $service_obj->setVar($key, $_POST[$key]);
            }
        }
        //assign menu name
        if(empty($_POST['service_menu_name'])){
            $service_obj->setVar('service_menu_name', $_POST['service_name']);
        }

        //set submiter
        $service_obj->setVar('service_datetime', time());
        
        if(Portfoliomkdirs(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname())) $upload_path = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname();
        
        // upload image
        if (!empty($_FILES['service_image']['name'])) {
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
            $maxfilesize = 500000;
            $maxfilewidth = 1200;
            $maxfileheight = 1200;
            $uploader = new XoopsMediaUploader($upload_path, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                $uploader->setPrefix('service_');
                if (!$uploader->upload()) {
              	$error_upload = $uploader->getErrors();
                }elseif ( file_exists( $uploader->getSavedDestination() )) {
                    if ($service_obj->getVar("service_image")){
                        @unlink($upload_path . '/' . $service_obj->getVar("service_image"));
                    }
                    $service_obj->setVar('service_image', $uploader->getSavedFileName());
                }     
            }
        }
        
        // delete iamge
        if (isset($_POST['delete_image'])&&empty($_FILES['service_image']['name'])){
            @unlink($upload_path . '/' . $service_obj->getVar("service_image"));
            $service_obj->setVar('service_image', '');
        }
        
        // insert object  
        if ($service_handler->insert($service_obj)) {
            redirect_header('admin.service.php', 3, '保存成功'); 
        }

        loadModuleAdminMenu(2);
        echo $service_handler->getHtmlErrors();
        $format = "p";
        $action = 'action.service.php';
        $form = $service_obj->getForm($action);
        $form->display();
    break;
    
    case 'delete':
        $service_obj =& $service_handler->get($service_id);
        if(!is_object($service_obj) || empty($service_id)) redirect_header('admin.service.php', 3, '没有该服务!'); 
        $image = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . '/' . $service_obj->getVar("service_image");
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if($service_handler->delete($service_obj)) {
                if (file_exists($image)) { @unlink($image);}        
                redirect_header('admin.service.php', 3, '删除成功');
            }else{
                echo $service_obj->getHtmlErrors();
            }
        }else{
            xoops_confirm(array('ok' => 1, 'id' => $service_obj->getVar('service_id'), 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_PORTFOLIO_RUSUREDEL, $service_obj->getVar('service_menu_name')));
        }
    break;
    
    default:
        redirect_header('admin.service.php', 3, '没有该服务！'); 
    break;
}
include "footer.php";
?>
