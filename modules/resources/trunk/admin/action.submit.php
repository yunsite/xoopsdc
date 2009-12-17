<?php
include "header.php";
$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : "";
$pass = isset($_POST["pass"]) ? $_POST["pass"] : 0;
$ac = isset($_POST["ac"]) ? $_POST["ac"] : "";
$myts =& MyTextSanitizer::getInstance();
switch ($op) {
    case "verify":
        switch ($ac) {
            default:
                $_resources_ids = isset($_POST["resources_ids"]) ? $_POST["resources_ids"] : 0;
                $resources_ids = serialize($_resources_ids);
                $passdesc = !empty($pass ) ? _SUBMIT : _CANCEL ;
                xoops_confirm(array("resources_ids"=>$resources_ids,"ac"=>"saveverify","op"=>"verify","passval"=>$pass),"action.submit.php","确定[ {$passdesc} ]这些记录？");
            break;
            
            case "saveverify":
                if (!$GLOBALS['xoopsSecurity']->check()) {
		            redirect_header("index.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
		            exit();
		        }
		        $resources_handler = xoops_getmodulehandler("resources");
                $_resources_ids = $_POST["resources_ids"];
                
				$resources_ids = unserialize($myts->stripSlashesGPC($_resources_ids));
				$passval = isset($_POST["passval"]) ? intval($_POST["passval"]) : 0;
				
                $criteria = new CriteriaCompo(new Criteria("resources_id",$resources_ids,"in"));
                
                if ( $resources_handler->updateAll("resources_state",$passval,$criteria)) {
                    $stop = "操作成功";
                } else {
                    $stop = "操作失败";
                }
                redirect_header("index.php", 5, $stop);
            break;
        }
        
    break;
}

include "footer.php";
?>