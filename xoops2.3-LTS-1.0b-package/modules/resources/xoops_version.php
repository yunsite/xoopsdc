<?php

$modversion['name'] = "资源模块";
$modversion['version'] = 1.00;
$modversion['description'] = "资源模块";
$modversion['author'] = "Magic.Shao <magic.shao@gmail.com>";
$modversion['credits'] = "xoops.org.cn";
$modversion['license'] = "GPL";
$modversion['image'] = "images/resources.png";
$modversion['dirname'] = "resources";
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
"res_category",
"res_resources",
"res_attachments",
"res_link",
"res_rate",
"res_counter"
);

// Templates
$i = 0;

$i++;
$modversion['templates'][$i]['file'] = 'resources_admin_index.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'resources_admin_category.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'resources_admin_resources.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'resources_admin_form.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'resources_index.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'resources_detail.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'resources_form.html';
$modversion['templates'][$i]['description'] = '';

$modversion['config'][] = array(
    'name'			=> 'pagenav',
    'title'			=> '_MI_RESOURCES_PAGENAV',
    'description'	=> '',
    'formtype'		=> 'textbox',
    'valuetype'		=> 'text',
    'default'		=> '10'
);

$modversion["config"][] = array(
    "name"          => "is_uploader",
    "title"         => "_MI_RESOURCES_USERUPLOAD",
    "description"   => "_MI_RESOURCES_USERUPLOAD_DESC",
    "formtype"      => "yesno",
    "valuetype"     => "int",
    "default"       => 0
    );
$modversion["config"][] = array(
    "name"          => "is_uploader_check",
    "title"         => "_MI_RESOURCES_USERUPLOAD_CHECK",
    "description"   => "_MI_RESOURCES_USERUPLOAD_CHECK_DESC",
    "formtype"      => "yesno",
    "valuetype"     => "int",
    "default"       => 0
    );
$select = array(
    'y-m-d' => '1',
    'y-m-d h:i:s' => '2',
    '年-月-日' => '3', 
    '年-月-日 小时:分钟:秒' => '4', 
    );

$modversion['config'][] = array(
    'name'			=> 'timeformat',
    "title"         => "_MI_RESOURCES_TIMEFORMAT",
    "description"   => "_MI_RESOURCES_TIMEFORMAT_DESC",
    'formtype'		=> 'select',
    'valuetype'		=> 'int',
    'options'		=> $select,
    'default'		=> 1
    );
    
$modversion["config"][] = array(
    "name"          => "do_rate",
    "title"         => "_MI_RESOURCES_DORATE",
    "description"   => "_MI_RESOURCES_DORATE_DESC",
    "formtype"      => "yesno",
    "valuetype"     => "int",
    "default"       => 0
    );    
    
// Comment callback functions
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'res_id';
$modversion['comments']['pageName'] = 'detail.php';
   
/* 
$modversion["comments"]["callbackFile"] = "include/comment.inc.php";
$modversion["comments"]["callback"]["approve"] = "resources_com_approve";
$modversion["comments"]["callback"]["update"] = "resources_com_update"; 
*/  
    
    
// Blocks
$modversion["blocks"] = array();

$modversion["blocks"][1] = array(
    "file"          => "blocks.php",
    "name"          => "资源",
    "description"   => "资源描述",
    "show_func"     => "resources_resource_show",
    "options"       => "0|res_weight|5",
    "edit_func"     => "resources_resource_edit",
    "template"      => "resources_block_resource.html"
);
        
?>
