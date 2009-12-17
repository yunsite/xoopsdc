<?php
/**
 * XOOPS photo management module
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/ 
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0.0
 * @author          Xiao Hui <xh.8326@gmail.com>
 * @version         $Id: picture.php 2292 2009-03-08 11:53:18Z xiao.hui $
 * @package         xoAlbum
 */
if (false === defined("XOOPS_ROOT_PATH")) {exit();}

function xoops_module_install_ilog(&$module)
{
    return true;
}
function xoops_module_update_ilog(&$module)
{
	global $xoopsDB;
	$query = "ALTER TABLE `".$xoopsDB->prefix("ilog_article")."`  ADD `dohtml` tinyint( 1 ) UNSIGNED NOT NULL DEFAULT '1';";
    $xoopsDB->queryF($query);
    $handler =& xoops_getmodulehandler('article','ilog');
    $articles = $handler->getAll(null,array('id'), false, true);

    include_once dirname(__FILE__) . "/function.php";

    foreach (array_keys($articles) as $k){
    	$obj =& $handler->get($k);
    	$contentSubStr = html_substr($obj->getVar("text_body"),400);
    	if ($contentSubStr){
    		$obj->setVar('summary', $contentSubStr);
    	}else{
    		$obj->setVar('summary', $obj->getVar("text_body"));	
    	}
    	$obj->setVar('text_body', $obj->getVar("text_body"));

    	$handler->insert($obj);
    	unset($obj);
    }
    return true;
}
function xoops_module_uninstall_ilog(&$module)
{
    return true;
}
?>