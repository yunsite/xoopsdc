<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class PortfolioService extends XoopsObject
{
    function __construct() {
        $this->initVar('service_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('service_pid', XOBJ_DTYPE_INT, 0);
        $this->initVar('service_name', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('service_menu_name', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('service_image', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('service_desc', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('service_pushtime', XOBJ_DTYPE_INT);
        $this->initVar('service_datetime', XOBJ_DTYPE_INT);
        $this->initVar('service_status', XOBJ_DTYPE_INT,0);
        $this->initVar('service_weight', XOBJ_DTYPE_INT,0);
        $this->initVar('service_tpl', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 0);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);
    }
    
    function getForm($action = false)
    {    
        global $xoopsModuleConfig;
        
        include_once XOOPS_ROOT_PATH . "/modules/portfolio/include/functions.render.php";
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $title = $this->isNew() ? '添加服务' : '更新服务';
        $format = empty($format) ? "e" : $format;
        $status = $this->isNew() ? 1 : $this->getVar('service_status');
        
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");
        
        //名称
        $form->addElement(new XoopsFormText('案例名称', 'service_name', 60, 255, $this->getVar('service_name', $format)), true);
        $form->addElement(new XoopsFormText('页面标题', 'service_menu_name', 60, 255, $this->getVar('service_menu_name', $format)));
        
        //题头图片
        $service_image = new XoopsFormElementTray('题头图片','', 'image');
        if( $this->getVar('service_image') ){
            $service_image->addElement(new XoopsFormLabel('', '<img src="'.XOOPS_URL.'/uploads/portfolio/'.$this->getVar('service_image').'"><br><br>'));
            $delete_check = new XoopsFormCheckBox('','delete_image');
            $delete_check->addOption(1,_DELETE);
            $service_image->addElement($delete_check);
            $display = '从新上传将会覆盖现有文件';
        }else{
            $display = '';
        }
        $service_image->addElement(new XoopsFormFile('','service_image',1024*1024*2));
        $service_image->addElement(new XoopsFormLabel('','允许上传的类型为jpeg,pjpeg,gif,png文件'));
        $service_image->addElement(new XoopsFormLabel('', $display)); 	
        $form->addElement($service_image);

        //服务介绍
        $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>$this->getVar('service_desc')); 
        $form->addElement(new XoopsFormEditor('服务介绍', 'service_desc',$configs), false);
        
        //模板选择
        $templates = portfolio_getTemplateList("service");
        if (count($templates)>0) {
            $template_select = new XoopsFormSelect('服务模板', "service_tpl", $this->getVar("service_tpl"));
            $template_select->addOptionArray($templates);
            $form->addElement($template_select);
        }
        
        //状态
        $form->addElement(new XoopsFormRadioYN('是否显示', 'service_status', $status));
        
        //创建时间
        if($this->isNew()) $form->addElement(new XoopsFormHidden('service_pushtime', time()));
        
        $form->addElement(new XoopsFormHidden('service_id', $this->getVar('service_id')));
        $form->addElement(new XoopsFormHidden('ac', 'insert'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;     
    }
}

class PortfolioServiceHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'portfolio_service', 'PortfolioService', 'service_id', 'service_name');
    }

    function &getTrees($pid = 0, $prefix = "--", $tags = array())
    {
        $pid = intval($pid);
        if (!is_array($tags) || count($tags) == 0) $tags = array("service_id","service_pid", "service_name", "service_menu_name", "service_image", "service_desc", "service_status", "service_weight", "service_tpl", "service_pushtime", "service_datetime");
        $criteria = new CriteriaCompo();
        $criteria->setSort('service_weight');
        $criteria->setOrder('ASC');
        $service_tree = $this->getAll($criteria, $tags);
        require_once dirname(__FILE__) . "/tree.php";
        $tree = new portfolioTree($service_tree);
        $service_array =& $tree->makeTree($prefix, $pid, $tags);

        return $service_array;
    }
}

?>
