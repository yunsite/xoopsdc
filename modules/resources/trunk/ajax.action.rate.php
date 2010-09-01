<?php

include 'header.php';
include dirname(__FILE__) . '/include/function.json.php';
header('Content-Type:text/html; charset=utf-8');
global $xoopsLogger;
$xoopsLogger->activated = false;
$rate = intval( @$_GET["rate"] );
$res_id = intval( @$_GET["res_id"] );

if(empty($res_id)){
    return false;
    exit();
}
    
$res_handler =& xoops_getmodulehandler('resources', 'resources');
$rate_handler =& xoops_getmodulehandler('rate',"resources");
$res_obj =& $res_handler->get($res_id);

$uid = (is_object($xoopsUser)) ? $xoopsUser->getVar("uid") : 0;
$criteria = new CriteriaCompo(new Criteria("res_id", $res_id));
$criteria->add(new Criteria("uid", $uid));

if(!empty($uid)){ 
  	if($count = $rate_handler->getCount($criteria)){
  		$message = _MD_RES_ALREADYRATED;
  	}else{
  		$rate_obj =& $rate_handler->create();
  		$rate_obj->setVar("res_id", $res_id);
  		$rate_obj->setVar("uid", $uid);
  		$rate_obj->setVar("rate_rating", $rate);
  		$rate_obj->setVar("rate_time", time());
  		if(!$rate_id = $rate_handler->insert($rate_obj)){
  		    $message = _MD_RES_NOTSAVED;
  		    exit();
  		}
  		$res_obj =& $res_handler->get($res_id);
  		$res_obj->setVar( "res_rating", $res_obj->getVar("res_rating") + $rate, true );
  		$res_obj->setVar( "res_rates", $res_obj->getVar("res_rates") + 1, true );
  		$res_handler->insert($res_obj, true);
  		$message = _MD_RES_RATEIT_PREFIXU;
  	}
}else{
    $message = _MD_RES_RATELT_NOUSER;
}

$message = $message;
echo json_encode(array('rating'=>$res_obj->getRatingAverage(), 'rates'=>$res_obj->getVar("res_rates"), 'msg'=>$message));
?>
