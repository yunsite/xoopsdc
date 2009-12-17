<?php
if (false === defined('XOOPS_ROOT_PATH')) {
	exit();
}
include(XOOPS_ROOT_PATH."/class/zipdownloader.php");
class resourcesDownloads extends XoopsObject {
	public function __construct() {
		
	}
	
}
class resourcesDownloadsHandler extends XoopsZipDownloader
{
	public function __construct(&$db) {
        
    }
}
?>