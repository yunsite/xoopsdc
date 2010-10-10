<?php
/**
 * Empty module foo
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
 * @package         foo
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          Susheng Yang <ezskyyoung@gmail.com> 
 * @version         $Id: xoops_version.php  $
 */
 
/**
 * 
 * 
 *
 */
$modversion = array();
$modversion['name'] = _FOO_MI_NAME;
$modversion['version'] = 1;
$modversion['description'] = _FOO_MI_DESC;
$modversion['author'] = "Susheng Yang<ezskyyoung@gmail.com><br />Taiwen Jiang <phppp@users.sourceforge.net>";
$modversion['credits'] = "xoops community.";
$modversion['license'] = "GPL see XOOPS LICENSE";
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "foo";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Scripts to run upon installation or update
//$modversion['onInstall'] = "include/install.php";
//$modversion['onUpdate'] = "include/update.php";

// Menu
$modversion['hasMain'] = 1;

    $modversion['sub'][1]['name'] = _FOO_MI_SUBMENU;
    $modversion['sub'][1]['url'] = "subpage.php";
    


// Mysql file
//$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
//$modversion['tables'][1] = "_";
//$modversion['tables'][2] = "_";
//$modversion['tables'][3] = "_";
//$modversion['tables'][4] = "_";
//$modversion['tables'][5] = "_";


// Config items
//$modversion['config'][1]['name'] = '_';
//$modversion['config'][1]['title'] = '_MI_FOO_';
//$modversion['config'][1]['description'] = '';
//$modversion['config'][1]['formtype'] = '';
//$modversion['config'][1]['valuetype'] = '';
//$modversion['config'][1]['default'] = ;

// Templates
$i = 0;

$i++;
$modversion['templates'][$i]['file'] = 'foo_index.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'foo_admin_index.html';
$modversion['templates'][$i]['description'] = '';


?>