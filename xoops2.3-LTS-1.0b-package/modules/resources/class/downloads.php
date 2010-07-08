<?php
if (false === defined('XOOPS_ROOT_PATH')) exit();

include(XOOPS_ROOT_PATH."/class/zipdownloader.php");

class ResourcesDownloads extends XoopsObject {
  	function __construct()
    {
  	}
	
}
class ResourcesDownloadsHandler extends XoopsZipDownloader
{
    function __construct(&$db) {  
    }
}
?>
