<?php
 /**
 * Spotlight
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright      The BEIJING XOOPS Co.Ltd. http://www.xoops.com.cn
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package        spotlight
 * @since          1.0.0
 * @author         Mengjue Shao <magic.shao@gmail.com>
 * @author         Susheng Yang <ezskyyoung@gmail.com>
 * @version        $Id: admin.page.php 1 2010-8-31 ezsky$
 */

include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(2);

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['page_id']) ? 'edit' : 'display');
$page_id = isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : '';
$sp_id = isset($_REQUEST['sp_id']) ? intval($_REQUEST['sp_id']) : '';
$sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
$page_handler =& xoops_getmodulehandler('page', 'spotlight');

$spotlights = $sp_handler->getList();
if(empty($spotlights)) redirect_header('admin.spotlight.php', 3, _AM_SPOTLIGHT_PLEASE_ADD_SLIDE);
$sp_name = !empty($sp_id) ? $spotlights[$sp_id] : current($spotlights);
$sp_id = !empty($sp_id) ? $sp_id : current($sp_handler->getIds());

switch ($op) {
default:
case 'display':
    $page_order = isset($_REQUEST['page_order']) ? $_REQUEST['page_order'] : '';
    if(!empty($page_order)){
        include_once '../include/functions.php';
        $ac_order = SpotlightContentOrder($page_order, 'page', 'page_order');
    } 
    if(!empty($ac_order)) redirect_header('admin.page.php?sp_id='.$sp_id, 3, _AM_SPOTLIGHT_UPDATE_SUCCESSFUL);
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('sp_id', $sp_id));
    $criteria->setSort('page_order');
    $criteria->setOrder('ASC');
    $pages = $page_handler->getAll($criteria, array('sp_id','page_title','published', 'page_order', 'page_status'), false, false);
    foreach($pages as $k=>$v){
        $pages[$k]['published'] = formatTimestamp($v['published'],'Y/m/d');
        $pages[$k]['page_status'] = empty($v['page_status']) ? '<img src="'.XOOPS_URL.'/modules/spotlight/images/delete.png" title='._AM_SPOTLIGHT_MANAGEMENT_UNPUBLISE.'>' : '<img src="'.XOOPS_URL.'/modules/spotlight/images/accept.png" title='._AM_SPOTLIGHT_MANAGEMENT_PUBLISE.'>';
        if(empty($v['page_order'])) $figures[$k]['page_order'] = '99';
    }
    $xoopsTpl->assign('spotlights', $spotlights);
    $xoopsTpl->assign('pages', $pages);
    $xoopsTpl->assign('sp_name', $sp_name);
    $xoopsTpl->assign('sp_id', $sp_id);
    $xoopsTpl->display("db:spotlight_admin_page.html");
break;

case 'new':
    $page_obj =& $page_handler->create();
    $form = $page_obj->getForm("action.page.php?sp_id={$sp_id}");
    $form->display();
break;

case "edit":
    $page_obj =& $page_handler->get($page_id);
    $form = $page_obj->getForm("action.page.php?sp_id={$sp_id}");
    $form->display();
break;
}

include "footer.php";
?>
