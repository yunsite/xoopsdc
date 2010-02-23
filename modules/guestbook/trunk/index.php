<?php

include 'header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['id']) ? "reply" : 'list');

$handler =& xoops_getmodulehandler('message','guestbook');
switch($op) {
    default:
    case "list":
    $xoopsOption['template_main'] = "guestbook_index.html";
    include XOOPS_ROOT_PATH . '/header.php';
    include_once XOOPS_ROOT_PATH . "/class/pagenav.php";
	$pageNav_criteria = new CriteriaCompo();
  $pageNav_criteria -> add(new Criteria('pid',0));
	$pageNav_criteria -> add(new Criteria('approve', '1'));
	$count = $handler->getCount($pageNav_criteria);
	$items_perpage=$xoopsModuleConfig['perpage'];
	if(!isset($_GET['start'])){
		$start=0;
	}
	else{
		@$start=intval($_GET['start']);
		}
	$pageNav = new XoopsPageNav($count,$items_perpage, $start, "start");
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('approve',1));
    $criteria->add(new Criteria('pid', 0));
    $criteria->setLimit($items_perpage);
  $criteria->setStart($start);
    $parent_message = $handler->getObjects($criteria, true, false);
    
    $child_criteria = new CriteriaCompo();
    foreach ($parent_message as $key => $value) {
        $child_criteria->add(new Criteria('pid', $key), 'OR');
    }
    $child_criteria->add(new Criteria('approve', '1'),'AND');
    $child_criteria->setSort("msg_time");
    $child_criteria->setOrder("ASC");
    $child_message = $handler->getObjects($child_criteria, true, false);
    $f =$count-$start;
    foreach ( array_keys($parent_message) as $i ) {
    $parent_message[$i]['floor']=$f--;
          $parent_message[$i]['title']=$parent_message[$i]['title'];
          $parent_message[$i]['name']=$parent_message[$i]['name'];
          $parent_message[$i]['message']=$parent_message[$i]['message'];
          $parent_message[$i]['msg_time']=formatTimestamp($parent_message[$i]['msg_time']);
          foreach ( array_keys($child_message) as $n ){
              if($child_message[$n]['pid']==$parent_message[$i]['id']){
                  $parent_message[$i]['child'][$n]= array();
                  $parent_message[$i]['child'][$n]=$child_message[$n];
                  $parent_message[$i]['child'][$n]['msg_time']=formatTimestamp($child_message[$n]['msg_time']);
                }
            }
      }

    $xoopsTpl->assign('messages',$parent_message);
   	$xoopsTpl->assign('page', $pageNav->renderNav());
    $form = $handler->getForm();
    $form->assign($xoopsTpl);
    include XOOPS_ROOT_PATH."/footer.php";
    break;
    

    case "reply":
    
   include XOOPS_ROOT_PATH . '/header.php';
    $form = $handler->getForm();
    $form->display();
    include XOOPS_ROOT_PATH."/footer.php";
    break;

    case "save":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }
 
    $obj =& $handler->create();
    
    if (isset($_REQUEST['id'])) {
      $p_obj =& $handler->get($_REQUEST['id']);
      $pid = $p_obj->getVar('pid');
      
      if ($pid == 0){
    
          $obj->setVar('pid', $_REQUEST['id']);
        
      }else{
      $obj->setVar('pid', $pid);
      }
    } 
    
     if ($xoopsUser) {
	         $obj->setVar('uid', $_REQUEST['uid']);
	         $obj->setVar('approve', 1);
        } else {
           
           $obj->setVar('email', $_REQUEST['email']);
     }
     if ($xoopsModuleConfig['allow_guest']){
      $obj->setVar('approve', 1);
     }
    $obj->setVar("msg_time", time());
    $obj->setVar('name', $_REQUEST['name']);
    $obj->setVar('title', $_REQUEST['title']);
    $obj->setVar('message', $_REQUEST['message']);
   
    if ($handler->insert($obj)) {
        redirect_header('index.php', 3, sprintf(_MD_GUESTBOOK_SAVEDSUCCESS, _MD_GUESTBOOK_INSERT));
    }
   include XOOPS_ROOT_PATH . '/header.php';
    echo $handler->getHtmlErrors();
    $form =& $handler->getForm();
    $form->display();
    include XOOPS_ROOT_PATH."/footer.php";
    break;

    case "delete":

    include XOOPS_ROOT_PATH . '/header.php';
    $obj =& $handler->get($_REQUEST['id']);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if ($handler->delete($obj)) {
            redirect_header('index.php', 3, sprintf(_MD_GUESTBOOK_DELDSUCCESS, _MD_GUESTBOOK_INSERT));
        } else {
            echo $handler->getHtmlErrors();
        }
    } else {
        xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_MD_GUESTBOOK_DELDSUCCESS, $obj->getVar('cat_title')));
    }
    include XOOPS_ROOT_PATH."/footer.php";
    break;
}


?>
