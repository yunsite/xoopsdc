<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *  Xoops Form Class Elements
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         kernel
 * @subpackage      Xoop Forms class
 * @since           2.3.0
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          John Neill <catzwolf@xoops.org>
 * @version         $Id: theme.php 3780 2009-10-25 17:44:04Z kris_fr $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * xos_opal_ThemeFactory
 *
 * @author Skalpa Keo
 * @package xos_opal
 * @subpackage xos_opal_Theme
 * @since 2.3.0
 */
class xos_opal_ThemeFactory
{
    var $xoBundleIdentifier = 'xos_opal_ThemeFactory';
    /**
     * Currently enabled themes (if empty, all the themes in themes/ are allowed)
     *
     * @var array
     */
    var $allowedThemes = array();
    /**
     * Default theme to instanciate if none specified
     *
     * @var string
     */
    var $defaultTheme = 'default';
    /**
     * If users are allowed to choose a custom theme
     *
     * @var bool
     */
    var $allowUserSelection = true;

    /**
     * Instanciate the specified theme
     */
    function &createInstance($options = array(), $initArgs = array())
    {
        // Grab the theme folder from request vars if present
        if (empty($options['folderName'])) {
            if (($req = @$_REQUEST['xoops_theme_select']) && $this->isThemeAllowed($req)) {
                $options['folderName'] = $req;
                if (isset($_SESSION) && $this->allowUserSelection) {
                    $_SESSION[$this->xoBundleIdentifier]['defaultTheme'] = $req;
                }
            } else if (isset($_SESSION[$this->xoBundleIdentifier]['defaultTheme'])) {
                $options['folderName'] = $_SESSION[$this->xoBundleIdentifier]['defaultTheme'];
            } else if (empty($options['folderName']) || ! $this->isThemeAllowed($options['folderName'])) {
                $options['folderName'] = $this->defaultTheme;
            }
            $GLOBALS['xoopsConfig']['theme_set'] = $options['folderName'];
        }
        $options['path'] = XOOPS_THEME_PATH . '/' . $options['folderName'];
        $inst = new xos_opal_Theme();
        foreach ($options as $k => $v) {
            $inst->$k = $v;
        }
        $inst->xoInit();
        return $inst;
    }

    /**
     * Checks if the specified theme is enabled or not
     *
     * @param string $name
     * @return bool
     */
    function isThemeAllowed($name)
    {
        return (empty($this->allowedThemes) || in_array($name, $this->allowedThemes));
    }
}

/**
 * xos_opal_AdminThemeFactory
 *
 * @author Andricq Nicolas (AKA MusS)
 * @author trabis
 * @package xos_opal
 * @subpackage xos_opal_Theme
 * @since 2.4.0
 */
class xos_opal_AdminThemeFactory extends xos_opal_ThemeFactory
{
    function &createInstance($options = array(), $initArgs = array())
    {
        $options["plugins"] = array();
        $inst =& parent::createInstance($options, $initArgs);
        $inst->path = XOOPS_ROOT_PATH . '/modules/system/class/gui/' . $inst->folderName;
        $inst->url = XOOPS_URL . '/modules/system/class/gui/' . $inst->folderName;
        $inst->template->assign(array(
            'theme_path'  => $inst->path,
            'theme_tpl'   => $inst->path.'/xotpl',
            'theme_url'   => $inst->url,
            'theme_img'   => $inst->url.'/img',
            'theme_icons' => $inst->url.'/icons',
            'theme_css'   => $inst->url.'/css',
            'theme_js'    => $inst->url.'/js',
            'theme_lang'  => $inst->url.'/language',
            ));

        return $inst;
    }
}

/**
 * xos_opal_Theme
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2009
 * @version $Id: theme.php 3780 2009-10-25 17:44:04Z kris_fr $
 * @access public
 */
class xos_opal_Theme
{
    /**
     * The name of this theme
     *
     * @var string
     */
    var $folderName = '';
    /**
     * Physical path of this theme folder
     *
     * @var string
     */
    var $path = '';
    var $url = '';

