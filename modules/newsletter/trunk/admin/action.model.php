<?php
include 'header.php';
xoops_cp_header();

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'save';
$mode_id = isset($_REQUEST['mode_id']) ? $_REQUEST['mode_id'] : '';

$model_handler = xoops_getmodulehandler('model','newsletter');

switch ($op) {
default:
case 'save':
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header($_SERVER['REQUEST_URI'], 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }   
 
    if (isset($mode_id)) {
        $model_obj =& $model_handler->get($mode_id);
    } else {
        $model_obj =& $model_handler->create();
    }

    foreach(array_keys($model_obj->vars) as $key) {
        if(isset($_POST[$key])) {
            $model_obj->setVar($key, $_POST[$key]);
        }
    }
    
    $peried = isset($_REQUEST['peried']) ? trim($_REQUEST['peried']) : 'day';
    $date = 0;
    $week = 0;
    $hour = 0;
    $minute = 0;
    
    if($peried == 'day') {
        $hour = isset($_REQUEST['day']['hour']) ? intval($_REQUEST['day']['hour']) : 0;
        $minute = isset($_REQUEST['day']['minute']) ? intval($_REQUEST['day']['minute']) : 0;
    } elseif ($peried == 'week') {
        $week = isset($_REQUEST['week']['week']) ? intval($_REQUEST['week']['week']) : 1;
        $hour = isset($_REQUEST['week']['hour']) ? intval($_REQUEST['week']['hour']) : 0;
        $minute = isset($_REQUEST['week']['minute']) ? intval($_REQUEST['week']['minute']) : 0;
    } else {
        $date = isset($_REQUEST['date']['date']) ? intval($_REQUEST['date']['date']) : 0;
        $hour = isset($_REQUEST['date']['hour']) ? intval($_REQUEST['date']['hour']) : 0;
        $minute = isset($_REQUEST['date']['minute']) ? intval($_REQUEST['date']['minute']) : 0;
    }
    
    if($model_obj->isNew()) $model_obj->setVar('last_create_time', time());
    break;
}

include 'footer.php';

?>
