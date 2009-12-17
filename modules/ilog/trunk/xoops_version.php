<?php
/**
 * ilog modules
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         ilog
 * @since           2.3.0
 * @author          ezsky <ezskyyoung@gmail.com>
 * @version        
 */
 


$modversion = array();
$modversion['name'] = _MI_ILOG_NAME;
$modversion['version'] = "1.1";
$modversion['description'] = _MI_ILOG_DESC;
$modversion['author'] = "ezsky <ezskyyoung@gmail.com>";
$modversion['credits'] = "The XOOPS Project,D.J.";
$modversion['license'] = "GPL see LICENSE";
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "ilog";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/admin.php";
$modversion['adminmenu'] = "admin/menu.php";

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Table
$modversion['tables'][0] = "ilog_article";


// Scripts to run upon installation or update
$modversion["onUpdate"] = "include/action.module.php";

// Templates
$modversion['templates'] = array();
$modversion['templates'][1]['file'] = 'ilog_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'ilog_form.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'ilog_view.html';
$modversion['templates'][3]['description'] = '';


/*
 * $options:  
 *                    $options[0] - number of tags to display
 *                    $options[1] - time duration, in days, 0 for all the time
 *                    $options[2] - max font size (px or %)
 *                    $options[3] - min font size (px or %)
 */
$modversion["blocks"][]    = array(
    "file"            => "ilog_block_tag.php",
    "name"            => "ilog Tag Cloud",
    "description"    => "Show tag cloud",
    "show_func"        => "ilog_tag_block_cloud_show",
    "edit_func"        => "ilog_tag_block_cloud_edit",
    "options"        => "100|0|150|80",
    "template"        => "ilog_tag_block_cloud.html",
    );
/*
 * $options:
 *                    $options[0] - number of tags to display
 *                    $options[1] - time duration, in days, 0 for all the time
 *                    $options[2] - sort: a - alphabet; c - count; t - time
 */
$modversion["blocks"][]    = array(
    "file"            => "ilog_block_tag.php",
    "name"            => "ilog Top Tags",
    "description"    => "Show top tags",
    "show_func"        => "ilog_tag_block_top_show",
    "edit_func"        => "ilog_tag_block_top_edit",
    "options"        => "50|30|c",
    "template"        => "ilog_tag_block_top.html",
    );
// Menu
$modversion['hasMain'] = 1;
global $xoopsUser;
if ($xoopsUser) {
    $modversion['sub'][1]['name'] = _MI_ILOG_SUBMIT;
    $modversion['sub'][1]['url'] = "action.php?op=new";
    //$modversion['sub'][2]['name'] = _PROFILE_MI_PAGE_SEARCH;
    //$modversion['sub'][2]['url'] = "search.php";
    //$modversion['sub'][3]['name'] = _PROFILE_MI_CHANGEPASS;
    //$modversion['sub'][3]['url'] = "changepass.php";
}

$modversion['config'] = array(
    array(
        'name' => 'perpage',
    	'title' => '_MI_ILOG_PERPAGE',
    	'description' => '_MI_ILOG_PERPAGE_DESC',
    	'formtype' => 'textbox',
    	'valuetype' => 'int',
    	'default' => 20
        ),
    array(
        'name' => 'summary',
    	'title' => '_MI_ILOG_SUMMARY_LENGTH',
    	'description' => '_MI_ILOG_SUMMARY_LENGTH_DESC',
    	'formtype' => 'textbox',
    	'valuetype' => 'int',
    	'default' => 200
        )
);
?>
