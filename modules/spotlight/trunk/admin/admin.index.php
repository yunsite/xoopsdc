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
 * @version        $Id: admin.index.php 1 2010-8-31 ezsky$
 */

include('header.php');

xoops_cp_header();
loadModuleAdminMenu(0);

$sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
$page_handler =& xoops_getmodulehandler('page', 'spotlight');

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php"; 

$count['spotligt'] = $sp_handler->getCount();
$count['page'] = $page_handler->getCount();
$count['components'] = count(XoopsLists::getDirListAsArray(dirname(dirname(__FILE__)) . '/components'));

$xoopsTpl->assign('count', $count);

$xoopsTpl->display("db:spotlight_admin_index.html");

xoops_cp_footer();
?>
