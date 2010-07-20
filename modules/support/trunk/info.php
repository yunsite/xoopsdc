<?php
include_once 'header.php';

// Parameter
$manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'info';
$pro_id = isset( $_REQUEST['pro_id'] ) ? trim($_REQUEST['pro_id']) : 0;

// Get handler
$category_handler =& xoops_getmodulehandler('category', 'support');
$linkusers_handler =& xoops_getmodulehandler('linkusers','support');
$process_handler =& xoops_getmodulehandler('process', 'support');
$transform_handler =& xoops_getmodulehandler('transform', 'support');
$annex_handler =& xoops_getmodulehandler('annex', 'support');

$process_obj = $process_handler->get($pro_id);
if(!is_object($process_obj) || empty($pro_id)) redirect_header('index.php', 3, _MA_SUPPORT_NOQUESTION);
$process = $process_obj->getValues(null, 'n');

switch ($op) {
    default:
    case 'info':
        $xoopsOption['template_main'] = 'support_info.html';
        include XOOPS_ROOT_PATH.'/header.php';
        
        //get customer
        $customer = $member_handler->getUser($process['customer_id']);
        $process['customer_uname'] = $customer->getVar('uname');
        $process['customer_name'] = $customer->getVar('name');

        //get support
        if(!empty($process['support_id'])) {
            $support = $member_handler->getUser($process['support_id']);
            $process['support_uname'] = $support->getVar('uname');
            $process['support_name'] = $support->getVar('name');
        }
        
        // get category
        $cat_obj = $category_handler->get($process['cat_id']);
        if(is_object($cat_obj) && !empty($process['cat_id'])) $process['category'] = $cat_obj->getVar('cat_name');

        // get times
        $process['grate_time'] = formatTimestamp($process['grate_time'], $date);
        $process['update_time'] = formatTimestamp($process['update_time'], $date);
        $process['last_reply_time'] = empty($process['last_reply_time']) ? '' : formatTimestamp($process['last_reply_time'], $date);
        
        // get status
        $process['action'] = $status[$process['status']];

        //get usesub  
        if($user['level'] == 'customer') {
            $process['usesub'] = array('read', 'forword', 'close');
            if ($process['status'] == 'create'  || $process['status'] == 'reject') array_push($process['usesub'], 'finish');
            if ($process['status'] == 'finish' || $process['status'] == 'lgnore' || $process['status'] == 'close') array_push($process['usesub'], 'read', 'reject', 'forword', 'finish', 'lgnore', 'close');
        } elseif ($user['level'] == 'support') {
            $process['usesub'] = array();
            if ($process['status'] == 'create'  || $process['status'] == 'reject') array_push($process['usesub'], 'reply','forword','finish', 'lgnore', 'close');
            if ($process['status'] == 'read' || $process['status'] == 'reply' || $process['status'] == 'forword') array_push($process['usesub'], 'read');
            if ($process['status'] == 'finish' || $process['status'] == 'lgnore' || $process['status'] == 'close') array_push($process['usesub'], 'read', 'reply', 'forword', 'finish', 'lgnore', 'close');
        } else {
            $process['usesub'] = array();
            if ($process['status'] == 'create'  || $process['status'] == 'reject') array_push($process['usesub'], 'reply', 'forword','finish', 'close', 'lgnore');            
            if ($process['status'] == 'read' || $process['status'] == 'reply' || $process['status'] == 'forword') array_push($process['usesub'], 'read');
            if ($process['status'] == 'finish' || $process['status'] == 'lgnore' || $process['status'] == 'close') array_push($process['usesub'], 'read', 'reply', 'forword', 'finish', 'lgnore', 'close');
        }
        array_unique($process['usesub']);
        
        // get annex
        $criteria = new Criteria("pro_id", $pro_id);
        $files = $annex_handler->getAll($criteria, null, false);
        if(!empty($files)) {
            foreach ($files as $key=>$val) {
                if(empty($val['tran_id'])) $process['files'][$key] = $val;
            }
        }

        $xoopsTpl->assign('process', $process);
        
        // get tranforms
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("pro_id", $pro_id));
        $criteria->setSort('grate_time');
        $criteria->setOrder('DESC');
        $tranforms = $transform_handler->getAll($criteria, null ,false);
        
        if(count($tranforms) > 1) {
        
            $ts =& MyTextSanitizer::getInstance();
            
            $uids = null;
            $forword_ids = null;
            foreach($tranforms as $k=>$v) {
                $uids[] = $v['uid'];
                if(!empty($v['forword_uid'])) {
                    $forword_ids[] = $v['forword_uid'];
                }
                $tranforms[$k]['tran_desc'] = $ts->undoHtmlSpecialChars($v['tran_desc']);
                $tranforms[$k]['grate_time'] = formatTimestamp($v['grate_time'], $date);
                $tranforms[$k]['status'] = $status[$v['tran_action']];
                $tranforms[$k]['status_info'] = $status_info[$v['tran_action']];
            }

            if($uids != null) {
                $criteria = new Criteria("uid","(".implode(", ",$uids). ")","in");
                $tran_users = $member_handler->getUsers($criteria);
            }
            
            if($forword_ids != null) {
                $criteria = new Criteria("uid","(".implode(", ",$forword_ids). ")","in");
                $tran_forwords = $member_handler->getUsers($criteria);
            }
            
            foreach($tranforms as $k=>$v) {
                $group = $member_handler->getGroupsByUser($v['uid']);
                if(in_array($xoopsModuleConfig['support_manager'], $group)) {
                    $tranforms[$k]['role'] = 'support_manager';
                } elseif(in_array($xoopsModuleConfig['support'], $group)) {
                    $tranforms[$k]['role'] = 'support';
                } else {
                    $tranforms[$k]['role'] = 'customer';
                }
                
                foreach($tran_users as $_user) {
                    if($_user->getVar('uid') == $v['uid']) {
                    $tranforms[$k]['uname'] = $_user->getVar('uname');
                    $tranforms[$k]['name'] = $_user->getVar('name');
                    }
                }
                
                if($forword_ids != null) {
                    foreach($tran_forwords as $forword_user) {
                        $tranforms[$k]['forword_uname'] = $forword_user->getVar('uname');
                        $tranforms[$k]['forword_name'] = $forword_user->getVar('name');
                    }
                }
                
                if(!empty($files)) {
                    foreach ($files as $key=>$val) {
                        if($k == $val['tran_id']) $tranforms[$k]['files'][$key] = $val;
                    }
                }
                
                if($tranforms[$k]['tran_action'] == 'create') unset($tranforms[$k]);
                if(!in_array($v['tran_action'], $user['permission'])) unset($tranforms[$k]);
            }
            $xoopsTpl->assign('tranforms', $tranforms);
        }

        // set list
        $list = array('default'=>_MA_SUPPORT_LATESTPUBLISH , 1 => _MA_SUPPORT_UNREAD, 2 => _MA_SUPPORT_TREATED, 3 => _MA_SUPPORT_UNTREATED, 4 => _MA_SUPPORT_SOLVE, 5 => _MA_SUPPORT_BYCAT);
        $xoopsTpl->assign('list', $list);
        
        $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
        $xoBreadcrumbs[] = array("title" => $process['subject']);
    break;
    
    case 'reply':
        $xoopsOption['template_main'] = 'support_form.html';
        include XOOPS_ROOT_PATH.'/header.php';
        
        $action = 'action.php?pro_id='.$pro_id.'&op='.$op;
        $form = $process_obj->replyForm($action);
        $form->assign($xoopsTpl);
        
        $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
        $xoBreadcrumbs[] = array("title" => $process['subject'], 'link' => 'info.php?pro_id='.$pro_id);
        $xoBreadcrumbs[] = array("title" => _MA_SUPPORT_QUESTIONREPLY);  
          
    case 'reject':
        $xoopsOption['template_main'] = 'support_form.html';
        include XOOPS_ROOT_PATH.'/header.php';
        
        $action = 'action.php?pro_id='.$pro_id.'&op='.$op;
        $form = $process_obj->replyForm($action);
        $form->assign($xoopsTpl);
        
        $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
        $xoBreadcrumbs[] = array("title" => $process['subject'], 'link' => 'info.php?pro_id='.$pro_id);
        $xoBreadcrumbs[] = array("title" => _MA_SUPPORT_QUESTIONREPLY);
    break;
    
    case 'forword':
        $xoopsOption['template_main'] = 'support_form.html';
        include XOOPS_ROOT_PATH.'/header.php';
        
        $action = 'action.php?pro_id='.$pro_id.'&op=forword';
        $form = $process_obj->replyForm($action, _MA_SUPPORT_QUESTIONFORWARD, 'forword');
        $form->assign($xoopsTpl);
        
        $xoBreadcrumbs[] = array("title" => $xoopsModule->getVar('name'), 'link' => XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/');
        $xoBreadcrumbs[] = array("title" => $process['subject'], 'link' => 'info.php?pro_id='.$pro_id);
        $xoBreadcrumbs[] = array("title" => _MA_SUPPORT_QUESTIONFORWARD);
    break;
}

$xoopsTpl->assign('op', $op);
$xoopsOption['xoops_pagetitle'] = $user['uname'] . ' - ' . $xoopsModule->getVar('name');

include_once 'footer.php';
?>
