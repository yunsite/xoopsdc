<?php

include 'header.php';

$rate = intval( @$_POST["rate"] );
$res_id = intval( @$_POST["res_id"] );

if(empty($res_id)){
    redirect_header("javascript:history.go(-1);", 1, _MD_RES_INVALID_SUBMIT);
    exit();
}

$res_handler =& xoops_getmodulehandler('resources', 'resources');
$rate_handler =& xoops_getmodulehandler('rate',"resources");
$res_obj =& $res_handler->get($res_id);

	$uid = (is_object($xoopsUser)) ? $xoopsUser->getVar("uid") : 0;
	$criteria = new CriteriaCompo(new Criteria("res_id", $res_id));
	$criteria->add(new Criteria("uid", $uid));
	if($count = $rate_handler->getCount($criteria)){
		$message = _MD_RES_ALREADYRATED;
	}else{
		$rate_obj =& $rate_handler->create();
		$rate_obj->setVar("res_id", $res_id);
		$rate_obj->setVar("uid", $uid);
		$rate_obj->setVar("rate_rating", $rate);
		$rate_obj->setVar("rate_time", time());
		if(!$rate_id = $rate_handler->insert($rate_obj)){
		    redirect_header("javascript:history.go(-1);", 1, _MD_RES_NOTSAVED);
		    exit();
		}
		$res_obj =& $res_handler->get($res_id);
		$res_obj->setVar( "res_rating", $res_obj->getVar("res_rating") + $rate, true );
		$res_obj->setVar( "res_rates", $res_obj->getVar("res_rates") + 1, true );
		$res_handler->insert($res_obj, true);
		$message = _MD_RES_RATEIT_PREFIXU;
	}

redirect_header("index.php", 2, $message);

include 'footer.php';
?>
