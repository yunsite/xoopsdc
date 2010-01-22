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
 * @version        $Id: menu.php 1 2010-1-22 ezsky$
 */

$adminmenu = array(); 
$adminmenu[] = array(
		'title' => _MI_LINKS_INDEX,  
		'link'  => 'admin/admin.index.php'
);
$adminmenu[] = array(
		'title' => _MI_LINKS_CATEGORY,  
		'link'  => 'admin/admin.category.php'
);
$adminmenu[] = array(
		'title' => _MI_LINKS_LINK,  
		'link'  => 'admin/admin.links.php'
);
?>
