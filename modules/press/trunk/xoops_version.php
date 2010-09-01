<?php
if ( false === defined('XOOPS_ROOT_PATH') ) {
  exit();
}
$modversion = array();
$modversion["version"] = "1.0";
$modversion["name"] = "最新动态";
$modversion["description"] = "";
$modversion["author"] = "ezsky";
$modversion["license"] = "";
$modversion["dirname"] = "press";
$modversion['image'] = "images/logo.png";
$modversion["sqlfile"]["mysql"] = "sql/mysql.sql";
$modversion["tables"] = array(
	'press_topics',
	'press_category',
);
$modversion["hasMain"] = 1;
// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
$i = 1;
$modversion["templates"][$i]["file"] = "press_index.html";
$modversion["templates"][$i]["description"] = "for index";
$i++;
$modversion["templates"][$i]["file"] = "press_view.html";
$modversion["templates"][$i]["description"] = "for view";
$i++;
$modversion["templates"][$i]["file"] = "press_edit.html";
$modversion["templates"][$i]["description"] = "for New ";
$i++;
$modversion["templates"][$i]["file"] = "press_header.html";
$modversion["templates"][$i]["description"] = "for header";
$i++;
$modversion["templates"][$i]["file"] = "press_category.html";
$modversion["templates"][$i]["description"] = "";
//admin templates
$i++;
$modversion["templates"][$i]["file"] = "press_cp_category.html";
$modversion["templates"][$i]["description"] = "";
$i++;
$modversion["templates"][$i]["file"] = "press_cp_edit.html";
$modversion["templates"][$i]["description"] = "";
$i++;
$modversion["templates"][$i]["file"] = "press_cp_index.html";
$modversion["templates"][$i]["description"] = "";

//blocks
/*
 * $options:  
 *                    $options[0] - category of news to display
 *                    $options[1] - number of news to display
 */
$modversion["blocks"][]    = array(
    "file"            => "blocks.php",
    "name"            => _MI_PRESS_BLOCK_NEWS,
    "description"    => "",
    "show_func"        => "press_block_news_show",
    "edit_func"        => "press_block_news_edit",
    "options"        => "0|10",
    "template"        => "press_block_news.html",
    );

$modversion["blocks"][]    = array(
    "file"            => "blocks.php",
    "name"            => _MI_PRESS_BLOCK_CATEGORY,
    "description"    => "",
    "show_func"        => "press_block_category_show",
    "edit_func"        => "",
    "options"        => "",
    "template"        => "press_block_category.html",
    );

// config
$modversion['hasConfig'] = 1;

$modversion["config"] = array();

$modversion["config"][] = array(
    "name"          => "presspagemaxnum",
    "title"         => "_PRESS_MI_NOTESPAGEMAXNUM",
    "description"   => "",
    "formtype"      => "textbox",
    "valuetype"     => "int",
    "default"       => 10
    );
$modversion["config"][] = array(
    "name"          => "presssummarymax",
    "title"         => "_PRESS_MI_NOTESSUMMARYMAX",
    "description"   => "",
    "formtype"      => "text",
    "valuetype"     => "int",
    "default"       => 320
    );
    
$modversion["config"][] = array(
    "name"          => "presseditor",
    "title"         => "_PRESS_MI_EDITOR",
    "description"   => "_PRESS_MI_EDITOR_DESC",
    "formtype"      => "select",
    "valuetype"     => "text",
    "default"       => "Dhtml",
    "options"       => array(
    		_PRESS_MI_FORM_TEXTAREA=>"textarea",
    		_PRESS_MI_FORM_DHTMLEXT=>"dhtmlext",
    		_PRESS_MI_FORM_DHTMLTEXTAREA=>"dhtmltextarea",
    		_PRESS_MI_FORM_TINYMCE=>"tinymce",
    	)
    );
 
// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'view.php';
$modversion['comments']['itemName'] = 'id';
// Comment callback functions
//$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
//$modversion['comments']['callback']['approve'] = 'news_com_approve';
//$modversion['comments']['callback']['update'] = 'news_com_update';
?>
