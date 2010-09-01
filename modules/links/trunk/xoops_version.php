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
 * @version        $Id: xoops_version.php 1 2010-1-22 ezsky$
 */

$modversion['name'] = _MI_LINKS_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _MI_LINKS_DESC;
$modversion['author'] = "Magic.Shao<magic.shao@gmail.com>, ezsky <ezskyyoung@gmail.com>";
$modversion['credits'] = "The XOOPS Project";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "links";

//Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/admin.index.php";
$modversion['adminmenu'] = "admin/menu.php";

//Menu
$modversion['hasMain'] = 1;

//Is performing module install/update?
$isModuleAction = ( !empty($_POST['fct']) && 'modulesadmin' == $_POST['fct'] ) ? true : false;
$modversion["onInstall"] = "include/action.module.php";
$modversion["onUpdate"] = "include/action.module.php";

//sql
$modversion['sqlfile']['mysql']= 'sql/mysql.sql';
$modversion['tables'] =  array(
'links_category',
'links_link'
);

//Templates
$modversion["templates"] = array();
$modversion["templates"][] = array(
"file" => "links_admin_links.html",
"description" => ""
);
$modversion["templates"][] = array(
"file" => "links_admin_category.html",
"description" => ""
);
$modversion["templates"][] = array(
"file" => "links_admin_index.html",
"description" => ""
);
$modversion["templates"][] = array(
"file" => "links_index.html",
"description" => ""
);
$modversion["templates"][] = array(
"file" => "links_join.html",
"description" => ""
);
//config
$i = 0;

$modversion['config'][$i]['name'] = 'logo_dir';
$modversion['config'][$i]['title'] = '_MI_LINKS_CON_LINK_DIR';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '/uploads/links/';
$modversion['config'][$i]['category'] = 'module';

$i++;
$modversion['config'][$i]['name'] = 'logo';
$modversion['config'][$i]['title'] = '_MI_LINKS_CON_LINK';
$modversion['config'][$i]['description'] = '_MI_LINKS_CON_LINK_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'module';

$i++;
$modversion['config'][$i]['name'] = 'application';
$modversion['config'][$i]['title'] = '_MI_LINKS_APPLICATION';
$modversion['config'][$i]['description'] = '_MI_LINKS_APPLICATION_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'module';

//Blocks
$modversion['blocks'][0]['file']        = "blocks.php";
$modversion['blocks'][0]['name']        = _MI_LINKS_BLOCK;
$modversion['blocks'][0]['description'] = "";
$modversion['blocks'][0]['show_func']   = "links_block_show";
$modversion['blocks'][0]['edit_func']   = "links_block_edit";
$modversion['blocks'][0]['options']     = "0|datetime|5|16|1|1"; //options 类别ID，排序，条目数，截取数，1为显示logo 0为不显示,展示方式
$modversion['blocks'][0]['template']    = "links_block_show.html";

?>
