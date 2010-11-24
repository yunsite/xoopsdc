<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(2, "");

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'list';
$model_id = isset($_REQUEST['model_id']) ? $_REQUEST['model_id'] : '';

$model_handler = xoops_getmodulehandler('model','newsletter');

switch ($op) {
    default:
    case 'list':
    
    $model = '';
    
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('model_type', 'automatic'));
    if($model_handler->getCount($criteria)) {
        $model_obj = current($model_handler->getAll($criteria));
        $model = $model_obj->getValues(null, 'n');
        
        $model['last_create_time'] = formatTimestamp($model['last_create_time']);
        $model['next_create_time'] = formatTimestamp($model['next_create_time']);

        $time_difference = $model['time_difference'];
        if($model['peried'] == "day") {
            $hour = floor($time_difference/3600);
            $minute = floor(($time_difference-($hour*3600))/60);
            $model['time_difference'] = "每天固定{$hour}點{$minute}分發送";
        } elseif ($model['peried'] == 'week') {
            $selected_week = floor($time_difference/(24*3600));
            $hour_and_minute = $time_difference-($selected_week*24*3600);
            $hour = floor($hour_and_minute/3600);
            $minute = floor(($hour_and_minute-($hour*3600))/60);
            $model['time_difference'] = "每周固定星期{$selected_week} {$hour}點{$minute}分發送";
        } else {
            $selected_date = floor($time_difference/(24*3600));
            $hour_and_minute = $time_difference-($selected_date*24*3600);
            $hour = floor($hour_and_minute/3600);
            $minute = floor(($hour_and_minute-($hour*3600))/60);
            $model['time_difference'] = "每月固定{$selected_date}號 {$hour}點{$minute}分發送";
        }

    } 

    $xoopsTpl->assign('model', $model);
    $xoopsTpl->display("db:newsletter_admin_automatic.html");
break;
    
    case 'edit':
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('model_type', 'automatic'));
    if($model_handler->getCount($criteria)) {
        $model_obj = current($model_handler->getAll($criteria));
    } else {
        $model_obj = $model_handler->create();
    }
    $action = 'admin.automatic.php';
    $form = $model_obj->getForm($action);
    $form->assign($xoopsTpl);
    
    $xoopsTpl->display("db:newsletter_admin_automatic_form.html");
break;

    case 'save':
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin.automatic.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
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
    
    $peried = isset($_REQUEST['peried']) ? trim($_REQUEST['peried']) : 'day';
    $time_difference = 0;
    $date = 0;
    $week = 0;
    $hour = 0;
    $minute = 0;
    if($peried == 'day') {
        $hour = isset($_REQUEST['day']['hour']) ? intval($_REQUEST['day']['hour']) : 0;
        $minute = isset($_REQUEST['day']['minute']) ? intval($_REQUEST['day']['minute']) : 0;
        $current_time = date('H',time())*3600+date('i',time())*60;
        $time = strtotime(date('Y',time()).'-'.date('m',time()).'-'.date('d',time()));
        if($current_time < ($hour*3600+$minute*60)) {
            $next_create_time = $time+$hour*3600+$minute*60;
        } else {
            $next_create_time = $time+((24*3600)-($current_time-($hour*3600+$minute*60)));
        }
    } elseif ($peried == 'week') {
        $week = isset($_REQUEST['week']['week']) ? intval($_REQUEST['week']['week']) : 1;
        $hour = isset($_REQUEST['week']['hour']) ? intval($_REQUEST['week']['hour']) : 0;
        $minute = isset($_REQUEST['week']['minute']) ? intval($_REQUEST['week']['minute']) : 0;
        $time_difference = $time_difference+$week*24*3600;
        $current_time = date('N',time())*24*3600+date('H',time())*3600+date('i',time())*60;
        $time = strtotime(date('Y',time()).'-'.date('m',time()).'-'.date('d',time()).' 00:00:00');
        if($current_time < ($week*24*3600+$hour*3600+$minute*60)) {
            $next_create_time = $time+($week*24*3600+$hour*3600+$minute*60)-$current_time;
        } else {
            $next_create_time = $time+((7*24*3600)+($week*24*3600+$hour*3600+$minute*60))-($current_time);
        }
    } else {
        $date = isset($_REQUEST['date']['date']) ? intval($_REQUEST['date']['date']) : 1;
        $hour = isset($_REQUEST['date']['hour']) ? intval($_REQUEST['date']['hour']) : 0;
        $minute = isset($_REQUEST['date']['minute']) ? intval($_REQUEST['date']['minute']) : 0;
        $time_difference = $time_difference+$date*24*3600;
        $current_time = date('j',time())*24*3600+date('H',time())*3600+date('i',time())*60;
        if($current_time < ($date*24*3600+$hour*3600+$minute*60)) {
            $time = strtotime(date('Y',time()).'-'.date('m',time()).'-'.$date);
            $next_create_time = $time+$hour*3600+$minute*60;
        } else {
            $time = strtotime(date('Y',time()).'-'.date('m',time()).'-'.date('d',time()));
            $next_create_time = $time+(date('t')*24*3600)-(date('j',time())*24*3600)+($date*24*3600+$hour*3600+$minute*60);
        }
    }
    
    if($hour > 24) $hour = 23;
    if($minute > 60) $minute = 59;
    $time_difference = $time_difference+$hour*3600;
    $time_difference = $time_difference+$minute*60;
    $model_obj->setVar('time_difference', $time_difference);
    
    /*
    如果按天，必须知道现在的时间大于或小于每天的发送周期(小时)，如果小于预设时间，则下次通知时间为当前年，月，日，预设小时和分钟，如果大于则24小时减去(当前小时和分钟-预设小时和分钟)
    如果按星期，必须知道现在是周几(周几+小时+分钟),大于或小于预设时间(周几+小时+分钟),如果小于预设，则下次通知时间为当前年，月，预设周几小时和分钟，如果大于7天减去(当前周几小时和分钟-预设周几小时和分钟)
    如果按月份，必须知道现在是几号(几号+小时+分钟)，大于或小于预设时间(几号+小时+分钟)如果小于预设，则下次通知时间为当前年，月，预设日，小时和分钟如果大于30天减去(当前日小时和分钟-预设日小时和分钟)
    */ 
    $model_obj->setVar('next_create_time', $next_create_time);
    
    if($model_obj->isNew()) $model_obj->setVar('last_create_time', time());
    $model_obj->setVar('model_type', 'automatic');
    
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
        redirect_header('admin.automatic.php', 3, '保存成功！'); 
    }  else {
        echo $model_handler->getHtmlErrors();
        redirect_header('admin.automatic.php', 3, '保存有誤！'); 
    }
    break;
}

include 'footer.php';
?>
