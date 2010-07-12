<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class PortfolioCase extends XoopsObject
{
    function __construct() {
        $this->initVar('case_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('service_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('case_title', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('case_menu_title', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('case_image', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('case_summary', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('case_description', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('case_pushtime', XOBJ_DTYPE_INT, null, false);
        $this->initVar('case_datetime', XOBJ_DTYPE_INT, null, false);
        $this->initVar('case_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('case_weight', XOBJ_DTYPE_INT, null, false);
        $this->initVar('case_tpl', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0, false);
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
        include_once XOOPS_ROOT_PATH . "/modules/portfolio/include/functions.php";
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        
        $service_handler = xoops_getmodulehandler('service','portfolio');
        $cs_handler = xoops_getmodulehandler('cs','portfolio');
        $images_handler = xoops_getmodulehandler('images','portfolio');
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $title = $this->isNew() ? '添加案例' : '更新案例';
        $format = empty($format) ? "e" : $format;
        $status = $this->isNew() ? 1 : $this->getVar('case_status');
        
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");
        
        //名称
        $form->addElement(new XoopsFormText('案例名称', 'case_title', 60, 255, $this->getVar('case_title', $format)), true);
        $form->addElement(new XoopsFormText('页面标题', 'case_menu_title', 60, 255, $this->getVar('case_menu_title', $format)));
        
        //所属服务
        $service_ids = array();
        $cs = '';
        if($this->getVar('case_id')) $cs = $cs_handler->getServerIds(array($this->getVar('case_id')));
        if(!empty($cs)) {
            foreach ($cs as $k=>$v) {
                $service_ids[$v['service_id']] = $v['service_id'];
            }
        }

        $services = $service_handler->getTrees(0, "--");
        $service_options = array();
        if(!empty($services)){
            foreach ($services as $id => $cat) {
                $service_options[$id] = $cat["prefix"] . $cat["service_menu_name"];
            }
        }

        $service_select = new XoopsFormCheckBox("所属服务", "service_ids", array_keys($service_ids));
        $service_select->addOptionArray($service_options);
        $form->addElement($service_select);

        //题头图片
        $case_image = new XoopsFormElementTray('题头图片','', 'image');
        if( $this->getVar('case_image') ){
            $case_image->addElement(new XoopsFormLabel('', '<img width="100" src="'.XOOPS_URL.'/uploads/portfolio/'.$this->getVar('case_image').'"><br><br>'));
            $delete_check = new XoopsFormCheckBox('','delete_image');
            $delete_check->addOption(1,_DELETE);
            $case_image->addElement($delete_check);
            $display = '从新上传将会覆盖现有文件';
        }else{
            $display = '';
        }
        $case_image->addElement(new XoopsFormFile('','case_image',1024*1024*2));
        $case_image->addElement(new XoopsFormLabel('','允许上传的类型为jpeg,pjpeg,gif,png文件'));
        $case_image->addElement(new XoopsFormLabel('', $display)); 	
        $form->addElement($case_image);
        
        //案例相册
        $gallery = new XoopsFormElementTray('案例相册','','gallery');
        
        if (!$this->isNew()) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('case_id', $this->getVar('case_id')));
            $images = $images_handler->getAll($criteria, null, false, false);
            if(!empty($images)) {
                $list = '已有图片列表<input id="check" name="check" type="checkbox" onclick="xoopsCheckAll(\'form\',\'check\');" />全部';
                $list .= '<ul class="ItemsList">';
                foreach($images as $k=>$v){
                    $list .= '<li>';
                    $list .= '
                        <img width="100" src="'.XOOPS_URL.'/uploads/portfolio/gallery/'.$v['image_file'].'">
                        <div align="center">
                            <input type="checkbox" value="'.$v['image_id'].'" name="del_image_ids[]">
                            名称：&nbsp;<input type="text" value="'.$v['image_title'].'" maxlength="255" size="10" name="image_title['.$v['image_id'].']"><br />
                            图片描述：&nbsp;<textarea cols="50" rows="5" name=image_desc['.$v['image_id'].']">'.$v['image_desc'].'</textarea>
                        </div>
                    ';
                    $list .= '</li>';
                }
                $list .= '</ul>';
                $gallery->addElement(new XoopsFormLabel('',$list));
                $gallery->addElement(new XoopsFormLabel('','<br style="clear: both;"/><br />(勾选图片提交后将被清除)'));
            }
        }           
        
        $gallery->addElement(new XoopsFormLabel('','<div><a id="addMore" href="javascript:void(0);">添加相册内容</a></div>'));
        $form->addElement($gallery);  
        
        //摘要
        $form->addElement( new XoopsFormTextArea('案例摘要','case_summary', $this->getVar('case_summary'), 5, 100));
        
        //案例介绍
        $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>$this->getVar('case_description')); 
        $form->addElement(new XoopsFormEditor('案例介绍', 'case_description',$configs));
        
        //模板选择
        $templates = portfolio_getTemplateList("case");
        if (count($templates)>0) {
            $template_select = new XoopsFormSelect("案例模板", "case_tpl", $this->getVar("case_tpl"));
            $template_select->addOptionArray($templates);
            $form->addElement($template_select);
        }
        
        //状态
        $form->addElement(new XoopsFormRadioYN('是否显示', 'case_status', 1));

        //创建时间
        if($this->isNew()) $form->addElement(new XoopsFormHidden('case_pushtime', time()));
        
        $form->addElement(new XoopsFormHidden('case_id', $this->getVar('case_id')));
        $form->addElement(new XoopsFormHidden('ac', 'insert'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;     
    }
}

class PortfolioCaseHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'portfolio_case', 'PortfolioCase', 'case_id', 'case_title');
    }
}

?>
