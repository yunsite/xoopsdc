<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code 
 which is considered copyrighted (c) material of the original comment or credit authors.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS about page module
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0.0
 * @author          susheng yang <ezskyyoung@gmail.com> 
 * @version         $Id: block.php 
 * @package         about
 */
 
if (!defined('XOOPS_ROOT_PATH')) { exit(); }
function about_block_menu_show(){
    $page_handler =& xoops_getmodulehandler('page', 'about');
    $menu_criteria = new CriteriaCompo();
    $menu_criteria->add(new Criteria('page_status', 1), 'AND');
    $menu_criteria->add(new Criteria('page_menu_status', 1));
    $menu_criteria->setSort('page_order');
    $menu_criteria->setOrder('ASC');
    $fields = array(
        "page_id", 
        "page_menu_title", 
        "page_blank",
        "page_menu_status",
        "page_status"
    );
    $page_menu = $page_handler->getAll($menu_criteria, $fields, false);
    
    include dirname(dirname(__FILE__)) . "/xoops_version.php";
    foreach ($page_menu as $k=>$v) {
        $page_menu[$k]['links'] = XOOPS_URL.'/modules/'.$modversion['dirname'].'/index.php?page_id='.$v['page_id'];
    }
    $block = $page_menu;
    return  $block;
}
?>