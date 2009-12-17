<?php
include "header.php";
$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : "";
$ac = isset($_POST["ac"]) ? $_POST["ac"] : "";

$attachments_handler = xoops_getmodulehandler("attachments");
$resources_handler = xoops_getmodulehandler("resources");

switch ($op) {
    case "attachment":
        switch ($ac){
            default:
                $att_id = isset($_GET["id"]) ? intval($_GET["id"]) :  0;
                $att_obj = $attachments_handler->get($att_id);
                $att_name = $att_obj->getVar("att_filename");
                xoops_confirm(array("att_id"=>$att_id,"op"=>"attachment","ac"=>"del"),"action.del.php","确定删除 [{$att_name}] 这个附件?");
            break;
            
            case "del":
                if (!$GLOBALS['xoopsSecurity']->check()) {
                    redirect_header("index.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
                    exit();
                }
                $att_id = isset($_POST["att_id"]) ? intval($_POST["att_id"]) :  0;
                $att_obj = $attachments_handler->get($att_id);
                $resources_id = $att_obj->getVar("resources_id");
                $att_attachment = $att_obj->getVar("att_attachment");
                if ( $attachments_handler->delete($att_obj) ) {
                    $criteria = new CriteriaCompo(new Criteria("resources_id",$resources_id));
                    $counts = $attachments_handler->getCount($criteria);
                    $resources_obj = $resources_handler->get($resources_id);
                    $resources_obj->setVar("resources_attachment",$counts);
                    $resources_handler->insert($resources_obj);
                    @unlink(XOOPS_VAR_PATH."/resources/".$att_attachment);
                    $stop = "删除成功";
                } else {
                    $stop = "删除失败";
                }
                redirect_header("edit.php?resources_id={$resources_id}", 5, $stop);
            break;
        }
    break;
    
    case "resources":
        switch ($ac) {
            default:
                $resources_id = isset($_GET["id"]) ? intval($_GET["id"]) :  0;
                xoops_confirm(array("resources_id"=>$resources_id,"op"=>"resources","ac"=>"del"),"action.del.php","确定删除这个资源?");
            break;
            
            case "del":
                $resources_id = isset($_POST["resources_id"]) ? intval($_POST["resources_id"]) :  0;
                $resources_obj = $resources_handler->get($resources_id);
                // 删除主表，及所有的评论
                $criteria = new CriteriaCompo(new Criteria("resources_pid",$resources_id));
                $criteria->add(new Criteria("resources_pid","0","!="));
                $_resources_ids = $resources_handler->getIds($criteria);
                unset($criteria);
                $_resources_ids[] = $resources_id;
                $criteria = new CriteriaCompo(new Criteria("resources_id","(".implode(", ",$_resources_ids). ")","in"));  
                if ( $resources_handler->deleteAll($criteria) ) {
                    // 删除附件
                    unset($criteria);
                    $criteria = new CriteriaCompo(new Criteria("resources_id",$resources_id));
                    $att_objs = $attachments_handler->getAll($criteria);
                    if ( $att_objs ) {
                        foreach ( $att_objs as $att_obj ) {
                            @unlink(XOOPS_VAR_PATH."/resources/".$att_obj->getVar("att_attachment"));
                            $attachments_handler->delete($att_obj);
                        }
                    }
                    $stop = "删除成功";
                } else {
                    $stop = "删除失败";
                }
                redirect_header("index.php", 5, $stop);
            break;
        }
    break;
}
include "footer.php";
?>
