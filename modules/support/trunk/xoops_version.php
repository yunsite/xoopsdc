<?php

$modversion['name'] = _MI_SUPPORT_NAME;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_SUPPORT_DESC;
$modversion['author'] = "Magic.Shao <magic.shao@gmail.com>";
$modversion['credits'] = "xoops.org.cn";
$modversion['license'] = "GPL";
$modversion['image'] = "images/support.png";
$modversion['dirname'] = "support";
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
"support_category",
"support_cat_users_link",
"support_process",
"support_transform",
"support_annex"
);

// Templates
$i = 0;

$i++;
$modversion['templates'][$i]['file'] = 'support_admin_index.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'support_admin_category.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'support_manager.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'support_index.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'support_category.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'support_info.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file'] = 'support_form.html';
$modversion['templates'][$i]['description'] = '';

$select = array(
    'y-m-d' => '1',
    'y-m-d h:i:s' => '2',
    '年-月-日' => '3', 
    '年-月-日 小时:分钟:秒' => '4', 
);

$modversion['config'][] = array(
    'name'			=> 'timeformat',
    'title'			=> '_MI_SUPPORT_TIME',
    'description'	=> '',
    'formtype'		=> 'select',
    'valuetype'		=> 'int',
    'options'		=> $select,
    'default'		=> 1
);

$modversion['config'][] = array(
    'name'			=> 'pagenav',
    'title'			=> '_MI_SUPPORT_PAGENAV',
    'description'	=> '',
    'formtype'		=> 'textbox',
    'valuetype'		=> 'text',
    'default'		=> '10'
);

$member_handler =& xoops_gethandler('member');     
$_groups = $member_handler->getGroupList(); 
foreach($_groups as $k=>$v){
    $groups[$v] = $k;
}
$modversion['config'][] = array(
    'name'			=> 'support_manager',
    'title'			=> '_MI_SUPPORT_SUPPORT_MANAGER',
    'description'	=> '',
    'formtype'		=> 'select',
    'valuetype'		=> 'int',
    'options'		=> $groups,
    'default'		=> 1
    );
    
$modversion['config'][] = array(
    'name'			=> 'support',
    'title'			=> '_MI_SUPPORT_SUPPORT',
    'description'	=> '',
    'formtype'		=> 'select',
    'valuetype'		=> 'int',
    'options'		=> $groups,
    'default'		=> 1
    );
?>
