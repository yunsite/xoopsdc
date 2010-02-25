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
 * @version        $Id: action.category.php 1 2010-1-22 ezsky$
 */

include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : 'display';
$cat_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
$cat_handler =& xoops_getmodulehandler('category', 'links');

switch ($ac) {       
    case 'insert':
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        if (!empty($cat_id)) {
            $cat_obj =& $cat_handler->get($cat_id);
            $message = _AM_LINKS_UPDATEDSUCCESS;
        }else {
            $cat_obj =& $cat_handler->create();         
            $message = _AM_LINKS_SAVEDSUCCESS;   
        }
        $cat_obj->setVar('cat_name', $_REQUEST['cat_name']);

        if ($cat_handler->insert($cat_obj)) {
            redirect_header('admin.category.php', 3, $message);
        }else{
            redirect_header('admin.category.php', 3, _AM_LINKS_ACTIVEERROR);
        }
    break;
    
    case 'delete':
        $cat_obj =& $cat_handler->get($cat_id);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            if($cat_handler->delete($cat_obj)) {
                $link_handler =& xoops_getmodulehandler('links', 'links');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('cat_id', $cat_id));
                $links = $link_handler->getAll($criteria, array('link_id','link_image'), false, false);
                if(!empty($links)){
                    
                    foreach($links as $k=>$v){
                        $link_ids[] = $v['link_id'];
                        if(!empty($v['link_image'])) @unlink(XOOPS_ROOT_PATH.$xoopsModuleConfig['logo_dir'].$v['link_image']);
                    }
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria("link_id","(".implode(',', $link_ids).")","in"));
                    $link_handler->deleteAll($criteria);  
                }
                redirect_header('admin.category.php', 3, _AM_LINKS_DELETESUCCESS);
            }else{
                echo $cat_obj->getHtmlErrors();
            }
        }else{
            xoops_confirm(array('ok' => 1, 'cat_id' => $cat_id, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_LINKS_RESUREDELCAT, $cat_obj->getVar('cat_name')));
        }
        
    break;
    
    default:
        redirect_header('admin.category.php');
    break;
}

include "footer.php";
?>
