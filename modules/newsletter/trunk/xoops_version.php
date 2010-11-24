<?php

$modversion['name'] = "電子報";
$modversion['version'] = 1.00;
$modversion['description'] = "電子報";
$modversion['author'] = "Magic.Shao <magic.shao@gmail.com>";
$modversion['credits'] = "xoops.org.cn";
$modversion['license'] = "GPL";
$modversion['image'] = "images/newsletter_slogo.png";
$modversion['dirname'] = "newsletter";
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
    "newsletter_subscribe",
    "newsletter_subscribe_log",
    "newsletter_model",
    "newsletter_content",
    "newsletter_sent_log"
);

/**
* Templates
*/
if ($isModuleAction) {
    include_once dirname(__FILE__) . "/include/functions.render.php";
    $modversion["templates"] = newsletter_getTplPageList("", true);
}

/*
$modversion['blocks'][] = array(
  	'file'			=> 'blocks.php',
  	'name'			=> '電子報',
  	'description'	=> '電子報',	
  	'show_func'		=> 'newsletter_register_show',
  	'options'		=> '0',
  	'edit_func'		=> 'newsletter_register_edit',
  	'template'		=> 'newsletter_register_menu.html'
);
*/

?>
