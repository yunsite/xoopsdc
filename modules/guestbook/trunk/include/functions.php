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
 	

/***************Blocks***************/
function block_addCatSelect($cats) {
	if(is_array($cats)) 
	{
		$cat_sql = "(".current($cats);
		array_shift($cats);
		foreach($cats as $cat) 
		{
			$cat_sql .= ",".$cat;
		}
		$cat_sql .= ")";
	}
	return $cat_sql;
}



	
?>