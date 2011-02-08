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
 * @version        $Id: action.spotlight.php 1 2010-8-31 ezsky$
 */

include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : 'display';
$sp_id = isset($_REQUEST['sp_id']) ? $_REQUEST['sp_id'] : '';
$sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
$page_handler =& xoops_getmodulehandler('page', 'spotlight');

switch ($ac) {       
case 'insert':
    if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.spotlight.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    if (isset($sp_id)) {
    $sp_obj =& $sp_handler->get($sp_id);
    $message = _AM_SPOTLIGHT_UPDATE_SUCCESSFUL;
    }else {
        $sp_obj =& $sp_handler->create();         
        $message = _AM_SPOTLIGHT_SAVE_STCCESSFULLY;   
    }
    foreach(array_keys($sp_obj->vars) as $key) {
        if(isset($_POST[$key])) {
            $sp_obj->setVar($key, $_POST[$key]);
        }
    }
    if ($sp_handler->insert($sp_obj)) {
        redirect_header('admin.spotlight.php', 3, $message);
    }else{
        redirect_header('admin.spotlight.php', 3, _AM_SPOTLIGHT_WRONG_OPERATING);
    }
break;

case 'delete':
    $sp_obj =& $sp_handler->get($sp_id);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.spotlight.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        $criteria = new Criteria('sp_id', $sp_id);
        if($page_handler->getCount($criteria)){
            redirect_header('admin.spotlight.php', 3, _AM_SPOTLIGHT_CAT_LINK_INFO);
        }else{
            $sp_handler->delete($sp_obj);
            redirect_header('admin.spotlight.php', 3, _AM_SPOTLIGHT_DELETED_SUCCESSFULLY);
        }
    }else{
        xoops_confirm(array('ok' => 1, 'sp_id' => $sp_id, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_SPOTLIGHT_DELETED_IN_THE_GROUP,$sp_obj->getVar('sp_name')));
    }
break;

default:
    redirect_header('admin.spotlight.php');
break;
}

include "footer.php";
?>
