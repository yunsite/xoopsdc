<?php
if ( false === defined('XOOPS_ROOT_PATH') ) {
  exit();
}
$modversion["version"] = "1.0.0";
$modversion["name"] = "resources";
$modversion["description"] = "resources";
$modversion["author"] = "xiaohui";
$modversion["license"] = "All rights reverved.";
$modversion["dirname"] = "resources";
$modversion['image'] = "images/logo.png";
$modversion["sqlfile"]["mysql"] = "sql/mysql.sql";
$modversion["tables"] = array(
	'resources',
	'resources_category',
	'resources_attachments',
	'resources_clipboard',
);

$modversion["hasMain"] = true;
// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion["templates"] = array();
$modversion["templates"][] = array(
	"file" => "resources_header.html",
	"description" => "for index"
	);
$modversion["templates"][] = array(
	"file" => "resources_index.html",
	"description" => "for index"
	);
$modversion["templates"][] = array(
	"file" => "resources_detail.html",
	"description" => "for detail"
	);
$modversion["templates"][] = array(
	"file" => "resources_post.html",
	"description" => "for post"
	);
$modversion["templates"][] = array(
	"file" => "resources_my.html",
	"description" => "for post"
	);
$modversion["templates"][] = array(
	"file" => "resources_action.html",
	"description" => ""
	);
$modversion["templates"][] = array(
	"file" => "resources_myclipboard.html",
	"description" => ""
	);
// admin templates
$modversion["templates"][] = array(
	"file" => "resources_cp_index.html",
	"description" => "for admin index"
	);
$modversion["templates"][] = array(
	"file" => "resources_cp_category.html",
	"description" => "for admin category"
	);
$modversion["templates"][] = array(
	"file" => "resources_cp_edit.html",
	"description" => "for admin category"
	);
	
// Blocks new resources list
$modversion['blocks'][] = array(
  	'file'			=> 'blocks.php',
  	'name'			=> 'attachments list',
  	'description'	=> '',
  	'show_func'		=> 'resources_att_show',
  	'options'		=> '5|1',
  	'edit_func'		=> 'resources_att_edit',
  	'template'		=> 'block_resources_atts.html'
);
// Block resources category
$modversion['blocks'][] = array(
  	'file'			=> 'blocks.php',
  	'name'			=> 'Category',
  	'description'	=> 'Shows Category',
  	'show_func'		=> 'b_category',
  	'template'		=> 'block_resources_category.html'
);

// config
$modversion['hasConfig'] = 1;

$modversion["config"] = array();
$modversion["config"][] = array(
    "name"          => "moduletitle",
    "title"         => "_MI_MODULETITLE",
    "description"   => "_MI_MODULETITLE_DESC",
    "formtype"      => "textbox",
    "valuetype"     => "text",
    "default"       => "resources"
    );
$modversion["config"][] = array(
    "name"          => "useruploads",
    "title"         => "_MI_USERUPLOADS",
    "description"   => "_MI_USERUPLOADS_DESC",
    "formtype"      => "yesno",
    "valuetype"     => "int",
    "default"       => 0
    );
$modversion["config"][] = array(
    "name"          => "useruploadscheck",
    "title"         => "_MI_USERUPLOADSCHECK",
    "description"   => "_MI_USERUPLOADSCHECK_DESC",
    "formtype"      => "yesno",
    "valuetype"     => "int",
    "default"       => 0
    );
$modversion["config"][] = array(
    "name"          => "attnum",
    "title"         => "_MI_ATTNUM",
    "description"   => "_MI_ATTNUM_DESC",
    "formtype"      => "textbox",
    "valuetype"     => "int",
    "default"       => 5
    );
$modversion["config"][] = array(
    "name"          => "pagenum",
    "title"         => "_MI_PAGENUM",
    "description"   => "_MI_PAGENUM_DESC",
    "formtype"      => "textbox",
    "valuetype"     => "int",
    "default"       => 10
    );
$modversion["config"][] = array(
    "name"          => "attsize", 
    "title"         => "_MI_ATTSIZE",
    "description"   => "_MI_ATTSIZE_DESC",
    "formtype"      => "textbox",
    "valuetype"     => "int",
    "default"       => 1048576
    );
$modversion["config"][] = array(
    "name"          => "guestuploads", 
    "title"         => "_MI_GUESTUPLOADS",
    "description"   => "_MI_GUESTUPLOADS_DESC",
    "formtype"      => "yesno",
    "valuetype"     => "int",
    "default"       => 0
    );
    
$modversion["onInstall"] = "include/action.module.php";
$modversion["onUpdate"] = "include/action.module.php";
?>
