<?php
include_once 'header.php';
include XOOPS_ROOT_PATH.'/header.php';
                      
// Parameter
$manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
$pro_id = isset( $_REQUEST['pro_id'] ) ? trim($_REQUEST['pro_id']) : 0;
$pro_desc = isset( $_POST['infomation'] ) ? $_POST['infomation'] : '';
$cat_id = isset( $_POST['cat_id'] ) ? trim($_POST['cat_id']) : 0;
$forword_uid = isset( $_POST['forword'] ) ? trim($_POST['forword']) : 0;

// Get handler
$category_handler =& xoops_getmodulehandler('category', 'support');
$linkusers_handler =& xoops_getmodulehandler('linkusers','support');
$process_handler =& xoops_getmodulehandler('process', 'support');
$transform_handler =& xoops_getmodulehandler('transform', 'support');
$annex_handler =& xoops_getmodulehandler('annex', 'support');
  
$process_obj = $process_handler->get($pro_id);

if(!is_object($process_obj) || empty($pro_id)) redirect_header('index.php', 3, _MA_SUPPORT_NOQUESTION);

if($user['level'] == 'customer' && $user['uid'] != $process_obj->getVar('customer_id')) redirect_header('index.php', 3, _MA_SUPPORT_NOPEREXPIRED);
if($user['level'] == 'support' && $user['uid'] != $process_obj->getVar('support_id') && $process_obj->getVar('status') != 'create') redirect_header('index.php', 3, _MA_SUPPORT_NOPEREXPIRED);

// update process status and update log
if( $op == 'read' || $op == 'reply' || $op == 'reject' || $op == 'forword' || $op == 'finish' || $op == 'lgnore' || $op == 'close' ){
    // read
    if($op == 'read') {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("pro_id", $pro_id));
        $criteria->setSort('grate_time');
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $tranform = current($transform_handler->getAll($criteria, null ,false));
        if($tranform['forword_uid'] > 0 || $process_obj->getVar('support_id') > 0) redirect_header('info.php?pro_id='.$pro_id, 3, _MA_SUPPORT_QUESTIONREAD);
      
        $process_obj->setVar('support_id', $user['uid']);
    }
    
    // finish or lgnore or close 
    if(($op == 'finish') || ($op == 'lgnore') || ($op == 'close')) $process_obj->setVar('last_reply_time', time());
    
    // forword
    if($op == 'forword') {
      if(empty($cat_id)) redirect_header('info.php?op=forword&pro_id='.$pro_id, 3, _MA_SUPPORT_NOCHOICECAT);
      $process_obj->setVar('cat_id', $cat_id);
      $process_obj->setVar('support_id', $forword_uid);
    }
    //juan
    $process_obj->setVar('status', $op);    
    // status

    
    // insert object  
    if ($pro_id = $process_handler->insert($process_obj)) {
    
        //transform
        $transform_obj = $transform_handler->create();
        $transform_obj->setVar('pro_id', $pro_id);
        $transform_obj->setVar('tran_desc', $pro_desc);
        $transform_obj->setVar('uid', $user['uid']);
        $transform_obj->setVar('tran_action', $op);
        $transform_obj->setVar('grate_time', time());
        if($op == 'forword') $transform_obj->setVar('forword_uid', $forword_uid);

        if($tran_id = $transform_handler->insert($transform_obj)) {
            // upload annex
            if ( !empty($_POST["xoops_upload_file"]) ){
            
                include_once XOOPS_ROOT_PATH."/class/uploader.php";
                include_once 'include/functions.php';
                
                if(Supportmkdirs(XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname())) $files_dir = XOOPS_UPLOAD_PATH . '/' . $xoopsModule->dirname();
                $mid_wh = array(360,360);
                $thumb_wh = array(150,120);
                $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
                $allowed_mimetypes = array(
                    'image/gif', 
                    'image/jpeg', 
                    'image/jpg', 
                    'image/png',
                    'application/msword', 
                    'application/vnd.ms-powerpoint', 
                    'application/vnd.ms-excel', 
                    'application/pdf', 
                    'application/octet-stream',
                    "application/x-gzip",
                    "application/zip"
                );
                $extendmimetypes = array('rar'=>'application/octet-stream');
                $maxfilesize = 50000000;
                $maxfilewidth = 1200;
                $maxfileheight = 1200; 
                $uploader = new XoopsMediaUploader($files_dir, $allowed_mimetypes, $maxfilesize, null, null, $extendmimetypes);
                foreach($_POST["xoops_upload_file"] as $k=>$v){ 
                  if ($uploader->fetchMedia($v)) {
                  $uploader->setPrefix('support_');
                      if (!$uploader->upload()) {
                          echo $uploader->getErrors();
                      } else {       
                          $annex_obj =& $annex_handler->create();
                          $annex_obj->setVar('pro_id', $pro_id);
                          $annex_obj->setVar('tran_id', $tran_id);
                          $annex_obj->setVar('uid', $user['uid']);
                          $annex_obj->setVar('annex_title', $uploader->getMediaName());
                          $annex_obj->setVar('annex_file', $uploader->getSavedFileName());
                          $annex_obj->setVar('annex_type', $uploader->getMediaType());
                          $annex_handler->insert($annex_obj);
                          
                          // thumbs
                          /*
                          if(in_array($uploader->getMediaType(), $allowed_mimetypes)) {
                              setImageThumb($files_dir."/", $uploader->getSavedFileName(), $files_dir."/", 'mid_'.$uploader->getSavedFileName(), array($mid_wh[0], $mid_wh[1]));
                              setImageThumb($files_dir."/", $uploader->getSavedFileName(), $files_dir."/", 'thumb_'.$uploader->getSavedFileName(), array($thumb_wh[0], $thumb_wh[1]));
                          }
                          */
                       }
                       unset($annex_obj);
                   }
                }
            }
        }
    if($op == 'forword' && $user['level'] == 'support') redirect_header('index.php', 3, _AM_SUPPORT_ACTIVEDSUCCESS);    
        redirect_header('info.php?pro_id='.$pro_id, 3, _AM_SUPPORT_ACTIVEDSUCCESS);
    } else {
        redirect_header('index.php', 3, _MA_SUPPORT_SAVEERROR);            
    }
}

redirect_header('index.php', 3, _MA_SUPPORT_NOQUESTION);
?>
