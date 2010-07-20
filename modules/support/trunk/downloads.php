<?php
include "header.php";

$ac = isset($_GET["ac"]) ? $_GET["ac"] : "";
$annex_id = isset($_GET["annex_id"]) ? $_GET["annex_id"] : 0;

$annex_handler =& xoops_getmodulehandler('annex', 'support');

$annex_obj = $annex_handler->get($annex_id);

if (is_object($annex_obj) && !empty($annex_id)) {
    header('location: '.XOOPS_URL.'/uploads/support/'.$annex_obj->getVar('annex_file'));
} else {
    header('location: '.XOOPS_URL. '/modules/support');
}

?>
