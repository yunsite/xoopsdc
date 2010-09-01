<?php


function resources_com_update($rec_id, $count)
{
    $res_handler =& xoops_getmodulehandler('resources', 'resources');
    $res_obj =& $res_handler->get($rec_id);
    $res_obj->setVar( "res_comments", $count, true );
    return $res_handler->insert($res_obj, true);
}

function resources_com_approve(&$comment)
{
 
}

?>