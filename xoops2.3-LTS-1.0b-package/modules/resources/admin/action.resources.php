<?php
include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : '';
$res_id = isset($_REQUEST['res_id']) ? trim($_REQUEST['res_id']) : '';
$att_id = isset($_REQUEST['att_id']) ? trim($_REQUEST['att_id']) : '';

$resources_handler = xoops_getmodulehandler('resources','resources');
$att_handler =& xoops_getmodulehandler('attachments', 'resources');

switch ($ac) {      
    case 'insert':
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.resources.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        
        global $xoopsUser, $xoopsModule;
        include_once XOOPS_ROOT_PATH."/modules/resources/include/functions.php";
        
        if (isset($res_id)) {
            $res_obj =& $resources_handler->get($res_id);
        }else {
            $res_obj =& $resources_handler->create();
        }
        //assign value to elements of objects 
        foreach(array_keys($res_obj->vars) as $key) {
            if(isset($_POST[$key]) && $_POST[$key] != $res_obj->getVar($key)) {
                $res_obj->setVar($key, $_POST[$key]);
            }
        }
        
        $res_obj->setVar('update_time', time());        
        
        // insert object  
        if ($res_id = $resources_handler->insert($res_obj)) {
        
            // upload annex
                $att_num = $res_obj->getVar('res_attachment');
                if ( !empty($_POST["xoops_upload_file"]) ){
                
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
                              $att_num = $att_num+1;
                           }
                           unset($att_obj);
                       }
                    }
                    
                    //update resources res_attachment
                    $res_obj =& $resources_handler->get($res_id);
                    $res_obj->setVar('res_attachment', $att_num);
                    $resources_handler->insert($res_obj);
                }
            redirect_header('admin.resources.php', 3, '保存成功'); 
        }

        loadModuleAdminMenu(3);
        echo $resources_handler->getHtmlErrors();
        $format = "p";
        $action = 'action.resources.php?ac=insert';
        $form = $cat_obj->getForm($action);
        $form->display();
    break;
    
    case 'delete':
        $res_obj =& $resources_handler->get($res_id);
        if(!is_object($res_obj) || empty($res_id)) redirect_header('admin.resources.php', 3, '没有该资源!'); 
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('res_id', $res_id));
            if( $att_handler->getCount($criteria) > 0 ) redirect_header('admin.resources.php?op=edit&res_id='.$res_id, 3, '该资源存在附件，请先清除相关附件！');
            if($resources_handler->delete($res_obj)) {      
                redirect_header('admin.resources.php', 3, '删除成功');
            }else{
                echo $res_obj->getHtmlErrors();
            }
        }else{
            xoops_confirm(array('ok' => 1, 'res_id' => $res_obj->getVar('res_id'), 'ac' => 'delete'), $_SERVER['REQUEST_URI'],  '确定删除资源['.$res_obj->getVar('res_subject').']?');
        }
    break;
    
    case 'delete_att':
        $att_obj =& $att_handler->get($att_id);
        if(!is_object($att_obj) || empty($att_id)) redirect_header('admin.resources.php?op=edit&res_id='.$res_id, 3, '没有该附件!');
        $attach =  XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname() . '/' . $att_obj->getVar("att_attachment");         
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if($att_handler->delete($att_obj, true)) {
                //update res_attachment
                $res_obj = $resources_handler->get($res_id);
                $res_obj->setVar("res_attachment", $res_obj->getVar('res_attachment')-1);
                $resources_handler->insert($res_obj);
               // @unlink(XOOPS_VAR_PATH."/resources/" . $att_obj->getVar("att_attachment"));
                if (file_exists($attach)) { @unlink($attach);}                
                redirect_header('admin.resources.php?op=edit&res_id='.$res_id, 3, '删除成功');
            }else{
                echo $att_obj->getHtmlErrors();
            }
        }else{
            xoops_confirm(array('ok' => 1, 'res_id' => $res_id, 'att_id' => $att_id, 'ac' => 'delete_att'), $_SERVER['REQUEST_URI'],  '确定删除附件['.$att_obj->getVar('att_filename').']?');
        }
    break;
    
    default:
        redirect_header('admin.resources.php', 3, '没有该资源！'); 
    break;
}
include "footer.php";
?>
