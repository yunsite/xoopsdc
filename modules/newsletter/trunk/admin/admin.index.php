<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(1, "");

$subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');
$subscribelog_handler = xoops_getmodulehandler('subscribelog','newsletter');
$model_handler = xoops_getmodulehandler('model','newsletter');

//獲取當前月份訂閱人數信息
$start_time = time();
$start_time = formatTimestamp($start_time, 'Y-m-d');
$start_time = explode('-', $start_time);
$start_time = strtotime($start_time[0].'-'.$start_time[1].'-01');
$end_time = time();
$result['start_time'] = formatTimestamp($start_time, 'Y-m-d');
$result['end_time'] = formatTimestamp($end_time, 'Y-m-d');
$result['timestamp'] = $start_time;
$result['next_timestamp'] = $end_time;
$result['subscribe'] = 0;
$result['unsubscribe'] = 0;
$result['count'] = 0;

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('subscribe_timestamp', $start_time, '>='), 'AND');
$criteria->add(new Criteria('subscribe_timestamp', $end_time, '<'), 'AND');
$criteria->setSort('subscribe_timestamp');
$criteria->setOrder('desc');
$logs = $subscribelog_handler->getAll($criteria, null, false);

if(!empty($logs)) {
    foreach ($logs as $k=>$v) { 
        if( ($v['subscribe_timestamp'] >= $result['timestamp']) && ($v['subscribe_timestamp'] < $result['next_timestamp']) ) {
            if($v['subscribe_action'] == 1) {
                $result['subscribe'] = $result['subscribe']+1;
            } else {
                $result['unsubscribe'] = $result['unsubscribe']+1;
            }
            $result['count'] = $result['count']+1;
            unset($logs[$k]);
        }
    }
}
$xoopsTpl->assign('result', $result);

//獲取電子報設置信息
$model_automatic = '';
$model_manual = '';

$model_objs = $model_handler->getAll(null);

if(!empty($model_objs)) {
    foreach ($model_objs as $key=>$model_obj) {
        if($model_obj->getVar('model_type') == 'automatic') {
            $model_automatic = $model_obj->getValues(null, 'n');
            $model_automatic['last_create_time'] = formatTimestamp($model_automatic['last_create_time']);
            $time_difference = $model_automatic['time_difference'];
            if($model_automatic['peried'] == "day") {
                $hour = floor($time_difference/3600);
                $minute = floor(($time_difference-($hour*3600))/60);
                $model_automatic['time_difference'] = "每天固定{$hour}點{$minute}分發送";
            } elseif ($model_automatic['peried'] == 'week') {
                $selected_week = floor($time_difference/(24*3600));
                $hour_and_minute = $time_difference-($selected_week*24*3600);
                $hour = floor($hour_and_minute/3600);
                $minute = floor(($hour_and_minute-($hour*3600))/60);
                $model_automatic['time_difference'] = "每周固定星期{$selected_week} {$hour}點{$minute}分發送";
            } else {
                $selected_date = floor($time_difference/(24*3600));
                $hour_and_minute = $time_difference-($selected_date*24*3600);
                $hour = floor($hour_and_minute/3600);
                $minute = floor(($hour_and_minute-($hour*3600))/60);
                $model_automatic['time_difference'] = "每月固定{$selected_date}號 {$hour}點{$minute}分發送";
            }
        } else {
            $model_manual = $model_obj->getValues(null, 'n');
            $model_manual['time_difference'] = formatTimestamp($model_manual['time_difference']);
            $model_manual['last_create_time'] = formatTimestamp($model_manual['last_create_time']);
        }
    }
}

$xoopsTpl->assign('model_automatic', $model_automatic);
$xoopsTpl->assign('model_manual', $model_manual);
$xoopsTpl->display("db:newsletter_admin_index.html");

include 'footer.php';
?>
