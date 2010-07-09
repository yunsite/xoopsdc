<?php

$modversion['name'] = "经典案例";
$modversion['version'] = 1.05;
$modversion['description'] = "经典案例";
$modversion['author'] = "Magic.Shao <magic.shao@gmail.com>";
$modversion['credits'] = "xoops.org.cn";
$modversion['license'] = "GPL";
$modversion['image'] = "images/portfolio_slogo.png";
$modversion['dirname'] = "portfolio";
$modversion['hasAdmin'] = 1; 
$modversion['adminindex'] = "admin/admin.index.php"; 
$modversion['adminmenu'] = "admin/menu.php"; 

// Is performing module install/update?
$isModuleAction = ( !empty($_POST["fct"]) && "modulesadmin" == $_POST["fct"] ) ? true : false;
$modversion["onInstall"] = "include/action.module.php";
$modversion["onUpdate"] = "include/action.module.php";

// Menu
$modversion['hasMain'] = 1; 
global $xoopsModuleConfig, $xoopsUser, $xoopsModule;

//sql
$modversion['sqlfile']['mysql']= "sql/mysql.sql";
$modversion['tables'] =  array(
"portfolio_service",
"portfolio_case",
"portfolio_images",
"portfolio_cs"
);

/**
* Templates
*/
if ($isModuleAction) {
    include_once dirname(__FILE__) . "/include/functions.render.php";
    $modversion["templates"] = portfolio_getTplPageList("", true);
}
// Blocks
$modversion["blocks"] = array();
$modversion["blocks"][1] = array(
    "file"          => "blocks.php",
    "name"          => "服务导航",
    "description"   => "",
    "show_func"     => "portfolio_block_service_show",
    "options"       => "",
    "edit_func"     => "",
    "template"      => "portfolio_block_service.html"
);
$modversion["blocks"][2] = array(
    "file"          => "blocks.php",
    "name"          => "案例展示",
    "description"   => "",
    "show_func"     => "portfolio_block_case_show",
    "options"       => "0|case_weight|10|1|1|1|1|10|100|100|10|100|0",
    "edit_func"     => "portfolio_block_case_edit",
    "template"      => "portfolio_block_case.html"
);
// Search
$modversion["hasSearch"] = 1;
$modversion["search"]["file"] = "include/search.inc.php";
$modversion["search"]["func"] = "portfolio_search"; 

?>
