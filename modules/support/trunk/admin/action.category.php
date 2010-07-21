<?php
include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : '';
$cat_id = isset($_REQUEST['cat_id']) ? trim($_REQUEST['cat_id']) : '';

$category_handler = xoops_getmodulehandler('category','support');
$linkusers_handler =& xoops_getmodulehandler('linkusers','support');

switch ($ac) {      
    case 'insert':
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        
        global $xoopsUser, $xoopsModule;
        include_once XOOPS_ROOT_PATH."/modules/support/include/functions.php";
        
        if (isset($cat_id)) {
            $cat_obj =& $category_handler->get($cat_id);
        }else {
            $cat_obj =& $category_handler->create();
        }
        //assign value to elements of objects 
        foreach(array_keys($cat_obj->vars) as $key) {
            if(isset($_POST[$key]) && $_POST[$key] != $cat_obj->getVar($key)) {
                $cat_obj->setVar($key, $_POST[$key]);
            }
        }
            
        if(Supportmkdirs(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname())) $upload_path = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname();
        
        // upload image
        if (!empty($_FILES['cat_image']['name'])) {
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
            $maxfilesize = 500000;
            $maxfilewidth = 1200;
            $maxfileheight = 1200;
            $uploader = new XoopsMediaUploader($upload_path, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                $uploader->setPrefix('cat_');
                if (!$uploader->upload()) {
              	$error_upload = $uploader->getErrors();
                }elseif ( file_exists( $uploader->getSavedDestination() )) {
                    if ($cat_obj->getVar("cat_image")){
                        @unlink($upload_path . '/' . $cat_obj->getVar("cat_image"));
                    }
                    $cat_obj->setVar('cat_image', $uploader->getSavedFileName());
                }     
            }
        }
        
        // delete iamge
        if (isset($_POST['delete_image'])&&empty($_FILES['cat_image']['name'])){
            @unlink($upload_path . '/' . $cat_obj->getVar("cat_image"));
            $cat_obj->setVar('cat_image', '');
        }
        
        
        
        // insert object  
        if ($cat_id = $category_handler->insert($cat_obj)) {
        
            //insert support
            $insert_ids = array();
            $delete_ids = array();
            $old_ids = array();
            $support_ids = !empty($_POST["support_ids"]) ? $_POST["support_ids"] : array();
            if(!is_array($support_ids)) $support_ids = array($support_ids);
            
            $linkusers = $linkusers_handler->getUids(array($cat_id));

            if(!empty($linkusers)) {
                foreach ($linkusers as $k=>$v) {
                    $old_ids[] = $v['uid'];
                }
            }

            if(!empty($support_ids)) {
                foreach ($support_ids as $id) {
                    if (!in_array($id, $old_ids)) $insert_ids[] = $id;
                }
            }
        
            if(!empty($insert_ids)) {
                foreach ($insert_ids as $id) {
                    $link_obj = $linkusers_handler->create();
                    $link_obj->setVar('cat_id', $cat_id);
                    $link_obj->setVar('uid', $id);
                    $linkusers_handler->insert($link_obj);
                    unset($link_obj);
                }
            }
            if(!empty($old_ids)) {
                foreach ($old_ids as $id) {
                    if (!in_array($id, $support_ids)) $delete_ids[] = $id;
                }
            }

            if(!empty($delete_ids)) {
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria("uid","(".implode(", ",$delete_ids). ")","in"), 'AND');
                $criteria->add(new Criteria('cat_id', $cat_id),'AND');
                $linkusers_handler->deleteAll($criteria, true);
            }
            
            redirect_header('admin.category.php', 3, '保存成功'); 
        }

        loadModuleAdminMenu(2);
        echo $category_handler->getHtmlErrors();
        $format = "p";
        $action = 'action.category.php';
        $form = $cat_obj->getForm($action);
        $form->display();
    break;
    
    case 'delete':
        $cat_obj =& $category_handler->get($cat_id);
        if(!is_object($cat_obj) || empty($cat_id)) redirect_header('admin.category.php', 3, '没有该分类!'); 
        $image = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . '/' . $cat_obj->getVar("cat_image");
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if($category_handler->delete($cat_obj)) {
                if (file_exists($image)) { @unlink($image);}        
                redirect_header('admin.category.php', 3, '保存成功');
            }else{
                echo $cat_obj->getHtmlErrors();
            }
        }else{
            xoops_confirm(array('ok' => 1, 'id' => $cat_obj->getVar('cat_id'), 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_PORTFOLIO_RUSUREDEL, $cat_obj->getVar('cat_name')));
        }
    break;
    
    default:
        redirect_header('admin.category.php', 3, '没有该分类！'); 
    break;
}
include "footer.php";
?>
