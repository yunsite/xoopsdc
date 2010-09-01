<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class PortfolioImages extends XoopsObject
{
    function __construct() {
        $this->initVar('image_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('case_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('image_title', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('image_desc', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('image_file', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('image_status', XOBJ_DTYPE_INT,0);
        $this->initVar('image_weight', XOBJ_DTYPE_INT,0);
    }
}

class PortfolioImagesHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'portfolio_images', 'PortfolioImages', 'image_id', 'image_name');
    }
}

?>
