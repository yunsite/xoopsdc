<?php
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

function xoops_module_install_resources(&$module)
{
    return true ;
}
function xoops_module_update_resources(&$module, $prev_version = null)
{
	global $xoopsDB;
    $query = "ALTER TABLE `".$xoopsDB->prefix("resources_attachments")."`  ADD `art_id` INT( 8 ) NOT NULL ;";
    $xoopsDB->queryF($query);
	return true;
}
?>
