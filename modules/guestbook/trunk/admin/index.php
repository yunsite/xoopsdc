<?php
/**
 * ****************************************************************************
 * Module généré par TDMCreate de la TDM "http://www.tdmxoops.net"
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
 
include_once("./header.php");

xoops_cp_header();

global $xoopsModule;

//Apelle du menu admin
if ( !is_readable(XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php"))	{
guestbook_adminmenu(0, _AM_GUESTBOOK_MANAGER_INDEX);
} else {
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";
loadModuleAdminMenu (0, _AM_GUESTBOOK_MANAGER_INDEX);
}

	//compte "total"
	$count_guest = $guestHandler->getCount();
	//compte "attente"
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria("guest_online", 1));
	$guest_online = $guestHandler->getCount($criteria);
	
include_once XOOPS_ROOT_PATH."/modules/guestbook/class/menu.php";

	$menu = new guestbookMenu();
	$menu->addItem("guest", "guest.php", "../images/deco/blank.gif", _AM_GUESTBOOK_MANAGER_GUEST);
	$menu->addItem("update", "../../system/admin.php?fct=modulesadmin&op=update&module=guestbook", "../images/deco/update.png",  _AM_GUESTBOOK_MANAGER_UPDATE);	
	$menu->addItem("permissions", "permissions.php", "../images/deco/permissions.png", _AM_GUESTBOOK_MANAGER_PERMISSIONS);
	$menu->addItem("preference", "../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule->getVar("mid").
												"&amp;&confcat_id=1", "../images/deco/pref.png", _AM_GUESTBOOK_MANAGER_PREFERENCES);
	$menu->addItem("about", "about.php", "../images/deco/about.png", _AM_GUESTBOOK_MANAGER_ABOUT);
	
	echo $menu->getCSS();
	

echo "<div class=\"CPbigTitle\" style=\"background-image: url(../images/deco/index.png); background-repeat: no-repeat; background-position: left; padding-left: 50px;\"><strong>"._AM_GUESTBOOK_MANAGER_INDEX."</strong></div><br />
		<table width=\"100%\" border=\"0\" cellspacing=\"10\" cellpadding=\"4\">
			<tr>
				<td valign=\"top\">".$menu->render()."</td>
				<td valign=\"top\" width=\"60%\">";
				
					echo "<fieldset>
						<legend class=\"CPmediumTitle\">"._AM_GUESTBOOK_MANAGER_GUEST."</legend>
						<br />";
						printf(_AM_GUESTBOOK_THEREARE_GUEST, $count_guest);
						echo "<br /><br />";
						printf(_AM_GUESTBOOK_THEREARE_GUEST_ONLINE, $guest_online);
						echo "<br />
					</fieldset><br /><br />";
					
				echo "</td>
			</tr>
		</table>
<br /><br />
<div align=\"center\"><a href=\"http://www.tdmxoops.net\" target=\"_blank\"><img src=\"http://www.tdmxoops.net/images/logo_modules.gif\" alt=\"TDM\" title=\"TDM\"></a></div>
";
xoops_cp_footer();

?>