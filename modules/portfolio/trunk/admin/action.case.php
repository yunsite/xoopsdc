<?php
include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : '';
$case_id = isset($_REQUEST['case_id']) ? trim($_REQUEST['case_id']) : '';

$service_handler =& xoops_getmodulehandler('service', 'portfolio');
$case_handler =& xoops_getmodulehandler('case', 'portfolio');
$cs_handler =& xoops_getmodulehandler('cs', 'portfolio');
$images_handler =& xoops_getmodulehandler('images', 'portfolio');

switch ($ac) {      
    case 'insert':
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.case.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        
        global $xoopsUser, $xoopsModule;
        include_once XOOPS_ROOT_PATH."/modules/portfolio/include/functions.php";
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        
        if (isset($case_id)) {
            $case_obj =& $case_handler->get($case_id);
        }else {
            $case_obj =& $case_handler->create();
        }
        //assign value to elements of objects 
        foreach(array_keys($case_obj->vars) as $key) {
            if(isset($_POST[$key]) && $_POST[$key] != $case_obj->getVar($key)) {
                $case_obj->setVar($key, $_POST[$key]);
            }
        }
        //assign menu title
        if(empty($_POST['case_menu_title'])){
            $case_obj->setVar('case_menu_title', $_POST['case_title']);
        }

        //set submiter
        $case_obj->setVar('case_datetime', time());
        
        // upload image
        if(Portfoliomkdirs(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname())) $upload_path = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname();
        if (!empty($_FILES['case_image']['name'])) {
            $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
            $maxfilesize = 500000;
            $maxfilewidth = 1200;
            $maxfileheight = 1200;
            $uploader = new XoopsMediaUploader($upload_path, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                $uploader->setPrefix('case_');
                if (!$uploader->upload()) {
              	$error_upload = $uploader->getErrors();
                }elseif ( file_exists( $uploader->getSavedDestination() )) {
                    if ($case_obj->getVar("case_image")){
                        @unlink($upload_path . '/' . $case_obj->getVar("case_image"));
                    }
                    $case_obj->setVar('case_image', $uploader->getSavedFileName());
                }     
            }
            unset($_POST["xoops_upload_file"][0]);
        }
        
        // delete iamge
        if (isset($_POST['delete_image'])&&empty($_FILES['case_image']['name'])){
            @unlink($upload_path . '/' . $case_obj->getVar("case_image"));
            $case_obj->setVar('case_image', '');
        }
        
        
        // insert object  
        if ($case_id = $case_handler->insert($case_obj)) {
        
            //update services
            $insert_ids = array();
            $delete_ids = array();
            $old_ids = array();
            $service_ids = !empty($_POST["service_ids"]) ? $_POST["service_ids"] : array();
            if(!is_array($service_ids)) $service_ids = array($service_ids);
            
            $cs = $cs_handler->getServerIds(array($case_id));

            if(!empty($cs)) {
                foreach ($cs as $k=>$v) {
                    $old_ids[] = $v['service_id'];
                }
            }

            if(!empty($service_ids)) {
                foreach ($service_ids as $id) {
                    if (!in_array($id, $old_ids)) $insert_ids[] = $id;
                }
            }
        
            if(!empty($insert_ids)) {
                foreach ($insert_ids as $id) {
                    $cs_obj = $cs_handler->create();
                    $cs_obj->setVar('service_id', $id);
                    $cs_obj->setVar('case_id', $case_id);
                    $cs_handler->insert($cs_obj);
                    unset($cs_obj);
                }
            }
            if(!empty($old_ids)) {
                foreach ($old_ids as $id) {
                    if (!in_array($id, $service_ids)) $delete_ids[] = $id;
                }
            }

            if(!empty($delete_ids)) {
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria("service_id","(".implode(", ",$delete_ids). ")","in"), 'AND');
                $criteria->add(new Criteria('case_id', $case_id),'AND');
                $cs_handler->deleteAll($criteria, true);
            }
            
            //update gallery
            $image_titles = isset($_POST['image_title']) ? $_POST['image_title'] : '';
            $image_descs = isset($_POST['image_desc']) ? $_POST['image_desc'] : '';
            if(!empty($image_titles) && is_array($image_titles)) {
                foreach($image_titles as $img_id=>$img_title) {
                    $image_obj =& $images_handler->get($img_id);
                    if(is_object($image_obj)) {
                        $image_obj->setVar('image_title', $img_title);
                        $image_obj->setVar('image_desc', $image_descs[$img_id]);
                        $images_handler->insert($image_obj); 
                        unset($image_obj);
                    }
                    
                }
            }

            if ( !empty($_POST["xoops_upload_file"]) ){ 
                if(Portfoliomkdirs(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . "/gallery")) $files_dir = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . "/gallery";
                $mid_wh = array(360,360);
                $thumb_wh = array(150,120);
                $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
                $maxfilesize = 50000000;
                $maxfilewidth = 1200;
                $maxfileheight = 1200; 
                $uploader = new XoopsMediaUploader($files_dir, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
                foreach($_POST["xoops_upload_file"] as $k=>$v){ 
                  if ($uploader->fetchMedia($v)) {
                  $uploader->setPrefix('case_gallery_');
                      if (!$uploader->upload()) {
                          echo $uploader->getErrors();
                      } else {       
                          $image_obj =& $images_handler->create();
                          $image_obj->setVar('case_id', $case_id);
                          $image_obj->setVar('image_title', $_POST["xoops_upload_file_name"][$k-1]);
                          $image_obj->setVar('image_desc', $_POST["xoops_upload_file_desc"][$k-1]);
                          $image_obj->setVar('image_file', $uploader->getSavedFileName());
                          $images_handler->insert($image_obj); 
                          setImageThumb($files_dir."/", $uploader->getSavedFileName(), $files_dir."/", 'mid_'.$uploader->getSavedFileName(), array($mid_wh[0], $mid_wh[1]));
                          setImageThumb($files_dir."/", $uploader->getSavedFileName(), $files_dir."/", 'thumb_'.$uploader->getSavedFileName(), array($thumb_wh[0], $thumb_wh[1]));
                          
                       }
                       unset($pic_obj);
                   }
                }
            }
            
            $del_image_ids = !empty($_POST['del_image_ids']) ? $_POST['del_image_ids'] :'';
            if(!empty($del_image_ids)){
                if(is_array($del_image_ids)) $del_image_ids = implode(',', $del_image_ids);
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('case_id', $case_id), 'AND'); 
                $criteria->add(new Criteria("image_id","(".$del_image_ids.")","in"));
                $images = $images_handler->getAll($criteria, array('image_id','image_file'), false); 
                if($images_handler->deleteAll($criteria)){    
                    foreach($images as $k=>$v){
                        unlink(str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . "/gallery/" . $v['image_file'])));
                        unlink(str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . "/gallery/mid_" . $v['image_file'])));
                        unlink(str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . "/gallery/thumb_" . $v['image_file'])));
                    }
                }
            }
            
            redirect_header('admin.case.php', 3, '保存成功'); 
        }

        loadModuleAdminMenu(3);
        echo $case_handler->getHtmlErrors();
        $format = "p";
        $action = 'action.case.php';
        $form = $case_obj->getForm($action);
        $form->display();
    break;
    
    case 'delete':
        $case_obj =& $case_handler->get($case_id);
        if(!is_object($case_obj) || empty($case_id)) redirect_header('admin.case.php', 3, '没有该案例!'); 
        $image = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . '/' . $case_obj->getVar("case_image");
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if($case_handler->delete($case_obj)) {
                if (file_exists($image)) { @unlink($image);}        
                redirect_header('admin.case.php', 3, '保存成功');
            }else{
                echo $case_obj->getHtmlErrors();
            }
        }else{
            xoops_confirm(array('ok' => 1, 'id' => $case_obj->getVar('case_id'), 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_PORTFOLIO_RUSUREDEL, $case_obj->getVar('case_menu_title')));
        }
    break;
    
    default:
        redirect_header('admin.case.php', 3, '没有该服务！'); 
    break;
}
include "footer.php";
?>
