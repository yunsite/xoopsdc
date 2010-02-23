<?php
/**
 * Guestbook modules
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
 * @package         guestbook
 * @since           2.3.0
 * @author          ezsky <ezskyyoung@gmail.com>
 * @version        
 */
 


$modversion = array();
$modversion['name'] = _MI_GUESTBOOK_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _MI_GUESTBOOK_DESC;
$modversion['author'] = "ezsky <ezskyyoung@gmail.com>";
$modversion['credits'] = "The XOOPS Project,D.J.";
$modversion['license'] = "GPL see LICENSE";
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "guestbook";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/admin.php";
$modversion['adminmenu'] = "admin/menu.php";

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Table
$modversion['tables'][0] = "guestbook_massages";

// Scripts to run upon installation or update
//$modversion['onInstall'] = "include/install.php";
//$modversion['onUpdate'] = "include/update.php";

// Templates
$modversion['templates'] = array();
$modversion['templates'][1]['file'] = 'guestbook_index.html';
$modversion['templates'][1]['description'] = '';


// Menu
$modversion['hasMain'] = 1;

$modversion['config'] = array();
$modversion['config'][]=array(
	'name' => 'perpage',
	'title' => '_GB_MI_PERPAGE',
	'description' => '_GB_MI_PERPAGE_DESC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' => 20);
	$modversion['config'][]=array(
	'name' => 'allow_guest',
	'title' => '_GB_MI_LIST',
	'description' => '_GB_MI_LIST_DESC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => 1);

?>
