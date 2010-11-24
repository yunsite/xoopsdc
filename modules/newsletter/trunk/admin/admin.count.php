<?php
include 'header.php';
xoops_cp_header();
loadModuleAdminMenu(4, "");

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$post_time = isset($_REQUEST["begin"]) ? $_REQUEST["begin"] : '';
$sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'desc';
$start = isset( $_REQUEST['start'] ) ? trim($_REQUEST['start']) : 0;
$limit = 10;

if (!empty($post_time)) {
    $begin = isset($_REQUEST["begin"]["date"]) ? strtotime($_REQUEST["begin"]["date"]) : 0 ;
    $begin = $begin + $_REQUEST["begin"]["time"];
    $finish = isset($_REQUEST["finish"]["date"]) ? strtotime($_REQUEST["finish"]["date"]) : 0 ;
    $finish = $finish + $_REQUEST["finish"]["time"];   
} else {
    $begin = time();
    $finish = time(); 
}

$subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');
$subscribelog_handler = xoops_getmodulehandler('subscribelog','newsletter');

switch ($op) {
    default:
    case 'list':
    if($sort == 'desc') {
        $time = time();
    } else {
        $time = 0;
        $criteria = new CriteriaCompo();
        $criteria->setSort('subscribe_timestamp');
        $criteria->setOrder('asc');
        $criteria->setLimit(1);
        $criteria->setStart(0);
        $log = $subscribelog_handler->getAll($criteria, null, false);
    
        if(!empty($log)) {
            $log = current($log);
            $time = $log['subscribe_timestamp'];
        } else {
            $time = time();
        }
    }
    $time = formatTimestamp($time, 'Y-m-d');
    $time = explode('-', $time);
    $time = strtotime($time[0].'-'.$time[1].'-01');
    
    for ($i = 1; $i <= $limit; $i++) {
        if($i == 1) {
            if($sort == 'desc') {
                $_start_time = $time;
                $_end_time = time();
            } else {
                $_end_time = formatTimestamp($time+35*24*3600, 'Y-m-d');
                $_end_time = explode('-', $_end_time);
                $_end_time = strtotime($_end_time[0].'-'.$_end_time[1].'-01');
                $_end_time = $_end_time-1;
                $_start_time = $time;
            }
        } else {
            if($sort == 'desc') {
           
                $_start_time = $_time-500;
                $_end_time = $_time;
            } else {
                $_start_time = $_time+35*24*3600;
                $_end_time = $_time+35*24*3600*2;
                $_end_time = formatTimestamp($_end_time, 'Y-m-d');
                $_end_time = explode('-', $_end_time);
                $_end_time = strtotime($_end_time[0].'-'.$_end_time[1].'-01');
            }
        }
        
        $_time = formatTimestamp($_start_time, 'Y-m-d');
        $_time = explode('-', $_time);
        $_time = strtotime($_time[0].'-'.$_time[1].'-01');

        $result[$i]['start_time'] = formatTimestamp($_time, 'Y-m-d');
        $result[$i]['end_time'] = formatTimestamp($_end_time, 'Y-m-d');
        $result[$i]['timestamp'] = $_time;
        $result[$i]['next_timestamp'] = $_end_time;
        $result[$i]['subscribe'] = 0;
        $result[$i]['unsubscribe'] = 0;
        $result[$i]['count'] = 0;
        //unset($_time,$_end_time);
    }

    $start_time = $result;
    $end_time = $result;
    if($sort == 'desc') {
        $start_time = end($start_time);
        $start_time = $start_time['next_timestamp'];
        $end_time = current($end_time);
        $end_time = $end_time['timestamp'];
    
    } else {
        $start_time = current($start_time);
        $start_time = $start_time['timestamp'];
        $end_time = end($end_time);
        $end_time = $end_time['next_timestamp'];
    }
    
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('subscribe_timestamp', $start_time, '>='), 'AND');
    //$criteria->add(new Criteria('subscribe_timestamp', $end_time, '<'), 'AND');
    $criteria->setSort('subscribe_timestamp');
    $criteria->setOrder($sort);
    $logs = $subscribelog_handler->getAll($criteria, null, false);
    
    foreach ($result as $key=>$val) {
        if($val['timestamp'] > time()) unset($result[$key]);
    
        if(!empty($logs)) {
            foreach ($logs as $k=>$v) { 
                if( ($v['subscribe_timestamp'] >= $val['timestamp']) && ($v['subscribe_timestamp'] < $val['next_timestamp']) ) {
                    if($v['subscribe_action'] == 1) {
                        $result[$key]['subscribe'] = $result[$key]['subscribe']+1;
                    } else {
                        $result[$key]['unsubscribe'] = $result[$key]['unsubscribe']+1;
                    }
                    $result[$key]['count'] = $result[$key]['count']+1;
                    unset($logs[$k]);
                }
            }
        }
    }
    break;
    
    case 'search':
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('subscribe_timestamp', $begin, '>='), 'AND');
    $criteria->add(new Criteria('subscribe_timestamp', $finish, '<'), 'AND');
    $criteria->setSort('subscribe_timestamp');
    $criteria->setOrder($sort);
    $logs = $subscribelog_handler->getAll($criteria, null, false);
    
    $result[0]['start_time'] = formatTimestamp($begin, 'Y-m-d');
    $result[0]['end_time'] = formatTimestamp($finish, 'Y-m-d');
    $result[0]['timestamp'] = $begin;
    $result[0]['next_timestamp'] = $finish;
    $result[0]['subscribe'] = 0;
    $result[0]['unsubscribe'] = 0;
    $result[0]['count'] = 0;
    
    if(!empty($logs)) {
        foreach ($logs as $k=>$v) { 
            if($v['subscribe_action'] == 1) {
                $result[0]['subscribe'] = $result[0]['subscribe']+1;
            } else {
                $result[0]['unsubscribe'] = $result[0]['unsubscribe']+1;
            }
            $result[0]['count'] = $result[0]['count']+1;
            unset($logs[$k]);
        }
    }
    break;
}


$xoopsTpl->assign('result', $result);

$sort_list = array(
    'asc' => '遞增排序',
    'desc' => '遞減排序'
);
$xoopsTpl->assign('sort_list', $sort_list);
$xoopsTpl->assign('sort', $sort);

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
$beginout = new XoopsFormDateTime("從", 'begin', 15, @$begin);
$finishout = new XoopsFormDateTime("到", 'finish', 15, @$finish);         
$search['begin'] = $beginout->render();
$search['finish'] = $finishout->render();
$xoopsTpl->assign('search', $search);
$xoopsTpl->display("db:newsletter_admin_count.html");
include 'footer.php';

?>
