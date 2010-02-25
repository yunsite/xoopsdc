<?php
 /**
 * Links
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright      The XOOPS Co.Ltd. http://www.xoops.com.cn
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package        links
 * @since          1.0.0
 * @author         Mengjue Shao <magic.shao@gmail.com>
 * @author         Susheng Yang <ezskyyoung@gmail.com>  
 * @version        $Id: admin.links.php 1 2010-1-22 ezsky$
 */

include "header.php";
xoops_cp_header();
loadModuleAdminMenu(2);
include_once "../include/functions.php";

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['link_id']) ? 'edit' : 'display');
$cat_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
$link_id = isset($_REQUEST['link_id']) ? $_REQUEST['link_id'] : '';
$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'published';

if($cat_id) $op = 'category_display';
$link_handler =& xoops_getmodulehandler('links', 'links');
$cat_handler =& xoops_getmodulehandler('category', 'links');
$count = $cat_handler->getCount();
if(empty($count)) redirect_header('admin.category.php', 3, _AM_LINKS_NOTCREATECAT);

switch ($op) {
    default:
    case 'display':
        $criteria = new CriteriaCompo();
        if($sort == 'release'){
            $criteria->add(new Criteria('link_status', 1));
            $criteria->setSort('datetime');
        }elseif($sort == 'draft'){
            $criteria->add(new Criteria('link_status', 0));
            $criteria->setSort('datetime');
        }
        elseif($sort == 'datetime'){
            $criteria->setSort('datetime');
        }else{
            $criteria->setSort('published');
        }     
        $criteria->setOrder('DESC');
        $links = $link_handler->getAll($criteria, null, false, false);
        foreach($links as $k=>$v){            
            $links[$k]['published'] = formatTimestamp($v['published'],'Y-m-d H:i:s');
            $links[$k]['datetime'] = formatTimestamp($v['datetime'],'Y-m-d H:i:s');
            $links[$k]['link_image'] = XOOPS_URL.$xoopsModuleConfig['logo_dir'].$v['link_image'];            
            $cat_obj = $cat_handler->get($v['cat_id']);
            if(is_object($cat_obj)) $links[$k]['cat_name'] = $cat_obj->getVar('cat_name');
            if(empty($v['link_order'])) $links[$k]['link_order'] = '99';
            if(empty($v['link_status'])){
                $links[$k]['link_status'] = XOOPS_URL.'/modules/links/images/delete.png';
            }else{
                $links[$k]['link_status'] = XOOPS_URL.'/modules/links/images/accept.png';
            }
        }

        $sorts_list = array(
            'published'=>_AM_LINKS_BYPUBLISH,
            'datetime'=>_AM_LINKS_BYUPDATE,
            'release'=>_AM_LINKS_PUBLISHLIK,
            'draft'=>_AM_LINKS_NOTPUBLISHLIK
            );
            
        $xoopsTpl->assign('sort', $sort);
        $xoopsTpl->assign('sorts_list', $sorts_list);
        $xoopsTpl->assign('links', $links);
        $xoopsTpl->assign('op', $op);
        $xoopsTpl->assign('logo', $xoopsModuleConfig['logo']);        
        $xoopsTpl->display("db:links_admin_links.html");
    break;
    
    case 'category_display':
        $cat_obj = $cat_handler->get($cat_id);
        if(!is_object($cat_obj)) redirect_header('admin.links.php', 3, _AM_LINKS_CATIDERROR);        
        $link_order = isset($_REQUEST['link_order']) ? $_REQUEST['link_order'] : '';
        if($link_order){
            $ac_order = ContentOrder($link_order, 'links', 'link_order');
            if($ac_order) redirect_header('admin.links.php?cat_id='.$cat_id, 3, _AM_LINKS_UPDATEDSUCCESS);
        } 
        
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_id', $cat_id));
        $criteria->setSort('link_order');
        $criteria->setOrder('ASC');
        $links = $link_handler->getAll($criteria, null, false, false);
        foreach($links as $k=>$v){
            $links[$k]['published'] = formatTimestamp($v['published'],'Y-m-d H:i:s');
            $links[$k]['datetime'] = formatTimestamp($v['datetime'],'Y-m-d H:i:s');
            $links[$k]['link_image'] = XOOPS_URL.$xoopsModuleConfig['logo_dir'].$v['link_image'];            
            $links[$k]['cat_name'] = $cat_obj->getVar('cat_name');
            if(empty($v['link_order'])) $links[$k]['link_order'] = '99';
            if(empty($v['link_status'])){
                $links[$k]['link_status'] = XOOPS_URL.'/modules/links/images/delete.png';
            }else{
                $links[$k]['link_status'] = XOOPS_URL.'/modules/links/images/accept.png';
            }
            
        }
        $xoopsTpl->assign('cat_id', $cat_id);
        $xoopsTpl->assign('cat_name', $cat_obj->getVar('cat_name'));
        $xoopsTpl->assign('links', $links);
        $xoopsTpl->assign('op', $op);
        $xoopsTpl->assign('logo', $xoopsModuleConfig['logo']);
        $xoopsTpl->display("db:links_admin_links.html");
    break;
    
    case 'new':
        $link_obj =& $link_handler->create();
        $action = 'action.links.php?sort='.$sort;
        $form = $link_obj->linksForm($action);
        $form->display();
    break;
    
    case "edit":
        $link_obj =& $link_handler->get($link_id);
        $action = 'action.links.php?sort='.$sort;
        $form = $link_obj->linksForm($action);
        $form->display();
    break;
}

include "footer.php";
?>
