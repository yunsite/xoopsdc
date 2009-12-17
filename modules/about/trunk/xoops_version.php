<?php

$modversion['name'] = _MI_ABOUT_NAME;
$modversion['version'] = 1.01;
$modversion['description'] = _MI_ABOUT_DESC;
$modversion['author'] = "Magic.Shao<magic.shao@gmail.com>, ezsky <ezskyyoung@gmail.com>";
$modversion['credits'] = "xoops.org.cn";
$modversion['license'] = "GPL";
$modversion['image'] = "images/about_slogo.png";
$modversion['dirname'] = "about";
$modversion['hasAdmin'] = 1; 
$modversion['adminindex'] = "admin/admin.page.php"; 
$modversion['adminmenu'] = "admin/menu.php"; 

// Is performing module install/update?
$isModuleAction = ( !empty($_POST["fct"]) && "modulesadmin" == $_POST["fct"] ) ? true : false;

// Menu
$modversion['hasMain'] = 1; 
global $xoopsModuleConfig, $xoopsUser, $xoopsModule;

//sql
$modversion['sqlfile']['mysql']= "sql/mysql.sql";
$modversion['tables'] =  array(
"about_page"
);

/**
* Templates
*/
if ($isModuleAction) {
    include_once dirname(__FILE__) . "/include/functions.render.php";
    $modversion["templates"] = about_getTplPageList("", true);
}
// Blocks
$modversion["blocks"] = array();

$modversion["blocks"][1] = array(
    "file"          => "blocks.php",
    "name"          => _MI_ABOUT_NAME,
    "description"   => "",
    "show_func"     => "about_block_menu_show",
    "options"       => "",
    "edit_func"     => "",
    "template"      => "about_block_menu.html");
?>
