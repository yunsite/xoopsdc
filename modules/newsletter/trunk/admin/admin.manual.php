<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(3, "");

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'list';
$model_id = isset($_REQUEST['model_id']) ? $_REQUEST['model_id'] : '';

$model_handler = xoops_getmodulehandler('model','newsletter');

switch ($op) {
default:
case 'list':
    
    $model = '';

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('model_type', 'manual'));
    
    if($model_handler->getCount($criteria)) {
        $model_obj = current($model_handler->getAll($criteria));
        $model = $model_obj->getValues(null, 'n');
        
        $model['time_difference'] = formatTimestamp($model['time_difference']);
        $model['last_create_time'] = formatTimestamp($model['last_create_time']);
    } 

    $xoopsTpl->assign('model', $model);
    $xoopsTpl->display("db:newsletter_admin_manual.html");
    break;
    
case 'edit':
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('model_type', 'manual'));
    if($model_handler->getCount($criteria)) {
        $model_obj = current($model_handler->getAll($criteria));
    } else {
        $model_obj = $model_handler->create();
    }
    $action = 'admin.manual.php';
    $form = $model_obj->getForm($action, 'manual');
    $form->assign($xoopsTpl);
    
    $xoopsTpl->display("db:newsletter_admin_manual_form.html");
    break;

case 'save':
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin.manual.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }   
 
    if (isset($model_id)) {
        $model_obj =& $model_handler->get($model_id);
        $header_img = $model_obj->getVar('header_img');
    } else {
        $model_obj =& $model_handler->create();
    }

    foreach(array_keys($model_obj->vars) as $key) {
        if(isset($_POST[$key])) {
            $model_obj->setVar($key, $_POST[$key]);
        }
    }
    
    $model_obj->setVar('peried', 'manual');
    
    $time_difference = isset($_POST["time_difference"]["date"]) ? strtotime($_POST["time_difference"]["date"]) : 0 ;   
    $model_obj->setVar('time_difference', $time_difference + $_POST["time_difference"]["time"]);
    
    $model_obj->setVar('next_create_time', $time_difference + $_POST["time_difference"]["time"]);
    
    if($model_obj->isNew()) $model_obj->setVar('last_create_time', time());
    $model_obj->setVar('model_type', 'manual');
    
    if ( !empty($_POST["xoops_upload_file"]) ){
        include_once XOOPS_ROOT_PATH."/class/uploader.php";
        include_once XOOPS_ROOT_PATH."/modules/newsletter/include/functions.php";
        $dir = XOOPS_ROOT_PATH . "/uploads/newsletter/";
        $original_dir = NewsletterCreateDir( $dir );
        $mid_dir = NewsletterCreateDir( $dir ); 
        $thumb_dir = NewsletterCreateDir( $dir ); 
        $mid_wh = array(360,360);
        $thumb_wh = array(300,300);
        $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
        $maxfilesize = 500000000;
        $maxfilewidth = 2000;
        $maxfileheight = 2000;
        $uploader = new XoopsMediaUploader($original_dir, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight); 
        if ($uploader->fetchMedia('header_img')) {
        $uploader->setPrefix('newsletter_header_');
            if (!$uploader->upload()) {
                echo $uploader->getErrors();
            } else {
                $model_obj->setVar('header_img', $uploader->getSavedFileName());
                setImageThumb($original_dir, $uploader->getSavedFileName(), $mid_dir, 'mid_'.$uploader->getSavedFileName(), array($mid_wh[0], $mid_wh[1]));
                setImageThumb($original_dir, $uploader->getSavedFileName(), $thumb_dir, 'thumb_'.$uploader->getSavedFileName(), array($thumb_wh[0], $thumb_wh[1]));
                if(!empty($header_img)){
                    unlink(str_replace("\\", "/", realpath($original_dir.$header_img)));
                    unlink(str_replace("\\", "/", realpath($mid_dir.'mid_'.$header_img)));
                    unlink(str_replace("\\", "/", realpath($thumb_dir.'thumb_'.$header_img)));
                } 
            }
        }
    }
    
    if ($model_handler->insert($model_obj)) {
        redirect_header('admin.manual.php', 3, '保存成功！'); 
    }  else {
        echo $model_handler->getHtmlErrors();
        redirect_header('admin.manual.php', 3, '保存有誤！'); 
    }
    break;
}

include 'footer.php';
?>
