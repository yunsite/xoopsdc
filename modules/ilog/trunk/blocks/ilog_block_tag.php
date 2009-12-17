<?php

function ilog_tag_block_cloud_show($options)
{
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
    return tag_block_cloud_show($options,"ilog");
}
function ilog_tag_block_cloud_edit($options) 
{
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
    return tag_block_cloud_edit($options);
}
function ilog_tag_block_top_show($options) 
{
    if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
        return null; 
    }
    $block_content = tag_block_top_show($options, "ilog");
    return $block_content;
}

function ilog_tag_block_top_edit($options) {
    if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
        return null; 
    } 
    $form = tag_block_top_edit($options);
    return $form;
}
?>