    /**
     * Whether or not the theme engine should include the output generated by php
     *
     * @var string
     */
    var $bufferOutput = true;
    /**
     * Canvas-level template to use
     *
     * @var string
     */
    var $canvasTemplate = 'theme.html';

     /**
     * Theme folder path
     *
     * @var string
     */
    var $themesPath = 'themes';

    /**
     * Content-level template to use
     *
     * @var string
     */
    var $contentTemplate = '';

    var $contentCacheLifetime = 0;
    var $contentCacheId = null;

    /**
     * Text content to display right after the contentTemplate output
     *
     * @var string
     */
    var $content = '';
    /**
     * Page construction plug-ins to use
     *
     * @var array
     * @access public
     */
    var $plugins = array(
        'xos_logos_PageBuilder');
    var $renderCount = 0;
    /**
     * Pointer to the theme template engine
     *
     * @var XoopsTpl
     */
    var $template = false;

    /**
     * Array containing the document meta-information
     *
     * @var array
     */
    var $metas = array(
        //'http' => array(
        //    'Content-Script-Type' => 'text/javascript' ,
        //    'Content-Style-Type' => 'text/css') ,
        'meta' => array() ,
        'link' => array() ,
        'script' => array());

    /**
     * Array of strings to be inserted in the head tag of HTML documents
     *
     * @var array
     */
    var $htmlHeadStrings = array();
    /**
     * Custom variables that will always be assigned to the template
     *
     * @var array
     */
    var $templateVars = array();

    /**
     * User extra information for cache id, like language, user groups
     *
     * @var boolean
     */
    var $use_extra_cache_id = true;

    /**
     * *#@-
     */

    /**
     * *#@+
     *
     * @tasktype 10 Initialization
     */
    /**
     * Initializes this theme
     *
     * Upon initialization, the theme creates its template engine and instanciates the
     * plug-ins from the specified {@link $plugins} list. If the theme is a 2.0 theme, that does not
     * display redirection messages, the HTTP redirections system is disabled to ensure users will
     * see the redirection screen.
     *
     * @param array $options
     * @return bool
     */
    function xoInit($options = array())
    {
        $this->path = XOOPS_THEME_PATH . '/' . $this->folderName;
        $this->url = XOOPS_THEME_URL . '/' . $this->folderName;

        $this->template = new XoopsTpl();
        $this->template->currentTheme =& $this;
        $this->template->assign_by_ref('xoTheme', $this);
        $this->template->assign(array(
            'xoops_theme' => $GLOBALS['xoopsConfig']['theme_set'] ,
            'xoops_imageurl' => XOOPS_THEME_URL . '/' . $GLOBALS['xoopsConfig']['theme_set'] . '/',
            'xoops_themecss' => xoops_getcss($GLOBALS['xoopsConfig']['theme_set']),
            'xoops_requesturi' => htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES),
            'xoops_sitename' => htmlspecialchars($GLOBALS['xoopsConfig']['sitename'], ENT_QUOTES),
            'xoops_slogan' => htmlspecialchars($GLOBALS['xoopsConfig']['slogan'], ENT_QUOTES),
            'xoops_dirname' => isset($GLOBALS['xoopsModule'])&& is_object($GLOBALS['xoopsModule']) ? $GLOBALS['xoopsModule']->getVar('dirname') : 'system',
            'xoops_banner' => $GLOBALS['xoopsConfig']['banners'] ? xoops_getbanner() : '&nbsp;',
            'xoops_pagetitle' => isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule']) ? $GLOBALS['xoopsModule']->getVar('name') : htmlspecialchars($GLOBALS['xoopsConfig']['slogan'], ENT_QUOTES)));

        if (isset($GLOBALS['xoopsUser']) && is_object($GLOBALS['xoopsUser'])) {
            $this->template->assign(array(
                'xoops_isuser' => true ,
                'xoops_userid' => $GLOBALS['xoopsUser']->getVar('uid'),
                'xoops_uname' => $GLOBALS['xoopsUser']->getVar('uname'),
                'xoops_isadmin' => $GLOBALS['xoopsUserIsAdmin']));
        } else {
            $this->template->assign(array(
                'xoops_isuser' => false,
                'xoops_isadmin' => false));
        }
        // Meta tags
        $config_handler =& xoops_gethandler('config');
        //$criteria = new CriteriaCompo(new Criteria('conf_modid', 0));       // ezsky hack 
        //$criteria->add(new Criteria('conf_catid', XOOPS_CONF_METAFOOTER));  // ezsky hack
        //$config = $config_handler->getConfigs($criteria, true);            // ezsky hack
        $config = $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);  // ezsky hack

        foreach ( array_keys($config) as $i ) {
        $name = $i;        // ezsky hack
        $value= $config[$i]; // ezsky hack
        
            if (substr($name, 0, 5) == 'meta_') {
                $this->addMeta('meta', substr($name, 5), $value);
            } else {
                // prefix each tag with 'xoops_'
                $this->template->assign("xoops_$name", $value);
            }
        }
        // Load global javascript
        $this->addScript('include/xoops.js');
        $this->loadLocalization();

        if ($this->bufferOutput) {
            ob_start();
        }
        $GLOBALS['xoTheme'] =& $this;
        $GLOBALS['xoopsTpl'] =& $this->template;
        // Instanciate and initialize all the theme plugins
        foreach ($this->plugins as $k => $bundleId) {
            if (!is_object($bundleId)) {
                $this->plugins[$bundleId] = new $bundleId();
                $this->plugins[$bundleId]->theme =& $this;
                $this->plugins[$bundleId]->xoInit();
                unset($this->plugins[$k]);
            }
        }
        return true;
    }

