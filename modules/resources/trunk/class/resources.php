<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class ResourcesResources extends XoopsObject
{
    function __construct() {    
        $this->initVar('res_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_id',XOBJ_DTYPE_INT, 0);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0);
        $this->initVar('res_subject', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('res_content', XOBJ_DTYPE_TXTAREA,"");
        $this->initVar('res_attachment', XOBJ_DTYPE_INT, 0);
        $this->initVar('res_status', XOBJ_DTYPE_INT, 0);
        $this->initVar('res_weight', XOBJ_DTYPE_INT, 0);
        $this->initVar('grate_time', XOBJ_DTYPE_INT);
        $this->initVar('update_time', XOBJ_DTYPE_INT);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);
        $this->initVar('res_counter', XOBJ_DTYPE_INT, 0);        
        $this->initVar('res_counter', XOBJ_DTYPE_INT, 0);
        $this->initVar('res_rating', XOBJ_DTYPE_INT, 0);
        $this->initVar('res_rates', XOBJ_DTYPE_INT, 0);           
    }
    
    function getRatingAverage($decimals = 2)
    {
	    $ave = 0;
	    if($this->getVar("res_rates")){
	    	$ave = number_format($this->getVar("res_rating")/$this->getVar("res_rates"), $decimals);
    	}
	    return $ave;
    }     
    
    function getForm($action = false)
    {
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $category_handler =& xoops_getmodulehandler('category', 'resources');
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $format = empty($format) ? "e" : $format;
        $status = $this->isNew() ? 1 : $this->getVar('res_status');
        
        $form = new XoopsThemeForm('资源管理', 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");
        
        // category
        $criteria = new CriteriaCompo();
      	$criteria->setSort('cat_weight');
      	$criteria->setOrder('ASC');
      	$categories = $category_handler->getList($criteria);
        $cat_select = new XoopsFormSelect('资源类别', 'cat_id', $this->getVar('cat_id'));
    	$cat_select->addOptionArray($categories);
    	$form->addElement($cat_select);
        
        // subject
        $form->addElement(new XoopsFormText('资源名称', 'res_subject', 60, 255, $this->getVar('res_subject', $format)), true);
        
        // content
        $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>$this->getVar('res_content', $format)); 
        $form->addElement(new XoopsFormEditor('资源描述', 'res_content', $configs));
        
        // resources
        $annex = new XoopsFormElementTray('资源附件','','annex');
        $annex_file = new XoopsFormFile('','annex','');
        $annex_multiLabel = new XoopsFormLabel('','<div><a id="addMore" href="javascript:void(0);">再添加一个附件</a></div>');
        $annex->addElement($annex_file);
        $annex->addElement($annex_multiLabel);  
        $form->addElement($annex);  
        
        //状态
        $form->addElement(new XoopsFormRadioYN('是否显示', 'res_status', $status));
        
        // gratetime
        if($this->isNew()) $form->addElement(new XoopsFormHidden('grate_time', time()));
        
        $form->addElement(new XoopsFormHidden('res_id', $this->getVar('res_id')));                       
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;
    }
}

class ResourcesResourcesHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'res_resources', 'ResourcesResources', 'res_id', 'res_subject');
    }
}

?>
