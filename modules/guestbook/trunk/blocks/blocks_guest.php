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
 	
include_once XOOPS_ROOT_PATH."/modules/guestbook/include/functions.php";
	
function b_guestbook_guest($options) {
include_once XOOPS_ROOT_PATH."/modules/guestbook/class/guest.php";
$myts =& MyTextSanitizer::getInstance();

$guest = array();
$type_block = $options[0];
$nb_guest = $options[1];
$lenght_title = $options[2];

$guestHandler =& xoops_getModuleHandler("guestbook_guest", "guestbook");
$criteria = new CriteriaCompo();
array_shift($options);
array_shift($options);
array_shift($options);

switch ($type_block) 
{
	// pour le bloc: guest recents
	case "recent":
		$criteria->add(new Criteria("guest_online", 1));
		$criteria->setSort("guest_date_created");
		$criteria->setOrder("DESC");
	break;
	// pour le bloc: guest du jour
	case "day":	
		$criteria->add(new Criteria("guest_online", 1));
		$criteria->add(new Criteria("guest_date_created", strtotime(date("Y/m/d")), ">="));
		$criteria->add(new Criteria("guest_date_created", strtotime(date("Y/m/d"))+86400, "<="));
		$criteria->setSort("guest_date_created");
		$criteria->setOrder("ASC");
	break;
	// pour le bloc: guest aléatoires
	case "random":
		$criteria->add(new Criteria("guest_online", 1));
		$criteria->setSort("RAND()");
	break;
}


$criteria->setLimit($nb_guest);
$guest_arr = $guestHandler->getall($criteria);
	foreach (array_keys($guest_arr) as $i) 
	{
		$guest[$i]["guest_id"] = $guest_arr[$i]->getVar("guest_id");
			$guest[$i]["guest_content"] = $guest_arr[$i]->getVar("guest_content");
			$guest[$i]["guest_name"] = $guest_arr[$i]->getVar("guest_name");
			$guest[$i]["guest_submitter"] = $guest_arr[$i]->getVar("guest_submitter");
			$guest[$i]["guest_date_created"] = $guest_arr[$i]->getVar("guest_date_created");
			$guest[$i]["guest_online"] = $guest_arr[$i]->getVar("guest_online");
		
	}
return $guest;
}

function b_guestbook_guest_edit($options) {
	$form = ""._MB_GUESTBOOK_GUEST_DISPLAY."\n";
	$form .= "<input type=\"hidden\" name=\"options[0]\" value=\"".$options[0]."\" />";
	$form .= "<input name=\"options[1]\" size=\"5\" maxlength=\"255\" value=\"".$options[1]."\" type=\"text\" />&nbsp;<br />";
	$form .= ""._MB_GUESTBOOK_GUEST_TITLELENGTH." : <input name=\"options[2]\" size=\"5\" maxlength=\"255\" value=\"".$options[2]."\" type=\"text\" /><br /><br />";
	array_shift($options);
	array_shift($options);
	array_shift($options);
	$form .= ""._MB_GUESTBOOK_GUEST_CATTODISPLAY."<br /><select name=\"options[]\" multiple=\"multiple\" size=\"5\">";
	$form .= "<option value=\"0\" " . (array_search(0, $options) === false ? "" : "selected=\"selected\"") . ">" ._MB_GUESTBOOK_GUEST_ALLCAT . "</option>";
	foreach (array_keys($topic_arr) as $i) {
		$form .= "<option value=\"" . $topic_arr[$i]->getVar("topic_id") . "\" " . (array_search($topic_arr[$i]->getVar("topic_id"), $options) === false ? "" : "selected=\"selected\"") . ">".$topic_arr[$i]->getVar("topic_title")."</option>";
	}
	$form .= "</select>";

	return $form;
}
	
?>