    /**
     * Generate cache id based on extra information of language and user groups
     *
     * User groups other than anonymous should be detected to avoid disclosing group sensitive contents
     *
     * @param string $cache_id raw cache id
     * @param string $extraString extra string
     * @return string complete cache id
     */
    function generateCacheId($cache_id, $extraString = '')
    {
        static $extra_string;
        if (!$this->use_extra_cache_id) {
            return $cache_id;
        }

        if (empty($extraString)) {
            if (empty($extra_string)) {
                // Generate language section
                $extra_string = $GLOBALS['xoopsConfig']['language'];
                // Generate group section
                if (!isset($GLOBALS['xoopsUser']) || !is_object($GLOBALS['xoopsUser'])) {
                    $extra_string .= '|' . XOOPS_GROUP_ANONYMOUS;
                } else {
                    $groups = $GLOBALS['xoopsUser']->getGroups();
                    sort($groups);
                    // Generate group string for non-anonymous groups,
                    // XOOPS_DB_PASS and XOOPS_DB_NAME (before we find better variables) are used to protect group sensitive contents
                    $extra_string .= '|' . implode(',', $groups) . substr(md5(XOOPS_DB_PASS . XOOPS_DB_NAME), 0, strlen(XOOPS_DB_USER) * 2);
                }
            }
            $extraString = $extra_string;
        }
        $cache_id .= '|' . $extraString;
        return $cache_id;
    }

