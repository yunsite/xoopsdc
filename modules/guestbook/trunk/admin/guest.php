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

if (isset($_REQUEST["op"])) {
	$op = $_REQUEST["op"];
} else {
	@$op = "show_list_guest";
}

//Menu admin
if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php") ) {
guestbook_adminmenu(1, _AM_GUESTBOOK_MANAGER_GUEST);
} else {
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";
loadModuleAdminMenu (1, _AM_GUESTBOOK_MANAGER_GUEST);
}

//Sous menu
echo "<div class=\"CPbigTitle\" style=\"background-image: url(../images/deco/blank.gif); background-repeat: no-repeat; background-position: left; padding-left: 50px;\">
		<strong>"._AM_GUESTBOOK_MANAGER_GUEST."</strong>
	</div><br /><br>";
switch ($op) 
{	
	case "save_guest":
		if ( !$GLOBALS["xoopsSecurity"]->check() ) {
           redirect_header("guest.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
        }
        if (isset($_REQUEST["guest_id"])) {
           $obj =& $guestHandler->get($_REQUEST["guest_id"]);
        } else {
           $obj =& $guestHandler->create();
        }
		$obj->setVar("guest_content", $_REQUEST["guest_content"]);
			$obj->setVar("guest_name", $_REQUEST["guest_name"]);
			$obj->setVar("guest_email", $_REQUEST["guest_email"]);
			$obj->setVar("guest_url", $_REQUEST["guest_url"]);
			$obj->setVar("guest_submitter", $_REQUEST["guest_submitter"]);
			$obj->setVar("guest_date_created", strtotime($_REQUEST["guest_date_created"]));
			$online = ($_REQUEST["guest_online"] == 1) ? "1" : "0";
			$obj->setVar("guest_online", $online);
			
		
        if ($guestHandler->insert($obj)) {
           redirect_header("guest.php?op=show_list_guest", 2, _AM_GUESTBOOK_FORMOK);
        }
        //include_once("../include/forms.php");
        echo $obj->getHtmlErrors();
        $form =& $obj->getForm();
	break;
	
	case "edit_guest":
		$obj = $guestHandler->get($_REQUEST["guest_id"]);
		$form = $obj->getForm();
	break;
	
	case "delete_guest":
		$obj =& $guestHandler->get($_REQUEST["guest_id"]);
		if (isset($_REQUEST["ok"]) && $_REQUEST["ok"] == 1) {
			if ( !$GLOBALS["xoopsSecurity"]->check() ) {
				redirect_header("guest.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
			}
			if ($guestHandler->delete($obj)) {
				redirect_header("guest.php", 3, _AM_GUESTBOOK_FORMDELOK);
			} else {
				echo $obj->getHtmlErrors();
			}
		} else {
			xoops_confirm(array("ok" => 1, "guest_id" => $_REQUEST["guest_id"], "op" => "delete_guest"), $_SERVER["REQUEST_URI"], sprintf(_AM_GUESTBOOK_FORMSUREDEL, $obj->getVar("guest")));
		}
	break;
	
	case "update_online_guest":
		
	if (isset($_REQUEST["guest_id"])) {
		$obj =& $guestHandler->get($_REQUEST["guest_id"]);
	} 
	$obj->setVar("guest_online", $_REQUEST["guest_online"]);

	if ($guestHandler->insert($obj)) {
		redirect_header("guest.php", 3, _AM_GUESTBOOK_FORMOK);
	}
	echo $obj->getHtmlErrors();
	
	break;
	
	case "default":
	default:

		$criteria = new CriteriaCompo();
		$criteria->setSort("guest_id");
		$criteria->setOrder("ASC");
		$numrows = $guestHandler->getCount();
		$guest_arr = $guestHandler->getall($criteria);
		
		//Affichage du tableau
		if ($numrows>0) 
		{			
			echo "<table width=\"100%\" cellspacing=\"1\" class=\"outer\">
				<tr>
					<th align=\"center\">"._AM_GUESTBOOK_GUEST_CONTENT."</th>
						<th align=\"center\">"._AM_GUESTBOOK_GUEST_NAME."</th>
						<th align=\"center\">"._AM_GUESTBOOK_GUEST_EMAIL."</th>
						<th align=\"center\">"._AM_GUESTBOOK_GUEST_URL."</th>
						<th align=\"center\">"._AM_GUESTBOOK_GUEST_SUBMITTER."</th>
						<th align=\"center\">"._AM_GUESTBOOK_GUEST_DATE_CREATED."</th>
						<th align=\"center\">"._AM_GUESTBOOK_GUEST_ONLINE."</th>
						
					<th align=\"center\" width=\"10%\">"._AM_GUESTBOOK_FORMACTION."</th>
				</tr>";
					
			$class = "odd";
			
			foreach (array_keys($guest_arr) as $i) 
			{
				echo "<tr class=\"".$class."\">";
				$class = ($class == "even") ? "odd" : "even";
				echo "<td align=\"center\">".$guest_arr[$i]->getVar("guest_content")."</td>";	
					echo "<td align=\"center\">".$guest_arr[$i]->getVar("guest_name")."</td>";	
					echo "<td align=\"center\">".$guest_arr[$i]->getVar("guest_email")."</td>";	
					echo "<td align=\"center\">".$guest_arr[$i]->getVar("guest_url")."</td>";	
					echo "<td align=\"center\">".XoopsUser::getUnameFromId($guest_arr[$i]->getVar("guest_submitter"),"S")."</td>";	
					echo "<td align=\"center\">".formatTimeStamp($guest_arr[$i]->getVar("guest_date_created"),"S")."</td>";	
					
					$online = $guest_arr[$i]->getVar("guest_online");
				
					if( $online == 1 ) {
						echo "<td align=\"center\"><a href=\"./guest.php?op=update_online_guest&guest_id=".$guest_arr[$i]->getVar("guest_id")."&guest_online=0\"><img src=\"./../images/deco/on.gif\" border=\"0\" alt=\""._AM_GUESTBOOK_ON."\" title=\""._AM_GUESTBOOK_ON."\"></a></td>";	
					} else {
						echo "<td align=\"center\"><a href=\"./guest.php?op=update_online_guest&guest_id=".$guest_arr[$i]->getVar("guest_id")."&guest_online=1\"><img src=\"./../images/deco/off.gif\" border=\"0\" alt=\""._AM_GUESTBOOK_OFF."\" title=\""._AM_GUESTBOOK_OFF."\"></a></td>";
					}
							echo "<td align=\"center\" width=\"10%\">
								<a href=\"guest.php?op=edit_guest&guest_id=".$guest_arr[$i]->getVar("guest_id")."\"><img src=\"../images/deco/edit.gif\" alt=\""._AM_GUESTBOOK_EDIT."\" title=\""._AM_GUESTBOOK_EDIT."\"></a>
								<a href=\"guest.php?op=delete_guest&guest_id=".$guest_arr[$i]->getVar("guest_id")."\"><img src=\"../images/deco/delete.gif\" alt=\""._AM_GUESTBOOK_DELETE."\" title=\""._AM_GUESTBOOK_DELETE."\"></a>
							  </td>";
				echo "</tr>";
			}
			echo "</table><br><br>";
		}
		// Affichage du formulaire
    	$obj =& $guestHandler->create();
    	$form = $obj->getForm();	
}
echo "<br /><br />
<div align=\"center\"><a href=\"http://www.tdmxoops.net\" target=\"_blank\"><img src=\"http://www.tdmxoops.net/images/logo_modules.gif\" alt=\"TDM\" title=\"TDM\"></a></div>
";
xoops_cp_footer();
	
?>