<?php
/**
 * Article module for XOOPS
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         article
 * @since           1.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: functions.render.php 2283 2008-10-12 03:36:13Z phppp $
 */
 
if (!defined('XOOPS_ROOT_PATH')) { exit(); }

include dirname(__FILE__) . "/vars.php";
define($GLOBALS["artdirname"] . "_FUNCTIONS_RENDER_LOADED", TRUE);

/**
 * Function to get template file of a specified style of a specified page
 * 
 * @var string     $page    page name
 * @var string     $style    template style
 *
 * @return string    template file name, using default style if style is invalid
 */
function portfolio_getTemplate($page = "index", $style = null)
{
    global $xoops;
    
    $template_dir = $xoops->path("modules/{$GLOBALS["artdirname"]}/templates/");
    $style = empty($style) ? "" : "_" . $style;
    $file_name = "{$GLOBALS["artdirname"]}_{$page}{$style}.html";
    if (file_exists($template_dir . $file_name)) return $file_name;
    if (!empty($style)) {
        $style = "";
        $file_name = "{$GLOBALS["artdirname"]}_{$page}{$style}.html";
        if(file_exists($template_dir . $file_name)) return $file_name;
    }
    return null;
}

/**
 * Function to get a list of template files of a page, indexed by file name
 * 
 * @var string     $page        page name
 * @var boolen     $refresh    recreate the data
 *
 * @return array
 */
function &portfolio_getTemplateList($page = "index", $refresh = false)
{
    $TplFiles = portfolio_getTplPageList($page, $refresh);
    $template = array();
    foreach (array_keys($TplFiles) as $temp) {
        $template[$temp] = $temp;
    }
    return $template;
}

/**
 * Function to get CSS file URL of a style
 * 
 * The hardcoded path is not desirable for theme switch, however, we have to keportfolio it before getting a good solution for cache
 *
 * @var string     $style
 *
 * @return string    file URL, false if not found
 */
function portfolio_getCss($style = "default")
{
    global $xoops;
    
    if (is_readable($xoops->path("modules/" . $GLOBALS["artdirname"] . "/templates/style_" . strtolower($style) . ".css"))) {
        return $xoops->path("modules/" . $GLOBALS["artdirname"] . "/templates/style_".strtolower($style).".css", true);
    }
    return $xoops->path("modules/" . $GLOBALS["artdirname"] . "/templates/style.css", true);
}

/**
 * Function to module header for a page with specified style
 * 
 * @var string     $style
 *
 * @return string
 */
function portfolio_getModuleHeader($style = "default")
{
    $xoops_module_header = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . portfolio_getCss($style) . "\" />";
    return $xoops_module_header;
}


/**
 * Function to get a list of template files of a page, indexed by style
 * 
 * @var string     $page    page name
 *
 * @return array
 */
function &portfolio_getTplPageList($page = "", $refresh = true)
{
    $list = null;
    
    $cache_file = empty($page) ? "template-list" : "template-case";
    /*
    load_functions("cache");
    $list = mod_loadCacheFile($cache_file, $GLOBALS["artdirname"]);
    */
    
    xoops_load("cache");
    $key = $GLOBALS["artdirname"] . "_{$cache_file}";
    $list = XoopsCache::read($key);
    
    if ( !is_array($list) || $refresh ) {
        $list = portfolio_template_lookup(!empty($page));
    }
    
    $ret = empty($page) ? $list : @$list[$page];
    return $ret;    
}

function &portfolio_template_lookup($index_by_page = false) 
{
    include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    
    $files = XoopsLists::getHtmlListAsArray(XOOPS_ROOT_PATH . "/modules/" . $GLOBALS["artdirname"] . "/templates/");
    $list = array();
    foreach ($files as $file => $name) {
        // The valid file name must be: art_article_mytpl.html OR art_category-1_your-trial.html
        if (preg_match("/^" . $GLOBALS["artdirname"] . "_([^_]*)(_(.*))?\.(html|xotpl)$/i", $name, $matches)) {
            if(empty($matches[1])) continue;
            if(empty($matches[3])) $matches[3] = "default";
            if (empty($index_by_page)) {
                $list[] = array("file" => $name, "description" => $matches[3]);
            } else {
                $list[$matches[1]][$matches[3]] = $name;
            }
        }
    }
    
    $cache_file = empty($index_by_page) ? "template-list" : "template-page";
    xoops_load("cache");
    $key = $GLOBALS["artdirname"] . "_{$cache_file}";
    XoopsCache::write($key, $list);
    
    //load_functions("cache");
    //mod_createCacheFile($list, $cache_file, $GLOBALS["artdirname"]);
    return $list;
}


?>
