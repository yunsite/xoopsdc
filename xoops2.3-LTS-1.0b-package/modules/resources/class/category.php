<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class ResourcesCategory extends XoopsObject
{
    function __construct() {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_name', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('cat_description', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('cat_image', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('cat_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT, null, false);
        $this->initVar('grate_time', XOBJ_DTYPE_INT, null, false);
        $this->initVar('update_time', XOBJ_DTYPE_INT, null, false);
    }
    
    function getForm($action = false)
    {
        global $xoopsModuleConfig;
        
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $title = $this->isNew() ? '添加分类' : '更新分类';
        $format = empty($format) ? "e" : $format;
        $status = $this->isNew() ? 1 : $this->getVar('cat_status');
        
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");
        
        //名称
        $form->addElement(new XoopsFormText('分类名称', 'cat_name', 60, 255, $this->getVar('cat_name', $format)), true);
        
        //题头图片
        $cat_image = new XoopsFormElementTray('题头图片','', 'image');
        if( $this->getVar('cat_image') ){
            $cat_image->addElement(new XoopsFormLabel('', '<img src="'.XOOPS_URL.'/uploads/resources/'.$this->getVar('cat_image').'"><br><br>'));
            $delete_check = new XoopsFormCheckBox('','delete_image');
            $delete_check->addOption(1,_DELETE);
            $cat_image->addElement($delete_check);
            $display = '从新上传将会覆盖现有文件';
        }else{
            $display = '';
        }
        $cat_image->addElement(new XoopsFormFile('','cat_image',1024*1024*2));
        $cat_image->addElement(new XoopsFormLabel('','允许上传的类型为jpeg,pjpeg,gif,png文件'));
        $cat_image->addElement(new XoopsFormLabel('', $display)); 	
        $form->addElement($cat_image);

        //服务介绍
        $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>$this->getVar('cat_desc')); 
        $form->addElement(new XoopsFormEditor('分类描述', 'cat_desc',$configs));
       
        //状态
        $form->addElement(new XoopsFormRadioYN('是否显示', 'cat_status', $status));
        
        // gratetime
        if($this->isNew()) $form->addElement(new XoopsFormHidden('grate_time', time()));
        
        $form->addElement(new XoopsFormHidden('cat_id', $this->getVar('cat_id')));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;         
    }
}

class ResourcesCategoryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'res_category', 'ResourcesCategory', 'cat_id', 'cat_name');
    }
}

?>
