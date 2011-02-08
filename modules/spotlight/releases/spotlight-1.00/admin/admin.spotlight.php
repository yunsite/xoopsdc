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
 * @version        $Id: admin.spotlight.php 1 2010-8-31 ezsky$
 */

include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(1);

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['sp_id']) ? 'edit' : 'display');
$sp_id = isset($_REQUEST['sp_id']) ? intval($_REQUEST['sp_id']) : 0;
$sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');

switch ($op) {
default:
case 'display':
    $spotlights = $sp_handler->getAll(null, array('sp_id', 'sp_name'), false, false);

    $xoopsTpl->assign('spotlights', $spotlights);
    $xoopsTpl->display("db:spotlight_admin_spotlight.html");
break;

case 'new':
    $sp_obj =& $sp_handler->create();
    $form = $sp_obj->getForm('action.spotlight.php');
    $form->assign($xoopsTpl);
    $xoopsTpl->display("db:spotlight_admin_spotlight.html");
break;

case "edit":
    $sp_obj =& $sp_handler->get($sp_id);
    $form = $sp_obj->getForm('action.spotlight.php');
    $form->assign($xoopsTpl);
    $xoopsTpl->display("db:spotlight_admin_spotlight.html");
break;
}

include "footer.php";
?>
