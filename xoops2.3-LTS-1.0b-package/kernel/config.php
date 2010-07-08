<?php
/**
 * XOOPS user classes
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
 * @author          Kazumi Ono <webmaster@myweb.ne.jp>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           2.0
 * @package         kernel
 * @version         $Id$
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

require_once XOOPS_ROOT_PATH . '/kernel/configoption.php';
require_once XOOPS_ROOT_PATH . '/kernel/configitem.php';

/**
 * @package     kernel
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 */


/**
 * XOOPS configuration handling class.
 * This class acts as an interface for handling general configurations of XOOPS
 * and its modules.
 *
 *
 * @author          Kazumi Ono <webmaster@myweb.ne.jp>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @access  public
 */

class XoopsConfigHandler
{
    /**
     * holds reference to config item handler(DAO) class
     *
     * @var     object
     * @access  private
     */
    var $_cHandler;

    /**
     * holds reference to config option handler(DAO) class
     *
     * @var     object
     * @access  private
     */
    var $_oHandler;

    /**
     * holds an array of cached references to config value arrays,
     *  indexed on module id and category id
     *
     * @var     array
     * @access  private
     */
    var $_cachedConfigs = array();

    /**
     * Constructor
     *
     * @param   object  &$db    reference to database object
     */
    function XoopsConfigHandler(&$db)
    {
        $this->_cHandler = new XoopsConfigItemHandler($db);
        $this->_oHandler = new XoopsConfigOptionHandler($db);
    }

    /**
     * Create a config
     *
     * @see     XoopsConfigItem
     * @return  object  reference to the new {@link XoopsConfigItem}
     */
    function &createConfig()
    {
        $instance =& $this->_cHandler->create();
        return $instance;
    }

    /**
     * Get a config
     *
     * @param   int     $id             ID of the config
     * @param   bool    $withoptions    load the config's options now?
     * @return  object  reference to the {@link XoopsConfig}
     */
    function &getConfig($id, $withoptions = false)
    {
        $config =& $this->_cHandler->get($id);
        if ($withoptions == true) {
            $config->setConfOptions($this->getConfigOptions(new Criteria('conf_id', $id)));
        }
        return $config;
    }

