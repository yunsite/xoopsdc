<?php
 /**
 * About
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
 * @package        about
 * @since          1.0.0
 * @author         Mengjue Shao <magic.shao@gmail.com>
 * @author         Susheng Yang <ezskyyoung@gmail.com>
 * @version        $Id: xoops_version.php 1 2010-2-9 ezsky$
 */

$modversion['name'] = _MI_ABOUT_NAME;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_ABOUT_DESC;
$modversion['author'] = "Magic.Shao<magic.shao@gmail.com>, ezsky <ezskyyoung@gmail.com>";
$modversion['credits'] = "xoops.org.cn";
$modversion['license'] = "GPL";
$modversion['image'] = "images/about_slogo.png";
$modversion['dirname'] = "about";
$modversion['hasAdmin'] = 1; 
$modversion['adminindex'] = "admin/admin.page.php"; 
$modversion['adminmenu'] = "admin/menu.php"; 

// Is performing module install/update?
$isModuleAction = ( !empty($_POST["fct"]) && "modulesadmin" == $_POST["fct"] ) ? true : false;
$modversion["onInstall"] = "include/action.module.php";
$modversion["onUpdate"] = "include/action.module.php";

// Menu
$modversion['hasMain'] = 1; 
global $xoopsModuleConfig, $xoopsUser, $xoopsModule;

//sql
$modversion['sqlfile']['mysql']= "sql/mysql.sql";
$modversion['tables'] =  array(
"about_page"
);

/**
* Templates
*/
if ($isModuleAction) {
    include_once dirname(__FILE__) . "/include/functions.render.php";
    $modversion["templates"] = about_getTplPageList("", true);
}
// Blocks
$modversion["blocks"] = array();

$modversion["blocks"][1] = array(
    "file"          => "blocks.php",
    "name"          => _MI_ABOUT_ABOUTWE,
    "description"   => "",
    "show_func"     => "about_block_menu_show",
    "options"       => "",
    "edit_func"     => "",
    "template"      => "about_block_menu.html"
);

/*
 * @param int $options[0] page id
 * @param int $options[1] text subStr number
 * @param int $options[2] if show page image
 * @param int $options[3] more link text 
 */

$modversion["blocks"][2] = array(
    "file"          => "blocks.php",
    "name"          => _MI_ABOUT_PAGE,
    "description"   => "",
    "show_func"     => "about_block_page_show",
    "options"       => "1|0|[more]|0",
    "edit_func"     => "about_block_page_edit",
    "template"      => "about_block_page.html"
);
?>
