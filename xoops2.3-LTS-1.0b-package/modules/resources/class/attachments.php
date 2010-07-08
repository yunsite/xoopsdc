<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class ResourcesAttachments extends XoopsObject
{
    function __construct() {        
        $this->initVar('att_id', XOBJ_DTYPE_INT, null, false);
    		$this->initVar('res_id', XOBJ_DTYPE_INT, 0, false); 
    		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
    		$this->initVar('att_subject', XOBJ_DTYPE_TXTBOX);
    		$this->initVar('att_content', XOBJ_DTYPE_TXTAREA);
    		$this->initVar('att_filename', XOBJ_DTYPE_TXTBOX);
    		$this->initVar('att_attachment', XOBJ_DTYPE_TXTBOX);
    		$this->initVar('att_type', XOBJ_DTYPE_TXTBOX); 
    		$this->initVar('att_size', XOBJ_DTYPE_INT, 0, false); 
    		$this->initVar('att_downloads', XOBJ_DTYPE_INT, 0, false);
    		$this->initVar('grate_time', XOBJ_DTYPE_INT, 0, false);
    		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
    		
    		
    }
}

class ResourcesAttachmentsHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'res_attachments', 'ResourcesAttachments', 'att_id', 'att_subject');
    }
    
    function getTypes($ext = null, $unset = null)
    {
        $types = array(
             "hqx"		=> "application/mac-binhex40",
             "doc"		=> "application/msword",
             "docx"		=> "application/vnd.openxmlformats-officedocument.wordprocessingml.document", 
             "xlsx"		=> "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", 
             "pptx"		=> "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
             "dot"		=> "application/msword",
             "accdb"	=> "application/msaccess", 
             'rar'    => 'application/octet-stream',
             "sql"		=> "application/octet-stream", 
             "bin"		=> "application/octet-stream",
             "lha"		=> "application/octet-stream",
             "lzh"		=> "application/octet-stream",
             "exe"		=> "application/octet-stream",
             "class"	=> "application/octet-stream",
             "so"		  => "application/octet-stream",
             "dll"		=> "application/octet-stream",
             "chm"		=> "application/octet-stream", 
             "pdf"		=> "application/pdf",
             "ai"		  => "application/postscript",
             "eps"		=> "application/postscript",
             "ps"		  => "application/postscript",
             "smi"		=> "application/smil",
             "smil"		=> "application/smil",
             "wbxml"	=> "application/vnd.wap.wbxml",
             "wmlc"		=> "application/vnd.wap.wmlc",
             "wmlsc"	=> "application/vnd.wap.wmlscriptc",
             "xla"		=> "application/vnd.ms-excel",
             "xls"		=> "application/vnd.ms-excel",
             "xlt"		=> "application/vnd.ms-excel",
             "ppt"		=> "application/vnd.ms-powerpoint",
             "csh"		=> "application/x-csh",
             "dcr"		=> "application/x-director",
             "dir"		=> "application/x-director",
             "dxr"		=> "application/x-director",
             "spl"		=> "application/x-futuresplash",
             "gtar"		=> "application/x-gtar",
             "php"		=> "application/x-httpd-php",
             "php3"		=> "application/x-httpd-php",
             "php4"		=> "application/x-httpd-php",
             "php5"		=> "application/x-httpd-php",
             "phtml"	=> "application/x-httpd-php",
             "js"		  => "application/x-javascript",
             "sh"		  => "application/x-sh",
             "swf"		=> "application/x-shockwave-flash",
             "sit"		=> "application/x-stuffit",
             "tar"		=> "application/x-tar",
             "tcl"		=> "application/x-tcl",
             "xhtml"	=> "application/xhtml+xml",
             "xht"		=> "application/xhtml+xml",
             "xhtml"	=> "application/xml",
             "ent"		=> "application/xml-external-parsed-entity",
             "dtd"		=> "application/xml-dtd",
             "mod"		=> "application/xml-dtd",
             "gz"		  => "application/x-gzip",
             "zip"		=> "application/zip",
             "au"		  => "audio/basic",
             "snd"		=> "audio/basic",
             "mid"		=> "audio/midi",
             "midi"		=> "audio/midi",
             "kar"		=> "audio/midi",
             "mp1"		=> "audio/mpeg",
             "mp2"		=> "audio/mpeg",
             "mp3"		=> "audio/mpeg",
             "aif"		=> "audio/x-aiff",
             "aiff"		=> "audio/x-aiff",
             "m3u"		=> "audio/x-mpegurl",
             "ram"		=> "audio/x-pn-realaudio",
             "rm"		  => "audio/x-pn-realaudio",
             "rpm"		=> "audio/x-pn-realaudio-plugin",
             "ra"		  => "audio/x-realaudio",
             "wav"		=> "audio/x-wav",
             "bmp"		=> "image/bmp",
             "gif"		=> "image/gif",
             "jpeg"		=> "image/jpeg",
             "jpg"		=> "image/jpeg",
             "jpe"		=> "image/jpeg",
             "png"		=> "image/png",
             "tiff"		=> "image/tiff",
             "tif"		=> "image/tif",
             "wbmp"		=> "image/vnd.wap.wbmp",
             "pnm"		=> "image/x-portable-anymap",
             "pbm"		=> "image/x-portable-bitmap",
             "pgm"		=> "image/x-portable-graymap",
             "ppm"		=> "image/x-portable-pixmap",
             "xbm"		=> "image/x-xbitmap",
             "xpm"		=> "image/x-xpixmap",
        	   "ics"		=> "text/calendar",
        	   "ifb"		=> "text/calendar",
             "css"		=> "text/css",
             "html"		=> "text/html",
             "htm"		=> "text/html",
             "asc"		=> "text/plain",
             "txt"		=> "text/plain",
             "rtf"		=> "text/rtf",
             "sgml"		=> "text/x-sgml",
             "sgm"		=> "text/x-sgml",
             "tsv"		=> "text/tab-seperated-values",
             "wml"		=> "text/vnd.wap.wml",
             "wmls"		=> "text/vnd.wap.wmlscript",
             "xsl"		=> "text/xml",
             "mpeg"		=> "video/mpeg",
             "mpg"		=> "video/mpeg",
             "mpe"		=> "video/mpeg",
             "qt"		  => "video/quicktime",
             "mov"		=> "video/quicktime",
             "avi"		=> "video/x-msvideo",
        );
        
        return $types;
    }
}

?>
