<?php
if (false === defined('XOOPS_ROOT_PATH')) {
	exit();
}

class resourcesAttachments extends XoopsObject {
	public function __construct() {
		$this->initVar('att_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('resources_id', XOBJ_DTYPE_INT);
		$this->initVar('uid', XOBJ_DTYPE_INT);
		$this->initVar('att_filename', XOBJ_DTYPE_TXTBOX);
		$this->initVar('att_attachment', XOBJ_DTYPE_TXTBOX);
		$this->initVar('att_type', XOBJ_DTYPE_TXTBOX); 
		$this->initVar('att_size', XOBJ_DTYPE_INT); 
		$this->initVar('att_dateline', XOBJ_DTYPE_INT);
		$this->initVar('att_downloads', XOBJ_DTYPE_INT);
		$this->initVar('art_id', XOBJ_DTYPE_INT);
	}
	
}
class resourcesAttachmentsHandler extends XoopsPersistableObjectHandler
{
	public function __construct(&$db) {
        parent::__construct($db,'resources_attachments','resourcesAttachments','att_id');
    } 
    
    public function setAttachments($resources_id){
    	global $xoopsUser, $xoopsModuleConfig;
    	include_once (dirname(__FILE__)."../../include/uploader.php");
    	for ($i=0;$i<$xoopsModuleConfig["attnum"]; $i++ ) {
    		if ( !$_FILES[$_POST["xoops_upload_file"][$i]]["size"] ) continue;
			$picture_type_obj = ImagesUploader::UploadAttachments($i) ;
			if ( false === $picture_type_obj )  continue;
			$att_obj = $this->get();
			$att_obj->setVar("uid",$xoopsUser->getVar("uid"));
			$att_obj->setVar("resources_id",$resources_id);
			$att_obj->setVar("att_filename",$picture_type_obj->getMediaName());
			$att_obj->setVar("att_attachment",$picture_type_obj->getSavedFileName());
			$att_obj->setVar("att_type",$picture_type_obj->getMediaType());
			$att_obj->setVar("att_size",$picture_type_obj->getMediaSize());
			$att_obj->setVar("att_dateline",time());
			if ( $att_id = $this->insert($att_obj) ) {
				$resources_handler = xoops_getmodulehandler("resources");
				$resources_obj = $resources_handler->get($resources_id);
				$resources_obj->setVar("resources_attachment",$resources_obj->getVar("resources_attachment") + 1 );
    			$resources_handler->insert($resources_obj);
			}
    	}
		return false;
    }
    
    public function setAttachmentsArt($art_id,$resources_id){
    	global $xoopsUser, $xoopsModuleConfig;
    	include_once (dirname(__FILE__)."../../include/uploader.php");
    	for ($i=0;$i<5; $i++ ) {
    		if ( !$_FILES[$_POST["xoops_upload_file"][$i]]["size"] ) continue;
			$picture_type_obj = ImagesUploader::UploadAttachments($i) ;
			if ( false === $picture_type_obj )  continue;
			$att_obj = $this->get();
			$att_obj->setVar("uid",$xoopsUser->getVar("uid"));
			$att_obj->setVar("resources_id",$resources_id);
			$att_obj->setVar("att_filename",$picture_type_obj->getMediaName());
			$att_obj->setVar("att_attachment",$picture_type_obj->getSavedFileName());
			$att_obj->setVar("att_type",$picture_type_obj->getMediaType());
			$att_obj->setVar("att_size",$picture_type_obj->getMediaSize());
			$att_obj->setVar("att_dateline",time());
			$att_obj->setVar("art_id",$art_id);
			$this->insert($att_obj);
    	}
		return false;
    }
    
    public function getAttArtList($art_id) {
        $criteria = new CriteriaCompo(new Criteria("art_id",$art_id));
        $attachments = $this->getAll($criteria,null,false);
        $att = array();
        foreach ( $attachments as $k=>$v ) {
            $att[$k] = $v;
            $att[$k]["att_dateline"] = formatTimestamp($v["att_dateline"]);
            $att[$k]["att_size"] = number_format($v["att_size"]/1024,2) . "Kb";
            //$att[$k]["att_num"] = 0;
            //$att[$k]["att_num"] = $att[$k]["att_num"]+1;
        }
        return $att;
    }
    
    
    public function getAttachmentList($resources_id) {
        $criteria = new CriteriaCompo(new Criteria("resources_id",$resources_id));
        $attachments = $this->getAll($criteria,null,false);
        $att = array();
        foreach ( $attachments as $k=>$v ) {
            $att[$k] = $v;
            $att[$k]["att_dateline"] = formatTimestamp($v["att_dateline"]);
            $att[$k]["att_size"] = number_format($v["att_size"]/1024,2) . "Kb";
        }
        return $att;
    }
}
