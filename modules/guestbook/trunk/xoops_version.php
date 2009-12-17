<?php
/**
 * ****************************************************************************
 * Module g�n�r� par TDMCreate de la TDM "http://www.tdmxoops.net"
 * ****************************************************************************
 * guestbook - MODULE FOR XOOPS AND IMPRESS CMS
 * Copyright (c) ezsky ()
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       ezsky ()
 * @license         GPL
 * @package         guestbook
 * @author 			ezsky ()
 *
 * Version : 1.00:
 * ****************************************************************************
 */
 
	
	$modversion["name"] = "guestbook";
	$modversion["version"] = 1.00;
	$modversion["description"] = "自动生成的留言板";
	$modversion["author"] = "ezsky";
	$modversion["author_website_url"] = "";
	$modversion["author_website_name"] = "";
	$modversion["credits"] = "";
	$modversion["license"] = "GPL";
	$modversion["release_info"] = "";
	$modversion["release_file"] = "";
	$modversion["manual"] = "";
	$modversion["manual_file"] = "";
	$modversion["image"] = "images/guestbookLogo.png";
	$modversion["dirname"] = "guestbook";

	//about
	$modversion["demo_site_url"] = "";
	$modversion["demo_site_name"] = "";
	$modversion["module_website_url"] = "";
	$modversion["module_website_name"] = "";
	$modversion["release"] = "0";
	$modversion["module_status"] = "";
	
	// Admin things
	$modversion["hasAdmin"] = 1;
	
	$modversion["adminindex"] = "admin/index.php";
	$modversion["adminmenu"] = "admin/menu.php";
	
	
	// Mysql file
	$modversion["sqlfile"]["mysql"] = "sql/mysql.sql";

	// Tables
	$modversion["tables"][0] = "guestbook_guest";
	
	
	// Scripts to run upon installation or update
	//$modversion["onInstall"] = "include/install.php";
	//$modversion["onUpdate"] = "include/update.php";// Menu
	$modversion["hasMain"] = 1;
	
	//Recherche
	$modversion["hasSearch"] = 1;
	$modversion["search"]["file"] = "include/search.inc.php";
	$modversion["search"]["func"] = "guestbook_search";
	
	$i = 1;
	include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
	$modversion["config"][$i]["name"]        = "guestbook_editor";
	$modversion["config"][$i]["title"]       = "_MI_GUESTBOOK_EDITOR";
	$modversion["config"][$i]["description"] = "";
	$modversion["config"][$i]["formtype"]    = "select";
	$modversion["config"][$i]["valuetype"]   = "text";
	$modversion["config"][$i]["default"]     = "dhtmltextarea";
	$modversion["config"][$i]["options"]     = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . "/class/xoopseditor");
	$modversion["config"][$i]["category"]    = "global";
	$i++;
	
	//Blocs
	$i = 1;
			$modversion["blocks"][$i]["file"] = "blocks_guest.php";
			$modversion["blocks"][$i]["name"] = _MI_GUESTBOOK_GUEST_BLOCK_RECENT;
			$modversion["blocks"][$i]["description"] = "";
			$modversion["blocks"][$i]["show_func"] = "b_guestbook_guest";
			$modversion["blocks"][$i]["edit_func"] = "b_guestbook_guest_edit";
			$modversion["blocks"][$i]["options"] = "recent|5|25|0";
			$modversion["blocks"][$i]["template"] = "guestbook_guest_block_recent.html";
			$i++;
			$modversion["blocks"][$i]["file"] = "blocks_guest.php";
			$modversion["blocks"][$i]["name"] = _MI_GUESTBOOK_GUEST_BLOCK_DAY;
			$modversion["blocks"][$i]["description"] = "";
			$modversion["blocks"][$i]["show_func"] = "b_guestbook_guest";
			$modversion["blocks"][$i]["edit_func"] = "b_guestbook_guest_edit";
			$modversion["blocks"][$i]["options"] = "day|5|25|0";
			$modversion["blocks"][$i]["template"] = "guestbook_guest_block_day.html";
			$i++;
			$modversion["blocks"][$i]["file"] = "blocks_guest.php";
			$modversion["blocks"][$i]["name"] = _MI_GUESTBOOK_GUEST_BLOCK_RANDOM;
			$modversion["blocks"][$i]["description"] = "";
			$modversion["blocks"][$i]["show_func"] = "b_guestbook_guest";
			$modversion["blocks"][$i]["edit_func"] = "b_guestbook_guest_edit";
			$modversion["blocks"][$i]["options"] = "random|5|25|0";
			$modversion["blocks"][$i]["template"] = "guestbook_guest_block_random.html";
			$i++;		
?>