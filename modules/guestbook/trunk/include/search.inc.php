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
 

	function guestbook_search($queryarray, $andor, $limit, $offset, $userid)
	{
		global $xoopsDB;
		
		$sql = "SELECT guest_id, guest_content, guest_submitter, guest_date_created FROM ".$xoopsDB->prefix("guestbook_guest")." WHERE guest_online = 1";
		
		if ( $userid != 0 ) {
			$sql .= " AND guest_submitter=".intval($userid)." ";
		}
		
		$sql .= " AND (guest_content LIKE '%$queryarray[0]%')";
		
		
		$sql .= "ORDER BY guest_date_created DESC";
		$result = $xoopsDB->query($sql,$limit,$offset);
		$ret = array();
		$i = 0;
		while($myrow = $xoopsDB->fetchArray($result))
		{
			$ret[$i]["image"] = "images/deco/guest_search.gif";
			$ret[$i]["link"] = "guest.php?guest_id=".$myrow["guest_id"]."";
			$ret[$i]["title"] = $myrow["guest_content"];
			$ret[$i]["time"] = $myrow["guest_date_created"];
			$ret[$i]["uid"] = $myrow["guest_submitter"];
			$i++;
		}
		return $ret;
	}

	
?>