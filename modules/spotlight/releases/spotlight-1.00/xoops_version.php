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
 * @version        $Id: xoops_version.php 1 2010-8-31 ezsky$
 */

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

$modversion['name'] = _MI_SPOTLIGHT_FOCUS_MANDGEMENT;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_SPOTLIGHT_FOCUS_MANDGEMENT;
$modversion['author'] = 'Magic.Shao <magic.shao@gmail.com> <br /> Susheng Yang <ezskyyoung@gmail.com>';
$modversion['credits'] = 'http://xoops.org.cn';
$modversion['license'] = 'GPL';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'spotlight';
$modversion['hasAdmin'] = 1; 
$modversion['adminindex'] = 'admin/admin.index.php'; 
$modversion['adminmenu'] = 'admin/menu.php'; 
$modversion['hasMain'] = 0;


//sql
$modversion['sqlfile']['mysql']= 'sql/mysql.sql';
$modversion['tables'] =  array(
    'sp_spotlight',
    'sp_page'
);

//Templates
$modversion["templates"] = array();
$modversion["templates"][] = array(
    "file" => "spotlight_admin_index.html",
    "description" => ""
);
$modversion["templates"][] = array(
    "file" => "spotlight_admin_spotlight.html",
    "description" => ""
);
$modversion["templates"][] = array(
    "file" => "spotlight_admin_page.html",
    "description" => ""
);
$modversion["templates"][] = array(
    "file" => "spotlight_form.html",
    "description" => ""
);

// Blocks
$modversion['blocks'] = array();
$modversion['blocks'][] = array(
    'file'			=> 'blocks.php',
  	'name'			=> _MI_SPOTLIGHT_FOCUS_NEWS,
  	'description'	=> _MI_SPOTLIGHT_FOCUS_NEWS,
  	'show_func'		=> 'spotlight_spotlight_show',
  	'options'		=> '0',
  	'edit_func'		=> 'spotlight_spotlight_edit',
  	'template'		=> 'blocks_spotlight.html'
);

//config
$select = array(
    'y-m-d' => '1',
    'y-m-d h:i:s' => '2',
    _MI_SPOTLIGHT_Y_M_D => '3', 
    _MI_SPOTLIGHT_REAR_MONTH_DAY_HOURS_MINUTES_SECONDS => '4', 
    );

$modversion['config'][] = array(
    'name'			=> 'date',
    'title'			=> '_MI_SPOTLIGHT_CON_TIME',
    'description'	=> '',
    'formtype'		=> 'select',
    'valuetype'		=> 'int',
    'options'		=> $select,
    'default'		=> 1
    );

$modversion['config'][] = array(
    'name'			=> 'spotlight_images',
    'title'			=> '_MI_SPOTLIGHT_CON_SPOTLIGHT_DIR',
    'description'	=> '',
    'formtype'		=> 'textbox',
    'valuetype'		=> 'text',
    'default'		=> '/uploads/spotlight/'
    );

$modversion['config'][] = array(
    'name'			=> 'upload_size',
    'title'			=> '_MI_SPOTLIGHT_CON_UPLOAD_SIZE',
    'description'	=> '_MI_SPOTLIGHT_CON_UPLOAD_SIZE_DESC',
    'formtype'		=> 'textbox',
    'valuetype'		=> 'text',
    'default'		=> '1048576'
    );
?>
