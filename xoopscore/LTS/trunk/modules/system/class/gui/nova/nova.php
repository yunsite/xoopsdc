<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

xoops_load('gui', 'system');

/*
 * Xoops Cpanel nova GUI class
 *
 * @copyright   The XOOPS project http://sf.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package     system
 * @usbpackage  GUI
 * @since       lts v2
 * @author      Mamba       <mambax7@gmail.com>
 * @author      Mojtabajml  <jamali.mojtaba@gmail.com>
 * @author      Voltan      <djvoltan@gmail.com>
 * @author      BitC3R0     <BitC3R0@gmail.com>
 * @author      trabis      <lusopoemas@gmail.com>
 * @author      ezsky      <lusopoemas@gmail.com> 
 * @version     1.2
 * @version     $Id: nova.php 3586 2009-09-06 16:44:43Z ezsky $
 */

class XoopsGuiNova extends  XoopsSystemGui
{

    function __construct()
    {
    }

    function XoopsGuiNova()
    {
        $this->__construct();
    }

    function validate()
    {
        return true;
    }


    function header()
    {
        parent::header();
      
        global $xoopsConfig, $xoopsUser, $xoopsModule, $xoTheme, $xoopsTpl;
        
        $tpl =& $this->template;
        $xoTheme =& $this->xoTheme;

        $xoTheme->addScript('Frameworks/jquery/jquery.js');
        
        $tpl->assign('lang_cp', _CPHOME);

        //for system options
        $tpl->assign('system_options', _AD_SYSOPTIONS);
        $tpl->assign('lang_banners', _MD_AM_BANS);
        $tpl->assign('lang_blocks', _MD_AM_BKAD);
        $tpl->assign('lang_groups', _MD_AM_ADGS);
        $tpl->assign('lang_images', _MD_AM_IMAGES);
        $tpl->assign('lang_modules', _MD_AM_MDAD);
        $tpl->assign('lang_preferences', _MD_AM_PREF);
        $tpl->assign('lang_smilies', _MD_AM_SMLS);
        $tpl->assign('lang_ranks', _MD_AM_RANK);
        $tpl->assign('lang_edituser', _MD_AM_USER);
        $tpl->assign('lang_finduser', _MD_AM_FINDUSER);
        $tpl->assign('lang_mailuser', _MD_AM_MLUS);
        $tpl->assign('lang_avatars', _MD_AM_AVATARS);
        $tpl->assign('lang_tpls', _MD_AM_TPLSETS);
        $tpl->assign('lang_comments', _MD_AM_COMMENTS);
        $tpl->assign('lang_insmodules', _AD_INSTALLEDMODULES);
        $tpl->assign('lang_tools', _MD_AM_TOOLS);

        //for toolpit help texts
        $tpl->assign('lang_banners_help', _MD_AM_BANS_HELP);
        $tpl->assign('lang_blocks_help', _MD_AM_BKAD_HELP);
        $tpl->assign('lang_groups_help', _MD_AM_ADGS_HELP);
        $tpl->assign('lang_images_help', _MD_AM_IMAGES_HELP);
        $tpl->assign('lang_modules_help', _MD_AM_MDAD_HELP);
        $tpl->assign('lang_preferences_help', _MD_AM_PREF_HELP);
        $tpl->assign('lang_smilies_help', _MD_AM_SMLS_HELP);
        $tpl->assign('lang_ranks_help', _MD_AM_RANK_HELP);
        $tpl->assign('lang_edituser_help', _MD_AM_USER_HELP);
        $tpl->assign('lang_finduser_help', _MD_AM_FINDUSER_HELP);
        $tpl->assign('lang_mailuser_help', _MD_AM_MLUS_HELP);
        $tpl->assign('lang_avatars_help', _MD_AM_AVATARS_HELP);
        $tpl->assign('lang_tpls_help', _MD_AM_TPLSETS_HELP);
        $tpl->assign('lang_comments_help', _MD_AM_COMMENTS_HELP);
        $tpl->assign('lang_tools_help', _MD_AM_TOOLS_HELP);

        //for system overview
        $tpl->assign('lang_overview', _MD_CPANEL_OVERVIEW);
        $tpl->assign('lang_phpextensions', _MD_CPANEL_PHPEXTENSIONS);
        $tpl->assign('lang_about_xoops', _MD_OXYGEN_ABOUT);
        $tpl->assign('lang_about_xoops_text', _MD_OXYGEN_ABOUT_TEXT);
        $tpl->assign('lang_version', _MD_OXYGEN_VERSION);
        $tpl->assign('lang_version_xoops', _MD_OXYGEN_VERSION_XOOPS);
        $tpl->assign('lang_version_php', _MD_OXYGEN_VERSION_PHP);
        $tpl->assign('lang_version_mysql', _MD_OXYGEN_VERSION_MYSQL);
        $tpl->assign('lang_server_api_name', _MD_OXYGEN_Server_API);
        $tpl->assign('lang_os', _MD_OXYGEN_OS);
        $tpl->assign('lang_xoops_links', _MD_OXYGEN_XOOPS_LINKS);

        //for xoops links
        $tpl->assign('lang_xoops_xoopsproject', _MD_OXYGEN_XOOPSPROJECT);
        $tpl->assign('lang_xoops_localsupport', _MD_OXYGEN_LOCALSUPPORT);
        $tpl->assign('lang_xoops_xoopscore', _MD_OXYGEN_XOOPSCORE);
        $tpl->assign('lang_xoops_xoopsthems', _MD_OXYGEN_XOOPSTHEME);
        $tpl->assign('lang_xoops_xoopswiki', _MD_OXYGEN_XOOPSWIKI);
        $tpl->assign('lang_xoops_xoopsbooks', _MD_OXYGEN_XOOPSBOOKS);
        $tpl->assign('lang_xgiftshop', _MD_OXYGEN_XGIFTSHOP);
        $tpl->assign('lang_xdonations', _MD_OXYGEN_XDONATIONS);
        $tpl->assign('lang_xdonations_text', _MD_OXYGEN_XDONATIONS_TEXT);
        $tpl->assign('lang_xoops_newmodule', _MD_OXYGEN_NEWMODULE);
        $tpl->assign('lang_xoops_xoopsfaq', _MD_OXYGEN_XOOPSFAQ);
        $tpl->assign('lang_xoops_codesvn', _MD_OXYGEN_CODESVN);
        $tpl->assign('lang_xoops_reportbug', _MD_OXYGEN_REPORTBUG);

        //add for styles name
        $tpl->assign('lang_silver', _MD_LANG_SILVER);
        $tpl->assign('lang_dark', _MD_LANG_DARK);
        $tpl->assign('lang_orange', _MD_LANG_ORANGE);

        //start system overview
        $tpl->assign('lang_xoops_version', XOOPS_VERSION);
        $tpl->assign('lang_php_vesion', PHP_VERSION);
        $tpl->assign('lang_mysql_version', mysql_get_server_info());
        $tpl->assign('lang_server_api', PHP_SAPI);
        $tpl->assign('lang_os_name', PHP_OS);
        $tpl->assign('safe_mode', ini_get( 'safe_mode' ) ? 'On' : 'Off');
        $tpl->assign('register_globals', ini_get( 'register_globals' ) ? 'On' : 'Off');
        $tpl->assign('magic_quotes_gpc', ini_get( 'magic_quotes_gpc' ) ? 'On' : 'Off');
        $tpl->assign('allow_url_fopen', ini_get( 'allow_url_fopen' ) ? 'On' : 'Off');
        $tpl->assign('fsockopen', function_exists( 'fsockopen' ) ? 'On' : 'Off');
        $tpl->assign('allow_call_time_pass_reference', ini_get( 'allow_call_time_pass_reference' ) ? 'On' : 'Off');
        $tpl->assign('post_max_size', ini_get( 'post_max_size' ));
        $tpl->assign('max_input_time', ini_get( 'max_input_time' ));
        $tpl->assign('output_buffering', ini_get( 'output_buffering' ));
        $tpl->assign('max_execution_time', ini_get( 'max_execution_time' ));
        $tpl->assign('memory_limit', ini_get( 'memory_limit' ));
        $tpl->assign('file_uploads', ini_get( 'file_uploads' ) ? 'On' : 'Off');
        $tpl->assign('upload_max_filesize', ini_get( 'upload_max_filesize' ));
        $tpl->assign('xoops_sitename', $xoopsConfig['sitename']);

        // ADD MENU *****************************************
        define('XOOPS_ADMINTHEME_URL',XOOPS_URL . '/modules/system/class/gui'); // add by kris
        define('XOOPS_ADMINTHEME_PATH', XOOPS_ROOT_PATH . '/modules/system/class/gui'); // add by kris
        //Add  CONTROL PANEL  Menu  items
        $menu = array();
        
        $menu[0]['link'] = XOOPS_URL;
        $menu[0]['title'] = _YOURHOME;
        $menu[0]['absolute'] = 1;
        
        $menu[1]['link'] = XOOPS_URL . '/admin.php?xoopsorgnews=1';
        $menu[1]['title'] = _XOOPS_NEWS;
        $menu[1]['absolute'] = 1;
        $menu[1]['icon'] = XOOPS_ADMINTHEME_URL . '/nova/images/xoops.png';
        
        $tpl->append('navitems', array('link' => XOOPS_URL . '/admin.php', 'text' => _CPHOME, 'menu' => $menu));

        //add SYSTEM  Menu items
        
        include dirname(__FILE__) . '/menu.php';
        if ($xoopsModule) {
        
        if ( 'system' == $xoopsModule->getVar('dirname', 'n')) {
            $modpath = XOOPS_URL . '/admin.php';
            $modname = _AD_SYSOPTIONS;
            $modid = 1;
            $moddir = 'system';
            /**/
            $mod_options = $adminmenu;
            foreach (array_keys($mod_options) as $item) {
                $mod_options[$item]['link'] = empty($mod_options[$item]['absolute']) ? XOOPS_URL . '/modules/'.$moddir.'/' . $mod_options[$item]['link'] : $mod_options[$item]['link'];
                $mod_options[$item]['icon'] = empty($mod_options[$item]['icon']) ? '' : XOOPS_ADMINTHEME_URL . '/nova/' . $mod_options[$item]['icon'];
                unset($mod_options[$item]['icon_small']);
            }
            
        } else {
            $moddir = $xoopsModule->getVar('dirname', 'n');
            $modpath = XOOPS_URL . '/modules/' . $moddir;
            $modname = $xoopsModule->getVar('name');
            $modid = $xoopsModule->getVar('mid');
            $modImage = '../'. $xoopsModule->getInfo('image');
            
            $mod_options = $xoopsModule->getAdminMenu();
            foreach (array_keys($mod_options) as $item) {
                $mod_options[$item]['link'] = empty($mod_options[$item]['absolute']) ? XOOPS_URL . "/modules/{$moddir}/" . $mod_options[$item]['link'] : $mod_options[$item]['link'];
                $mod_options[$item]['icon'] = empty($mod_options[$item]['icon']) ? '' : XOOPS_URL . "/modules/{$moddir}/" . $mod_options[$item]['icon'];
            }
            
            $tpl->assign('modImage', $modImage);
        }
         
        $tpl->assign('mod_options', $mod_options);
        $tpl->assign('modpath', $modpath);
        $tpl->assign('modname', $modname);
        $tpl->assign('modid', $modid);
        $tpl->assign('moddir', $moddir);
        }
        // add preferences menu
        $menu = array();
        
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=1',
            'title'     => "基本参数设置",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=2',
            'title'     => "会员管理设置 ",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=3',
            'title'     => "Meta 及页脚",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=4',
            'title'     => "敏感词语检查",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=5',
            'title'     => "搜索选项",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=6',
            'title'     => "邮件设置",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=show&amp;confcat_id=7',
            'title'     => "认证方式管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
            
        
        $tpl->append('navitems', array('link' => XOOPS_URL . '/modules/system/admin.php?fct=preferences', 'text' => "站点", 'dir' => "", 'menu' => $menu));
        
        // xianshi
        $menu = array();
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin',
            'title'     => "区块管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=tplsets',
            'title'     => "模板管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
             
        $tpl->append('navitems', array('link' => '###', 'text' => "显示/外观", 'dir' => "", 'menu' => $menu));
        
        // manage tools
        $menu = array();
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=avatars',
            'title'     => "头像管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=banners',
            'title'     => "广告管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=comments',
            'title'     => "评论管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png'); 
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=findusers',
            'title'     => "查找用户",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=users',
            'title'     => "编辑用户",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png'); 
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=groups',
            'title'     => "群组管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png');
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=images',
            'title'     => "图片管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png'); 
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=mailusers',
            'title'     => "邮件群发",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png'); 
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=smilies',
            'title'     => "表情管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png'); 
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=userrank',
            'title'     => "等级管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png'); 
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin',
            'title'     => "模块管理",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/prefs_small.png'); 
        $menu[] = array(
            'link'      => XOOPS_URL . '/modules/system/admin.php?fct=tools',
            'title'     => "维护工具",
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/icons/tools_small.png');       
        
        $tpl->append('navitems', array('link' => XOOPS_URL . '/modules/system/admin.php?fct=preferences', 'text' => "管理工具", 'dir' => "", 'menu' => $menu));
        
        // add MODULES  Menu items
        $module_handler =& xoops_gethandler('module');
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('hasadmin', 1));
        $criteria->add(new Criteria('isactive', 1));
        $criteria->setSort('mid');
        $mods = $module_handler->getObjects($criteria);
        unset($mods[0]);
        $menu = array();
        $moduleperm_handler =& xoops_gethandler('groupperm');
        foreach ($mods as $mod) {
            $rtn = array();
            $modOptions = array(); //add for sub menus
            $sadmin = $moduleperm_handler->checkRight('module_admin', $mod->getVar('mid'), $xoopsUser->getGroups());
            if ($sadmin) {
                $info = $mod->getInfo();
                if (!empty($info['adminindex'])) {
                    $rtn['link'] = XOOPS_URL . '/modules/'. $mod->getVar('dirname', 'n') . '/' . $info['adminindex'];
                } else {
                    $rtn['link'] = XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $mod->getVar('mid');
                }
                $rtn['title'] = $mod->name();
                $rtn['absolute'] = 1;
                $rtn['url'] = XOOPS_URL . '/modules/'. $mod->getVar('dirname', 'n') . '/'; //add for sub menus

                if (isset($info['icon']) && $info['icon'] != '' ) {
                	$rtn['icon'] = XOOPS_URL . '/modules/' . $mod->getVar('dirname', 'n') . '/' . $info['icon'];
                }
            }
            $menu[] = $rtn;
        }
        
        $tpl->append('navitems', array('link' => XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin',
        'text' => "应用程序", 'dir' => "", 'menu' => $menu));
        
        /*add OPTIONS/Links Menu Items
        $menu = array();
        $menu[] = array(
            'link'      => 'http://www.xoops.org',
            'title'     => _XOOPS_WEBSITE,
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/images/xoops.png');
        $menu[] = array(
            'link'      => 'http://www.xoops.org/modules/extgallery/',
            'title'     => _AD_XOOPSTHEMES,
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/images/tweb.png');
        $menu[] = array(
            'link'      => 'http://www.xoops.org/modules/repository/',
            'title'     => _AD_XOOPSMODULES,
            'absolute'  => 1,
            'icon'     => XOOPS_ADMINTHEME_URL . '/nova/images/xoops.png');
        $menu[] = array(
            'link'      => 'http://sourceforge.net/projects/xoops/',
            'title'     => _SOURCEFORGE,
            'absolute'  => 1);

        $tpl->append('navitems', array('link' => XOOPS_URL . '/admin.php','text' => _AD_INTERESTSITES, 'menu' => $menu));
        
        //add OPTIONS/links for local support
        if (file_exists($file = XOOPS_ADMINTHEME_PATH . '/nova/language/' . $xoopsConfig['language'] . '/localsupport.php' )) {
            $links = include XOOPS_ADMINTHEME_PATH . '/nova/language/' . $xoopsConfig['language'] . '/localsupport.php';
            if ( count($links) > 0 ) {
                $tpl->append('navitems', array('link' => XOOPS_URL . '/admin.php','text' => _AD_LOCALSUPPORT, 'menu' => $links));
            }
        }
        */
        if (is_object($xoopsModule) || !empty($_GET['xoopsorgnews'])) {
                return;
        }
        
        foreach ($mods as $mod) {
            $rtn = array();
            $moduleperm_handler =& xoops_gethandler('groupperm');
            $sadmin = $moduleperm_handler->checkRight('module_admin', $mod->getVar('mid'), $xoopsUser->getGroups());
            if ($sadmin) {
                $info = $mod->getInfo();
                if (!empty($info['adminindex'])) {
                	$rtn['link'] = XOOPS_URL . '/modules/'. $mod->getVar('dirname', 'n') . '/' . $info['adminindex'];
                } else {
                	$rtn['link'] = XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $mod->getVar('mid');
                }
                $rtn['title'] = $mod->getVar('name');
                $rtn['absolute'] = 1;
                if (isset($info['icon_big'])) {
                	$rtn['icon'] = XOOPS_URL . '/modules/' . $mod->getVar('dirname', 'n') . '/' . $info['icon_big'];
                } elseif (isset($info['image'])) {
                	$rtn['icon'] = XOOPS_URL . '/modules/' . $mod->getVar('dirname', 'n') . '/' . $info['image'];
                }
            }
            
            $tpl->append('modules', $rtn);
        }
        if (empty($mods)) {
             $tpl->assign('cpanel', true);
        } 
        
        // ###### Output warn messages for security ######

if (is_dir(XOOPS_ROOT_PATH . "/install/" )) {
  $tpl->assign('warninginstall', sprintf(_AD_WARNINGINSTALL, XOOPS_ROOT_PATH . '/install/'));
}

if ( is_writable(XOOPS_ROOT_PATH . "/mainfile.php" ) ) {
    $tpl->assign('warningwriteable_mainfile', sprintf(_AD_WARNINGWRITEABLE, XOOPS_ROOT_PATH . '/mainfile.php'));
}

// ###### Output warn messages for correct functionality  ######
if (!is_writable(XOOPS_CACHE_PATH))  {
    $tpl->assign('warningwriteable_cache', sprintf(_AD_WARNINGNOTWRITEABLE, XOOPS_CACHE_PATH));
}
if (!is_writable(XOOPS_UPLOAD_PATH))     {
    $tpl->assign('warningwriteable_upload', sprintf(_AD_WARNINGNOTWRITEABLE, XOOPSS_UPLOAD_PATH));

}
if (!is_writable(XOOPS_COMPILE_PATH))    {
    $tpl->assign('warningwriteable_compile', sprintf(_AD_WARNINGNOTWRITEABLE, XOOPS_COMPILE_PATH));
}
       
if (strpos(XOOPS_PATH, XOOPS_ROOT_PATH) !== false || strpos(XOOPS_PATH, $_SERVER['DOCUMENT_ROOT']) !== false) {
    $tpl->assign('warningxoopslibinside_path', sprintf(_AD_WARNINGXOOPSLIBINSIDE, XOOPS_PATH));
}

if (strpos(XOOPS_VAR_PATH, XOOPS_ROOT_PATH) !== false || strpos(XOOPS_VAR_PATH, $_SERVER['DOCUMENT_ROOT']) !== false) {
    $tpl->assign('warningxoopslibinside_var', sprintf(_AD_WARNINGXOOPSLIBINSIDE, XOOPS_VAR_PATH));
}
        
    }
}

?>