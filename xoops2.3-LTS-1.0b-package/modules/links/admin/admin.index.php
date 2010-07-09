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
 * @version        $Id: admin.index.php 1 2010-1-22 ezsky$
 */

include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(0);

$path = isset($_REQUEST['path']) ? $_REQUEST['path'] : '';
$cat_handler =& xoops_getmodulehandler('category', 'links');
$links_handler =& xoops_getmodulehandler('links', 'links');

$create = array(
    'logo_dir' => XOOPS_ROOT_PATH.$xoopsModuleConfig['logo_dir']
    );
    
if($path){
    include_once "../include/functions.php";
    switch ($path) {
        case 'logo_dir':
            Linksmkdirs($create['logo_dir']);
        break;
        default:
        break;
    }
}    

foreach ($create as $k=>$v){
    if(!is_dir($v)){
        $create[$k] = $v.'&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.index.php?path='.$k.'">' ._AM_LINKS_CREATE. '</a>';
    }else{
        $create[$k] = $v.'&nbsp;&nbsp;&nbsp;&nbsp;' ._AM_LINKS_DIRCREATED;
    }
}
$count['category'] = $cat_handler->getCount();
$count['links_Total'] = $links_handler->getCount();
$count['links_Release'] = $links_handler->getCount(new Criteria('link_status', 1));
$count['links_Draft'] = $links_handler->getCount(new Criteria('link_status', 0));

$xoopsTpl->assign('create', $create);
$xoopsTpl->assign('count', $count);
$xoopsTpl->assign('logo', $xoopsModuleConfig['logo']);
$xoopsTpl->display("db:links_admin_index.html");

include "footer.php";
?>
