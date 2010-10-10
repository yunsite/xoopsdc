<?php

include_once '../../mainfile.php';

if(isset($spotlight_data['ajax']) && $spotlight_data['ajax'] == 1) {
    include_once XOOPS_ROOT_PATH . '/modules/spotlight/ajax_transfer_spotlight.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/spotlight/transfer_spotlight.php';
}


?>