    /**
     * xos_opal_Theme::checkCache()
     *
     * @return
     */
    function checkCache()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' && $this->contentCacheLifetime) {
            $template = $this->contentTemplate ? $this->contentTemplate : 'db:system_dummy.html';
            $this->template->caching = 2;
            $this->template->cache_lifetime = $this->contentCacheLifetime;
            $uri = str_replace(XOOPS_URL, '', $_SERVER['REQUEST_URI']);
            // Clean uri by removing session id
            if (defined('SID') && SID && strpos($uri, SID)) {
                $uri = preg_replace("/([\?&])(" . SID . "$|" . SID . "&)/", "\\1", $uri);
            }
            $this->contentCacheId = $this->generateCacheId($uri);
            if ($this->template->is_cached($template, $this->contentCacheId)) {
                $xoopsLogger =& XoopsLogger::getInstance();
                $xoopsLogger->addExtra($template, sprintf('Cached (regenerates every %d seconds)', $this->contentCacheLifetime));
                $this->render(null, null, $template);
                return true;
            }
        }
        return false;
    }

    /**
     * Render the page
     *
     * The theme engine builds pages from 2 templates: canvas and content.
     *
     * A module can call this method directly and specify what templates the theme engine must use.
     * If render() hasn't been called before, the theme defaults will be used for the canvas and
     * page template (and xoopsOption['template_main'] for the content).
     *
     * @param string $canvasTpl The canvas template, if different from the theme default
     * @param string $pageTpl The page template, if different from the theme default (unsupported, 2.3+ only)
     * @param string $contentTpl The content template
     * @param array $vars Template variables to send to the template engine
     */
    function render($canvasTpl = null, $pageTpl = null, $contentTpl = null, $vars = array())
    {
        if ($this->renderCount) {
            return false;
        }
        $xoopsLogger =& XoopsLogger::getInstance();
        $xoopsLogger->startTime('Page rendering');
        //  @internal : Lame fix to ensure the metas specified in the xoops config page don't appear twice
        $old = array(
            'robots',
            'keywords',
            'description',
            'rating',
            'author',
            'copyright');
        foreach ($this->metas['meta'] as $name => $value) {
            if (in_array($name, $old)) {
                $this->template->assign("xoops_meta_$name", htmlspecialchars($value, ENT_QUOTES));
                unset($this->metas['meta'][$name]);
            }
        }
        if ($canvasTpl) {
            $this->canvasTemplate = $canvasTpl;
        }
        if ($contentTpl) {
            $this->contentTemplate = $contentTpl;
        }
        if (!empty($vars)) {
            $this->template->assign($vars);
        }
        if ($this->contentTemplate) {
            $this->content = $this->template->fetch($this->contentTemplate, $this->contentCacheId);
        }
        if ($this->bufferOutput) {
            $this->content .= ob_get_contents();
            ob_end_clean();
        }
        $this->template->assign_by_ref('xoops_contents', $this->content);
        // We assume no overlap between $GLOBALS['xoopsOption']['xoops_module_header'] and $this->template->get_template_vars( 'xoops_module_header' ) ?
        $header = empty($GLOBALS['xoopsOption']['xoops_module_header']) ? $this->template->get_template_vars('xoops_module_header') : $GLOBALS['xoopsOption']['xoops_module_header'];
        $this->template->assign('xoops_module_header', $this->renderMetas(null, true) . "\n" . $header);

        if (!empty($GLOBALS['xoopsOption']['xoops_pagetitle'])) {
            $this->template->assign('xoops_pagetitle', $GLOBALS['xoopsOption']['xoops_pagetitle']);
        }
        // Do not cache the main (theme.html) template output
        $this->template->caching = 0;
        $this->template->display($this->path . '/' . $this->canvasTemplate);
        $this->renderCount++;
        $xoopsLogger->stopTime('Page rendering');
    }

    /**
     * Load localization information
     *
     * Folder structure for localization:
     *                                                                             <ul>themes/themefolder/english
     *                                                                                 <li>main.php - language definitions</li>
     *                                                                                 <li>style.css - localization stylesheet</li>
     *                                                                                 <li>script.js - localization script</li>
     *                                                                             </ul>
     */
    function loadLocalization($type = "main")
    {
        $language = $GLOBALS["xoopsConfig"]["language"];
        // Load global localization stylesheet if available
        if (file_exists($GLOBALS['xoops']->path('language/' . $language . '/style.css'))) {
            $this->addStylesheet($GLOBALS['xoops']->url('language/' . $language . '/style.css'));
        }
        if (!file_exists($this->path . '/language/' . $language)) {
            return true;
        }
        $this->addLanguage($type);
        // Load theme localization stylesheet and scripts if available
        if (file_exists($this->path . '/language/' . $language . '/script.js')) {
            $this->addScript($this->url . '/language/' . $language . '/script.js');
        }
        if (file_exists($this->path . '/language/' . $language . '/style.css')) {
            $this->addStylesheet($this->url . '/language/' . $language . '/style.css');
        }
        return true;
    }

    /**
     * Load theme specific language constants
     *
     * @param string $type language type, like 'main', 'admin'; Needs to be declared in theme xo-info.php
     * @param string $language specific language
     */
    function addLanguage($type = "main", $language = null)
    {
        $language = is_null($language) ? $GLOBALS["xoopsConfig"]["language"] : $language;
        if (!file_exists($fileinc = $GLOBALS['xoops']->path($this->resourcePath("/language/{$language}/{$type}.php")))) {
            if (!file_exists($fileinc = $GLOBALS['xoops']->path($this->resourcePath( "/language/english/{$type}.php")))) {
                return false;
            }
        }
        $ret = include_once $fileinc;
        return $ret;
    }

    /**
     * *#@+
     *
     * @tasktype 20 Manipulating page meta-information
     */
    /**
     * Adds script code to the document head
     *
     * This methods allows the insertion of an external script file (if $src is provided), or
     * of a script snippet. The file URI is parsed to take benefit of the theme resource
     * overloading system.
     *
     * The $attributes parameter allows you to specify the attributes that will be added to the
     * inserted <script> tag. If unspecified, the <var>type</var> attribute value will default to
     * 'text/javascript'.
     *
     * <code>
     * // Add an external script using a physical path
     * $theme->addScript( 'www/script.js', null, '' );
     * $theme->addScript( 'modules/newbb/script.js', null, '' );
     * // Specify attributes for the <script> tag
     * $theme->addScript( 'mod_xoops_SiteManager#common.js', array( 'type' => 'application/x-javascript' ), '' );
     * // Insert a code snippet
     * $theme->addScript( null, array( 'type' => 'application/x-javascript' ), 'window.open("Hello world");' );
     * </code>
     *
     * @param string $src path to an external script file
     * @param array $attributes hash of attributes to add to the <script> tag
     * @param string $content Code snippet to output within the <script> tag
     * @return void
     */
    function addScript($src = '', $attributes = array(), $content = '')
    {
        if (empty($attributes)) {
            $attributes = array();
        }
        if (!empty($src)) {
            $src = $GLOBALS['xoops']->url($this->resourcePath($src));
            $attributes['src'] = $src;
        }
        if (!empty($content)) {
            $attributes['_'] = $content;
        }
        if (!isset($attributes['type'])) {
            $attributes['type'] = 'text/javascript';
        }
        $this->addMeta('script', $src, $attributes);
    }


    /**
     * Add StyleSheet or CSS code to the document head
     *
     * @param string $src path to .css file
     * @param array $attributes name => value paired array of attributes such as title
     * @param string $content CSS code to output between the <style> tags (in case $src is empty)
     * @return void
     */
    function addStylesheet($src = '', $attributes = array(), $content = '')
    {
        if (empty($attributes)) {
            $attributes = array();
        }
        if (!empty($src)) {
            $src = $GLOBALS['xoops']->url($this->resourcePath($src));
            $attributes['href'] = $src;
        }
        if (!isset($attributes['type'])) {
            $attributes['type'] = 'text/css';
        }
        if (!empty($content)) {
            $attributes['_'] = $content;
        }
        $this->addMeta('stylesheet', $src, $attributes);
    }

    /**
     * Add a <link> to the header
     *
     * @param string $rel Relationship from the current doc to the anchored one
     * @param string $href URI of the anchored document
     * @param array $attributes Additional attributes to add to the <link> element
     */
    function addLink($rel, $href = '', $attributes = array())
    {
        if (empty($attributes)) {
            $attributes = array();
        }
        if (!empty($href)) {
            $attributes['href'] = $href;
        }
        $this->addMeta('link', $rel, $attributes);
    }

    /**
     * Set a meta http-equiv value
     */
    function addHttpMeta($name, $value = null)
    {
        if (isset($value)) {
            return $this->addMeta('http', $name, $value);
        }
        unset($this->metas['http'][$name]);
    }

    /**
     * Change output page meta-information
     */
    function addMeta($type = 'meta', $name = '', $value = '')
    {
        if (!isset($this->metas[$type])) {
            $this->metas[$type] = array();
        }
        if (!empty($name)) {
            $this->metas[$type][$name] = $value;
        } else {
            $this->metas[$type][] = $value;
        }
        return $value;
    }

    /**
     * xos_opal_Theme::headContent()
     *
     * @param mixed $params
     * @param mixed $content
     * @param mixed $smarty
     * @param mixed $repeat
     * @return
     */
    function headContent($params, $content, &$smarty, &$repeat)
    {
        if (!$repeat) {
            $this->htmlHeadStrings[] = $content;
        }
    }

    /**
     * xos_opal_Theme::renderMetas()
     *
     * @param mixed $type
     * @param mixed $return
     * @return
     */
    function renderMetas($type = null, $return = false)
    {
        $str = '';
        if (!isset($type)) {
            foreach (array_keys($this->metas) as $type) {
                $str .= $this->renderMetas($type, true);
            }
            $str .= implode("\n", $this->htmlHeadStrings);
        } else {
            switch ($type) {
                case 'script':
                    foreach ($this->metas[$type] as $attrs) {
                        $str .= "<script" . $this->renderAttributes($attrs) . ">";
                        if (@$attrs['_']) {
                            $str .= "\n//<![CDATA[\n" . $attrs['_'] . "\n//]]>";
                        }
                        $str .= "</script>\n";
                    }
                    break;
                case 'link':
                    foreach ($this->metas[$type] as $rel => $attrs) {
                        $str .= '<link rel="' . $rel . '"' . $this->renderAttributes($attrs) . " />\n";
                    }
                    break;
                case 'stylesheet':
                    foreach ($this->metas[$type] as $attrs) {
                        if (@$attrs['_']) {
                            $str .= '<style' . $this->renderAttributes($attrs) . ">\n/* <![CDATA[ */\n" . $attrs['_'] . "\n/* //]]> */\n</style>";
                        } else {
                            $str .= '<link rel="stylesheet"' . $this->renderAttributes($attrs) . " />\n";
                        }
                    }
                    break;
                case 'http':
                    foreach ($this->metas[$type] as $name => $content) {
                        $str .= '<meta http-equiv="' . htmlspecialchars($name, ENT_QUOTES) . '" content="' . htmlspecialchars($content, ENT_QUOTES) . "\" />\n";
                    }
                    break;
                default:
                    foreach ($this->metas[$type] as $name => $content) {
                        $str .= '<meta name="' . htmlspecialchars($name, ENT_QUOTES) . '" content="' . htmlspecialchars($content, ENT_QUOTES) . "\" />\n";
                    }
                    break;
            }
        }
        if ($return) {
            return $str;
        }
        echo $str;
        return true;
    }

    /**
     * Generates a unique element ID
     *
     * @param string $tagName
     * @return string
     */
    function genElementId($tagName = 'xos')
    {
        static $cache = array();
        if (!isset($cache[$tagName])) {
            $cache[$tagName] = 1;
        }
        return $tagName . '-' . $cache[$tagName] ++;
    }

    /**
     * Transform an attributes collection to an XML string
     *
     * @param array $coll
     * @return string
     */
    function renderAttributes($coll)
    {
        $str = '';
        foreach ($coll as $name => $val) {
            if ($name != '_') {
                $str .= ' ' . $name . '="' . htmlspecialchars($val, ENT_QUOTES) . '"';
            }
        }
        return $str;
    }

    /**
     * Return a themable file resource path
     *
     * @param string $path
     * @return string
     */
    function resourcePath($path)
    {
        if (substr($path, 0, 1) == '/') {
            $path = substr($path, 1);
        }
        if (file_exists("{$this->path}/{$path}")) {
            return "{$this->themesPath}/{$this->folderName}/{$path}";
        }
        return $path;
    }
}

?>