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
 * @version        $Id: admin.category.php 1 2010-1-22 ezsky$
 */

include "header.php";
xoops_cp_header();
loadModuleAdminMenu(1);
include_once "../include/functions.php";

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['cat_id']) ? 'edit' : 'display');
$cat_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
$cat_handler =& xoops_getmodulehandler('category', 'links');

switch ($op) {
    default:
    case 'display':
        $cat_order = isset($_REQUEST['cat_order']) ? $_REQUEST['cat_order'] : '';
        if(!empty($cat_order)){
            $ac_order = ContentOrder($cat_order, 'category', 'cat_order');
            if(!empty($ac_order)) redirect_header('admin.category.php', 3, _AM_LINKS_UPDATEDSUCCESS);
        }         
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_order');
        $criteria->setOrder('ASC');
        $categories = $cat_handler->getAll($criteria, array('cat_id', 'cat_name', 'cat_order'), false, false);
        foreach($categories as $k=>$v){
            if(empty($v['cat_order'])) $categories[$k]['cat_order'] = '99';
        }
        
        $xoopsTpl->assign('categories', $categories);
        $xoopsTpl->display("db:links_admin_category.html");
    break;
    
    case 'new':
        $cat_obj =& $cat_handler->create();
        $action = 'action.category.php';
        $form = $cat_obj->catForm($action);
        $form->display();
    break;
    
    case "edit":
        $cat_obj =& $cat_handler->get($cat_id);
        $action = 'action.category.php';
        $form = $cat_obj->catForm($action);
        $form->display();
    break;
}

include "footer.php";
?>
