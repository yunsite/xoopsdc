<?php
 
if (!defined("XOOPS_ROOT_PATH")) { exit(); }

function portfolio_search($queryarray, $andor, $limit, $offset, $userid)

{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;
    
    $sql = "SELECT service_id, service_name,service_menu_name,service_pushtime FROM ".$xoopsDB->prefix("portfolio_service")."";
        
    if ( is_array($queryarray) && $count = count($queryarray) ) {
        $sql .= " WHERE ((service_name LIKE '%$queryarray[0]%' OR  service_menu_name LIKE '%$queryarray[0]%')";
        for($i=1;$i<$count;$i++){
            $sql .= " $andor ";
            $sql .= "(service_name '%$queryarray[$i]%' OR  service_menu_name LIKE '%$queryarray[$i]%')";
        }
        $sql .= ") ";
    }

    $sql .= "ORDER BY service_id DESC";
    
    $query = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("portfolio_service")." WHERE service_id>0");
    list($numrows) = $xoopsDB->fetchrow($query);
    
    $result = $xoopsDB->query($sql,$limit,$offset);
    $ret = array();
    $i = 0;
    
    while($myrow = $xoopsDB->fetchArray($result)){
        $ret[$i]['image'] = "images/url.gif";  
        $ret[$i]['link'] = "case.php?service_id=".$myrow['service_id'];
        $ret[$i]['title'] = $myrow['service_name'];
        $ret[$i]['time'] = $myrow['service_pushtime'];
        $ret[$i]['uid'] = "";
        $i++;
    }
        
    return $ret;

}

?>
