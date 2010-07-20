<?php
include_once 'header.php';

// Parameter
$manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'display';
$select = isset($_REQUEST['select']) ? trim($_REQUEST['select']) : 'default';
$keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
$start = isset( $_REQUEST['start'] ) ? trim($_REQUEST['start']) : 0;
$limit = $xoopsModuleConfig['pagenav'];
$ext = 'select='.$select;
if($keyword) $ext .= '&keyword='.$keyword;

// Get handler
$category_handler =& xoops_getmodulehandler('category', 'support');
$linkusers_handler =& xoops_getmodulehandler('linkusers','support');
$process_handler =& xoops_getmodulehandler('process', 'support');
$transform_handler =& xoops_getmodulehandler('transform', 'support');
$annex_handler =& xoops_getmodulehandler('annex', 'support');

// Redirect index or cateogry
if($manager) {
    if($manager == "index") $redirect = _MA_SUPPORT_QUESTIONMANAGEMENT;
    if($manager == "category") $redirect = _MA_SUPPORT_CATMANAGEMENT;
    redirect_header($manager.'.php', 3, _MA_SUPPORT_FORWARDTO.$redirect);
}else{
    $manager = "index";
}

switch ($op) {
    default:
    case 'display':
        $xoopsOption['template_main'] = 'support_index.html';
        include XOOPS_ROOT_PATH.'/header.php';

        // criteria
        $criteria = new CriteriaCompo();
//        $criteria->add(new Criteria('cat_status', 1, 'AND'));
        // keyword
        if(!empty($keyword)) $criteria->add(new Criteria('subject', '%'.$keyword.'%', 'like'));
        
        // catId
        $link_cats = $linkusers_handler->getCatIds(array($user['uid']));
        $catIds = array();
        if(!empty($link_cats)) {
            foreach ($link_cats as $k=>$v) {
                $catIds[] = $v['cat_id'];
            }
        }        
        if($user['level'] == 'customer') $criteria->add(new Criteria('customer_id', $user['uid']), 'AND');
        if($user['level'] == 'support')  $criteria->add(new Criteria("cat_id","(".implode(", ",$catIds). ")","in"), 'AND');
/*juan
        if($user['level'] == 'support'){
         $criteria->add(new Criteria("cat_id","(".implode(", ",$catIds). ")","in"), 'AND');      
         $criteria->add(new Criteria("support_id",$user['uid']));          
        } 
        */  
        // select 
        
        if(!empty($select)) {
            if($select == 1) {
                $criteria->add(new Criteria('status', 'create'), 'AND');
                $criteria->setSort('update_time');
            } elseif($select == 2) {
                $criteria->add(new Criteria('status', 'create', '!='), 'AND');
                $criteria->add(new Criteria('status', 'read', '!='), 'AND');                
                $criteria->setSort('update_time');
            } elseif($select == 3) {
                $criteria->add(new Criteria('status', 'read'), 'AND');
                $criteria->setSort('update_time');
            } elseif($select == 4) {
                $criteria->add(new Criteria('last_reply_time', 0, '!='), 'AND');
                $criteria->setSort('update_time');
            } elseif($select == 5) {
                $criteria->setSort('cat_id ASC, update_time');
            } else {
                $criteria->setSort('update_time');
            }
        }
        $criteria->setOrder('DESC');
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        
        // pageNav
        if ($process_handler->getCount($criteria) > $limit ){
            include_once XOOPS_ROOT_PATH.'/class/pagenav.php';  
            $pageNav = new XoopsPageNav($process_handler->getCount($criteria), $limit, $start, 'start', @$ext);
            $xoopsTpl->assign('pagenav', $pageNav->renderNav(4));
        }
        
        $process = $process_handler->getAll($criteria, null, false);

        if(!empty($process)) {

            foreach ($process as $k=>$v) {
                $process[$k]['status'] = '';
                $process[$k]['grate_time'] = formatTimestamp($v['grate_time'], $date);
                $process[$k]['update_time'] = formatTimestamp($v['grate_time'], $date);
                $process[$k]['last_reply_time'] = empty($v['last_reply_time']) ? '' : formatTimestamp($v['last_reply_time'], $date);
                $process[$k]['status'] = $status[$v['status']]; 
                $process[$k]['action'] = $v['status']; 
                $cat_ids[] = $v['cat_id'];
                $pro_ids[] = $v['pro_id'];
            }
            
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria("cat_id","(".implode(", ",$cat_ids). ")","in"), 'AND');
            $cats = $category_handler->getList($criteria);

            foreach ($process as $k=>$v) {
                if($v['cat_id']) $process[$k]['cat_name'] = $cats[$v['cat_id']];
            }
        }
        
        $xoopsTpl->assign('process', $process);
        $xoopsTpl->assign('manager', $manager);
        $xoopsTpl->assign('select', $select);
        
        $list = array('default'=>_MA_SUPPORT_LATESTPUBLISH , 1 => _MA_SUPPORT_UNREAD, 2 => _MA_SUPPORT_TREATED, 3 => _MA_SUPPORT_UNTREATED, 4 => _MA_SUPPORT_SOLVE, 5 => _MA_SUPPORT_BYCAT);
        $xoopsTpl->assign('list', $list);
        
        $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'));
    break;
    
    case 'add':
        $xoopsOption['template_main'] = 'support_form.html';
        include XOOPS_ROOT_PATH.'/header.php';
        
        $process_obj =& $process_handler->create();
        $action = 'index.php?op=insert';
        $form = $process_obj->getForm($action);
        $form->assign($xoopsTpl);
        
        $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
        $xoBreadcrumbs[] = array("title" => _MA_SUPPORT_ASK);
    break;
    
    case 'insert':
        include XOOPS_ROOT_PATH.'/header.php';
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        $process_obj =& $process_handler->create();
        
        //assign value to elements of objects 
        foreach(array_keys($process_obj->vars) as $key) {
            if(isset($_POST[$key]) && $_POST[$key] != $process_obj->getVar($key)) {
                $process_obj->setVar($key, $_POST[$key]);
            }
        }

        // customer_id
        $process_obj->setVar('customer_id', $user['uid']);
        $process_obj->setVar('status', 'create');
        // insert object  
        if ($pro_id = $process_handler->insert($process_obj)) {
        
            //transform
            $transform_obj = $transform_handler->create();
            $transform_obj->setVar('pro_id', $pro_id);
            $transform_obj->setVar('uid', $user['uid']);
            $transform_obj->setVar('tran_action', 'create');
            $transform_obj->setVar('grate_time', time());
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
                              $annex_obj->setVar('tran_id', 0);
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
            
            redirect_header('info.php?pro_id='.$pro_id, 3, _MA_SUPPORT_SEND);
        } else {
            redirect_header('index.php', 3, _MA_SUPPORT_SAVEERROR);            
        }
    break;
}

$xoopsTpl->assign('op', $op);
$xoopsOption['xoops_pagetitle'] = $user['uname'] . ' - ' . $xoopsModule->getVar('name');

include_once 'footer.php';
?>
