<?php
if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}

include_once XOOPS_ROOT_PATH."/class/uploader.php";

class ImagesUploader extends XoopsMediaUploader
{
    function getMaxWidth()
    {
        return $this->maxWidth;
    }

    function getMaxHeight()
    {
        return $this->maxHeight;
    }

    function getUploadDir()
    {
        return $this->uploadDir;
    }
    
    function UploadAttachments($num=0,$path_upload=null) {
    	if ( null === $path_upload ) {
    		$path_upload = XOOPS_UPLOAD_PATH."/resources" ;
    		if ( !is_dir($path_upload) ) {
				if ( !@mkdir($path_upload, 0777) ){
					return false ;
				} else {
					@chmod($path_upload, 0777);
				}
			}
    	}
    $allow_type = include dirname(__FILE__) . '/mimetypes.inc.php';
		$media_name = $_POST["xoops_upload_file"][$num] ;
		$attachment_tmpname = $_FILES[$media_name]["tmp_name"];
		$attachment_maxsize = $_FILES[$media_name]["size"];
		if ( $_POST["MAX_FILE_SIZE"] < $attachment_maxsize )	 {
			return false;
		}
	    $uploader = new XoopsMediaUploader($path_upload, $allow_type, $attachment_maxsize);
	    if ($uploader->fetchMedia($media_name)) {
	    	$uploader->setPrefix("rep_");
	    	if ($uploader->upload()) {
	    		return $uploader;
	    	}
	    	return false;        
	    }
	    return false;
	}
	
}

?>
