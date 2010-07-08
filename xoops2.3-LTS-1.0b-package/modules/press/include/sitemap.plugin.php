<?php

if (!defined("XOOPS_ROOT_PATH")) { exit(); }

function b_sitemap_press()
{    
    $sitemap = array();
    $category_handler =& xoops_getmodulehandler('category', 'press');
    
    $categories = $category_handler->getList();
    foreach ($categories as $k => $v) {
        $sitemap["parent"][] = array("id" => $k, "title" => $v, "url"=> "index.php?page_id="  . $k);
    }
    return $sitemap;
}

?>
