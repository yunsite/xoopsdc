<?php
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class NewsletterModel extends XoopsObject
{
    function __construct() {
        $this->initVar('model_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('peried', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('time_difference', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('next_create_time', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('last_create_time', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tpl_name', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('status', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('model_type', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('model_title', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('header_img', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('header_desc', XOBJ_DTYPE_TXTAREA);
        $this->initVar('footer_desc', XOBJ_DTYPE_TXTAREA);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 0);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0);
    }
    
    function getForm($action = false, $type = 'automatic')
    {
        include_once XOOPS_ROOT_PATH . "/modules/newsletter/include/functions.render.php";
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        
        if ($action === false) $action = $_SERVER['REQUEST_URI'];
        $format = empty($format) ? "e" : $format;
        $status = $this->isNew() ? 1 : $this->getVar('status');

        $form = new XoopsThemeForm('電子報發送設定', 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");
        
        $form->addElement(new XoopsFormRadioYN('是否啟用每天自動發送電子報功能', 'status', $status));
        
        $peried = $this->isNew() ? 'day' : $this->getVar('peried');
        $time_difference = $this->getVar('time_difference');
        
        $form->addElement(new XoopsFormText('電子報名稱', 'model_title', 60, 255, $this->getVar('model_title')), true);
        
        if($type == 'automatic') {
            $weeks = array(1=>'星期一', 2=>'星期二', 3=>'星期三', 4=>'星期四', 5=>'星期五', 6=>'星期六', 7=>'星期日');
            for ($i = 1; $i <= 31; $i++) {
                $dates[$i] = $i.'號';
            }
            
            $time = 0;
            $time_difference = $this->getVar('time_difference');
            $selected_date = 1;
            $selected_week = 1;
            
            $option = new XoopsFormElementTray('電子報自動發送的時間');
            $list = '';
            $list .= '<div><input type="radio" name="peried" value="day"';
            
            $hour = '';
            $minute = '';
            if($peried == "day") {
                $list .= ' checked="checked"';
                $hour = floor($time_difference/3600);
                $minute = floor(($time_difference-($hour*3600))/60);
            }
            
            $list .= ' />';
            $list .= '每天固定 <input type="text" value="'.$hour.'" maxlength="4" size="4" name="day[hour]">點<input type="text" value="'.$minute.'" maxlength="4" size="4" name="day[minute]">分發送</div>';
            $list .= '<div><input type="radio" name="peried" value="week"';
            
            $hour = '';
            $minute = '';
            if($peried == "week") {
                $list .= ' checked="checked"';
                $selected_week = floor($time_difference/(24*3600));
                $hour_and_minute = $time_difference-($selected_week*24*3600);
                $hour = floor($hour_and_minute/3600);
                $minute = floor(($hour_and_minute-($hour*3600))/60);
            }
            
            $list .= ' />';
            $list .= '每周固定 <select name="week[week]" size="1">';
            foreach ($weeks as $k=>$v) {
                $selected = '';
                if($selected_week == $k) $selected = " selected='selected'";
                $list .= "<option value='{$k}'{$selected}>{$v}</option>";
            }
            $list .= '</select><input type="text" value="'.$hour.'" maxlength="4" size="4" name="week[hour]">點<input type="text" value="'.$minute.'" maxlength="4" size="4" name="week[minute]">分發送</div>';
            $list .= '<div><input type="radio" name="peried" value="date"';
            
            $hour = '';
            $minute = '';
            if($peried == "date") {
                $list .= ' checked="checked"';
                $selected_date = floor($time_difference/(24*3600));
                $hour_and_minute = $time_difference-($selected_date*24*3600);
                $hour = floor($hour_and_minute/3600);
                $minute = floor(($hour_and_minute-($hour*3600))/60);
            }
            
            $list .= ' />';
            $list .= '每月固定 <select name="date[date]" size="1">';
            foreach ($dates as $k=>$v) {
                $selected = '';
                if($selected_date == $k) $selected = " selected='selected'";
                $list .= "<option value='{$k}'{$selected}>{$v}</option>";
            }
            $list .= '</select><input type="text" value="'.$hour.'" maxlength="4" size="4" name="date[hour]">點<input type="text" value="'.$minute.'" maxlength="4" size="4" name="date[minute]">分發送</div>';
            $option->addElement(new XoopsFormLabel('',$list));
            $form->addElement($option);    
        } else {
            $form->addElement(new XoopsFormDateTime("電子報手動發送的時間", 'time_difference', 15, $time_difference));
        }
        
        $header_img = new XoopsFormElementTray('電子報表頭圖片');
        if( $this->getVar('header_img') ){
            $header_img->addElement(new XoopsFormLabel('', '<img src="'.XOOPS_URL.'/uploads/newsletter/thumb_'.$this->getVar('header_img').'"><br><br>'));
            $display = '從新上傳將會覆寫現有圖片';
        }else{
            $display = '';
        }  
        $header_img->addElement(new XoopsFormFile('','header_img',1024*1024*2));
        $header_img->addElement(new XoopsFormLabel('','允許上傳的類型為jpeg,pjpeg,gif,png檔案'));
        $header_img->addElement(new XoopsFormLabel('', $display)); 	
        $form->addElement($header_img);
        
        $form->addElement(new XoopsFormEditor('電子報表頭文字', "header_desc", array('editor'=>'fckeditor','width'=>'100%','height'=>'300px','name'=>'item_summary', 'value'=>$this->getVar('header_desc')), false)); 
        $form->addElement(new XoopsFormEditor('電子報頁腳文字', "footer_desc", array('editor'=>'fckeditor','width'=>'100%','height'=>'300px','name'=>'item_summary', 'value'=>$this->getVar('footer_desc')), false)); 
        
        $templates = newsletter_getTemplateList($type);
        if (count($templates)>0) {
            $template_select = new XoopsFormSelect('電子報模板', "tpl_name", $this->getVar("tpl_name"));
            $template_select->addOptionArray($templates);
            $form->addElement($template_select);
        }
        
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('model_id', $this->getVar("model_id")));
        $form->addElement(new XoopsFormButton('', 'submit', '提交', 'submit'));
        return $form;
    }
}

class NewsletterModelHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db){
        parent::__construct($db, 'newsletter_model', 'NewsletterModel', 'model_id');
    }
}

?>