    /**
     * insert a new config in the database
     *
     * @param   object  &$config    reference to the {@link XoopsConfigItem}
     */
    function insertConfig(&$config)
    {
        if (!$this->_cHandler->insert($config)) {
            return false;
        }
        $options =& $config->getConfOptions();
        $count = count($options);
        $conf_id = $config->getVar('conf_id');
        for ($i = 0; $i < $count; $i++) {
            $options[$i]->setVar('conf_id', $conf_id);
            if (!$this->_oHandler->insert($options[$i])) {
                foreach($options[$i]->getErrors() as $msg){
                    $config->setErrors($msg);
                }
            }
        }
        if (!empty($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')])) {
            unset ($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')]);
        }
        xoops_load("cache");
        $key = "config_" . intval($config->getVar('conf_modid')) . "_" . intval($config->getVar('conf_catid'));
        if (XoopsCache::read($key)) {
        	XoopsCache::delete($key);
        }
        
        return true;
    }

    /**
     * Delete a config from the database
     *
     * @param   object  &$config    reference to a {@link XoopsConfigItem}
     */
    function deleteConfig(&$config)
    {
        if (!$this->_cHandler->delete($config)) {
            return false;
        }
        $options =& $config->getConfOptions();
        $count = count($options);
        if ($count == 0) {
            $options = $this->getConfigOptions(new Criteria('conf_id', $config->getVar('conf_id')));
            $count = count($options);
        }
        if (is_array($options) && $count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $this->_oHandler->delete($options[$i]);
            }
        }
        if (!empty($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')])) {
            unset ($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')]);
        }
        xoops_load("cache");
        $key = "config_" . intval($config->getVar('conf_modid')) . "_" . intval($config->getVar('conf_catid'));
        if (XoopsCache::read($key)) {
        	XoopsCache::delete($key);
        }
        return true;
    }

    /**
     * get one or more Configs
     *
     * @param   object  $criteria       {@link CriteriaElement}
     * @param   bool    $id_as_key      Use the configs' ID as keys?
     * @param   bool    $with_options   get the options now?
     *
     * @return  array   Array of {@link XoopsConfigItem} objects
     */
    function getConfigs($criteria = null, $id_as_key = false, $with_options = false)
    {
        return $this->_cHandler->getObjects($criteria, $id_as_key);
    }

    /**
     * Count some configs
     *
     * @param   object  $criteria   {@link CriteriaElement}
     */
    function getConfigCount($criteria = null)
    {
        return $this->_cHandler->getCount($criteria);
    }

    /**
     * Get configs from a certain category
     *
     * @param   int $category   ID of a category
     * @param   int $module     ID of a module
     *
     * @return  array   array of {@link XoopsConfig}s
     */
    function &getConfigsByCat($category, $module = 0)
    {
        xoops_load("cache");
        $key = "config_" . intval($module) . "_" . intval($category);

        if (!$_cachedConfigs = XoopsCache::read($key)) {
            $_cachedConfigs = array();
            $criteria = new CriteriaCompo(new Criteria('conf_modid', intval($module)));
            if (!empty($category)) {
                $criteria->add(new Criteria('conf_catid', intval($category)));
            }
            $configs = $this->getConfigs($criteria, true);
            if (is_array($configs)) {
                foreach (array_keys($configs) as $i) {
                    $_cachedConfigs[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
                }
            }
            XoopsCache::write($key, $_cachedConfigs);
        }

        return $_cachedConfigs;
    }

    /**
     * Make a new {@link XoopsConfigOption}
     *
     * @return  object  {@link XoopsConfigOption}
     */
    function &createConfigOption()
    {
        $inst =& $this->_oHandler->create();
        return $inst;
    }

    /**
     * Get a {@link XoopsConfigOption}
     *
     * @param   int $id ID of the config option
     *
     * @return  object  {@link XoopsConfigOption}
     */
    function &getConfigOption($id)
    {
        $inst =& $this->_oHandler->get($id);
        return $inst;
    }

    /**
     * Get one or more {@link XoopsConfigOption}s
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @param   bool    $id_as_key  Use IDs as keys in the array?
     *
     * @return  array   Array of {@link XoopsConfigOption}s
     */
    function getConfigOptions($criteria = null, $id_as_key = false)
    {
        return $this->_oHandler->getObjects($criteria, $id_as_key);
    }

    /**
     * Count some {@link XoopsConfigOption}s
     *
     * @param   object  $criteria   {@link CriteriaElement}
     *
     * @return  int     Count of {@link XoopsConfigOption}s matching $criteria
     */
    function getConfigOptionsCount($criteria = null)
    {
        return $this->_oHandler->getCount($criteria);
    }

    /**
     * Get a list of configs
     *
     * @param   int $conf_modid ID of the modules
     * @param   int $conf_catid ID of the category
     *
     * @return  array   Associative array of name=>value pairs.
     */
    function getConfigList($conf_modid, $conf_catid = 0)
    {
        return $this->getConfigsByCat($conf_catid, $conf_modid);
    }

    /**
     * Load module configs
     *
     * @param   string      $dirname    dirname of a module
     * @param   int         $category   ID of a category
     *
     * @return  array       Associative array of name=>value pairs.
     */
    public function loadModuleConfig($dirname = "", $category = 0)
    {
        if (empty($dirname) && !empty($GLOBALS["xoopsModule"])) {
            $dirname = $GLOBALS["xoopsModule"]->getVar("dirname");
        }
        if (empty($dirname)) {
            return null;
        }

        xoops_load("cache");
        $key = "config_{$dirname}_{$category}";

        if (!$_cachedConfigs = XoopsCache::read($key)) {
            $module = 0;
            if ($dirname == "system") {
                $module = 0;
            } elseif (!empty($GLOBALS["xoopsModule"]) && $dirname == $GLOBALS["xoopsModule"]->getVar("dirname")) {
                $module = $GLOBALS["xoopsModule"]->getVar("mid");
            } else {
                $module_obj = xoops_getHandler("module")->getByDirname($dirname);
                if ($module_obj) {
                    $module = $module_obj->getVar("mid");
                } else {
                    return null;
                }
            }
            $_cachedConfigs = $this->getConfigsByCat($category, $module);
            XoopsCache::write($key, $_cachedConfigs);
        }

        return $_cachedConfigs;
    }

    /**
     * Clear config caches
     *
     * @param   mixed       $module     ID or dirname of a module
     * @param   int         $category   ID of a category
     *
     * @return  boolean     True on success, false on failure
     */
    public function flush($module = 0, $category = 0)
    {
        xoops_load("cache");
        $dirname = "";
        $mid = 0;
        if (is_int($module)) {
            $mid = intval($module);
            if (empty($mid)) {
                $dirname = "system";
            } else {
                if ($module_obj = xoops_getHandler("module")->get($mid)) {
                    $dirname = $module_obj->getVar("dirname");
                }
            }
        } else {
            $dirname = $module;
            if ("system" == $dirname) {
                $mid = 0;
            } else {
                if ($module_obj = xoops_getHandler("module")->getByDirname($dirname)) {
                    $mid = $module_obj->getVar("mid");
                }
            }
        }
        $key = "config_{$mid}_{$category}";
        XoopsCache::delete($key);
        $key = "config_{$dirname}_{$category}";
        XoopsCache::delete($key);
        return true;
    }
}
?>