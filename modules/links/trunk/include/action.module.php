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
 * @version        $Id: action.module.php 1 2010-1-22 ezsky$
 */

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

function xoops_module_install_links(&$module)
{
    $data_category_file = XOOPS_ROOT_PATH."/modules/links/sql/mysql.links.sql";
    $GLOBALS["xoopsDB"]->queryF("SET NAMES utf8");
    if(!$GLOBALS["xoopsDB"]->queryFromFile($data_category_file)){
        $module->setErrors("Pre-set data were not installed");
        return true;
    }
    return true ;
}
function xoops_module_update_links(&$module, $prev_version = null)
{
    return true;
}
?>
