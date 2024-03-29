<?php
/**
 * XoopsObject and handlers
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
 * @package          kernel
 * @since            2.3.0
 * @author           Kazumi Ono (AKA onokazu>
 * @author           Taiwen Jiang <phppp@users.sourceforge.net>
 * @version          $Id: object.php 2209 2008-10-01 04:35:42Z phppp $
 */


/**#@+
 * Xoops object datatype
 *
 **/
define('XOBJ_DTYPE_TXTBOX',     1);
define('XOBJ_DTYPE_TXTAREA',    2);
define('XOBJ_DTYPE_INT',        3);
define('XOBJ_DTYPE_URL',        4);
define('XOBJ_DTYPE_EMAIL',      5);
define('XOBJ_DTYPE_ARRAY',      6);
define('XOBJ_DTYPE_OTHER',      7);
define('XOBJ_DTYPE_SOURCE',     8);
define('XOBJ_DTYPE_STIME',      9);
define('XOBJ_DTYPE_MTIME',      10);
define('XOBJ_DTYPE_LTIME',      11);
/**#@-*/

//include_once "xoopspluginloader.php";
    
/**
 * Base class for all objects in the Xoops kernel (and beyond) 
 * 
 * @author Kazumi Ono (AKA onokazu)
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright copyright &copy; The XOOPS project
 * @package kernel
 **/
class XoopsObject
{
    /**
     * holds all variables(properties) of an object
     * 
     * @var array
     * @access protected
     **/
    var $vars = array();

    /**
     * variables cleaned for store in DB
     * 
     * @var array
     * @access protected
     */
    var $cleanVars = array();

    /**
     * is it a newly created object?
     * 
     * @var bool
     * @access private
     */
    var $_isNew = false;

    /**
     * has any of the values been modified?
     * 
     * @var bool
     * @access private
     */
    var $_isDirty = false;

    /**
     * errors
     * 
     * @var array
     * @access private
     */
    var $_errors = array();

    /**
     * additional filters registered dynamically by a child class object
     * 
     * @access private
     */
    var $_filters = array();

    /**
     * constructor
     * 
     * normally, this is called from child classes only
     * @access public
     */
    function XoopsObject()
    {
    }

    /**#@+
     * used for new/clone objects
     * 
     * @access public
     */
    function setNew()
    {
        $this->_isNew = true;
    }
    function unsetNew()
    {
        $this->_isNew = false;
    }
    function isNew()
    {
        return $this->_isNew;
    }
    /**#@-*/

    /**#@+
     * mark modified objects as dirty
     * 
     * used for modified objects only
     * @access public
     */
    function setDirty()
    {
        $this->_isDirty = true;
    }
    function unsetDirty()
    {
        $this->_isDirty = false;
    }
    function isDirty()
    {
        return $this->_isDirty;
    }
    /**#@-*/

    /**
     * initialize variables for the object
     * 
     * @access public
     * @param string $key
     * @param int $data_type  set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER if no data type ckecking nor text sanitizing is required)
     * @param mixed
     * @param bool $required  require html form input?
     * @param int $maxlength  for XOBJ_DTYPE_TXTBOX type only
     * @param string $option  does this data have any select options?
     */
    function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '')
    {
        $this->vars[$key] = array('value' => $value, 'required' => $required, 'data_type' => $data_type, 'maxlength' => $maxlength, 'changed' => false, 'options' => $options);
    }

    /**
     * assign a value to a variable
     * 
     * @access public
     * @param string $key name of the variable to assign
     * @param mixed $value value to assign
     */
    function assignVar($key, $value)
    {
        if (isset($key) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] =& $value;
        }
    }

    /**
     * assign values to multiple variables in a batch
     * 
     * @access private
     * @param array $var_array associative array of values to assign
     */
    function assignVars($var_arr)
    {
        foreach ($var_arr as $key => $value) {
            $this->assignVar($key, $value);
        }
    }

    /**
     * assign a value to a variable
     * 
     * @access public
     * @param string $key name of the variable to assign
     * @param mixed $value value to assign
     * @param bool $not_gpc
     */
    function setVar($key, $value, $not_gpc = false)
    {
        if (!empty($key) && isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] =& $value;
            $this->vars[$key]['not_gpc'] = $not_gpc;
            $this->vars[$key]['changed'] = true;
            $this->setDirty();
        }
    }

    /**
     * assign values to multiple variables in a batch
     * 
     * @access private
     * @param array $var_arr associative array of values to assign
     * @param bool $not_gpc
     */
    function setVars($var_arr, $not_gpc = false)
    {
        foreach ($var_arr as $key => $value) {
            $this->setVar($key, $value, $not_gpc);
        }
    }

    /**
     * unset variable(s) for the object
     * 
     * @access   public
     * @param    mixed $var
     */
    function destoryVars($var)
    {
        if (empty($var)) return true;
        $var = !is_array($var) ? array($var) : $var;
        foreach ($var as $key) {
            if (!isset($this->vars[$key])) continue;
            $this->vars[$key]["changed"] = null;
        }
        return true;
    }

    /**
     * Assign values to multiple variables in a batch
     *
     * Meant for a CGI contenxt:
     * - prefixed CGI args are considered save
     * - avoids polluting of namespace with CGI args
     *
     * @access private
     * @param array $var_arr associative array of values to assign
     * @param string $pref prefix (only keys starting with the prefix will be set)
     */
    function setFormVars($var_arr = null, $pref = 'xo_', $not_gpc = false)
    {
        $len = strlen($pref);
        foreach ($var_arr as $key => $value) {
            if ($pref == substr($key,0,$len)) {
                $this->setVar(substr($key,$len), $value, $not_gpc);
            }
        }
    }

    /**
     * returns all variables for the object
     * 
     * @access public
     * @return array associative array of key->value pairs
     */
    function &getVars()
    {
        return $this->vars;
    }
    
    /**
     * Returns the values of the specified variables
     *
     * @param mixed $keys An array containing the names of the keys to retrieve, or null to get all of them
     * @param string $format Format to use (see getVar)
     * @param int $maxDepth Maximum level of recursion to use if some vars are objects themselves
     * @return array associative array of key->value pairs
     */
    function getValues( $keys = null, $format = 's', $maxDepth = 1 )
    {
        if ( !isset( $keys ) ) {
            $keys = array_keys( $this->vars );
        }
        $vars = array();
        foreach ( $keys as $key ) {
            if ( isset( $this->vars[$key] ) ) {
                if ( is_object( $this->vars[$key] ) && is_a( $this->vars[$key], 'XoopsObject' ) ) {
                    if ( $maxDepth ) {
                        $vars[$key] = $this->vars[$key]->getValues( null, $format, $maxDepth - 1 );
                    }
                } else {
                    $vars[$key] = $this->getVar( $key, $format );
                }
            }
        }
        return $vars;
    }
    
    /**
    * returns a specific variable for the object in a proper format
    * 
    * @access public
    * @param string $key key of the object's variable to be returned
    * @param string $format format to use for the output
    * @return mixed formatted value of the variable
    */
    function getVar($key, $format = 's')
    {
        $ret = null;
        if ( !isset($this->vars[$key]) ) return $ret ;
        $ret = $this->vars[$key]['value'];
        
        switch ($this->vars[$key]['data_type']) {

        case XOBJ_DTYPE_TXTBOX:
            switch (strtolower($format)) {
            case 's':
            case 'show':
            case 'e':
            case 'edit':
                $ts =& MyTextSanitizer::getInstance();
                return $ts->htmlSpecialChars($ret);
                break 1;
            case 'p':
            case 'preview':
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        case XOBJ_DTYPE_TXTAREA:
            switch (strtolower($format)) {
            case 's':
            case 'show':
                $ts =& MyTextSanitizer::getInstance();
                $html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
                return $ts->displayTarea($ret, $html, $smiley, $xcode, $image, $br);
                break 1;
            case 'e':
            case 'edit':
                return htmlspecialchars($ret, ENT_QUOTES);
                break 1;
            case 'p':
            case 'preview':
                $ts =& MyTextSanitizer::getInstance();
                $html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
                return $ts->previewTarea($ret, $html, $smiley, $xcode, $image, $br);
                break 1;
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                return htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        case XOBJ_DTYPE_ARRAY:
            if (!is_array($ret)) {
                if ($ret != "") {
                    $ret = @unserialize( $ret );
                }
                $ret = is_array($ret) ? $ret : array();
            }
            break;
        case XOBJ_DTYPE_SOURCE:
            switch (strtolower($format)) {
            case 's':
            case 'show':
                break 1;
            case 'e':
            case 'edit':
                return htmlspecialchars($ret, ENT_QUOTES);
                break 1;
            case 'p':
            case 'preview':
                $ts =& MyTextSanitizer::getInstance();
                return $ts->stripSlashesGPC($ret);
                break 1;
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                return htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        default:
            if ($this->vars[$key]['options'] != '' && $ret != '') {
                switch (strtolower($format)) {
                case 's':
                case 'show':
                    $selected = explode('|', $ret);
                    $options = explode('|', $this->vars[$key]['options']);
                    $i = 1;
                    $ret = array();
                    foreach ($options as $op) {
                        if (in_array($i, $selected)) {
                            $ret[] = $op;
                        }
                        $i++;
                    }
                    return implode(', ', $ret);
                case 'e':
                case 'edit':
                    $ret = explode('|', $ret);
                    break 1;
                default:
                    break 1;
                }

            }
            break;
        }
        return $ret;
    }

    /**
     * clean values of all variables of the object for storage. 
     * also add slashes whereever needed
     * 
     * @return bool true if successful
     * @access public
     */
    function cleanVars()
    {
        $ts =& MyTextSanitizer::getInstance();
        $existing_errors = $this->getErrors();
        $this->_errors = array();
        foreach ($this->vars as $k => $v) {
            $cleanv = $v['value'];
            if (!$v['changed']) {
            } else {
                $cleanv = is_string($cleanv) ? trim($cleanv) : $cleanv;
                switch ($v['data_type']) {
                case XOBJ_DTYPE_TXTBOX:
                    if ($v['required'] && $cleanv != '0' && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if (isset($v['maxlength']) && strlen($cleanv) > intval($v['maxlength'])) {
                        $this->setErrors( sprintf( _XOBJ_ERR_SHORTERTHAN, $k, intval( $v['maxlength'] ) ) );
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                    } else {
                        $cleanv = $ts->censorString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_TXTAREA:
                    if ($v['required'] && $cleanv != '0' && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                    } else {
                        $cleanv = $ts->censorString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_SOURCE:
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($cleanv);
                    } else {
                        $cleanv = $cleanv;
                    }
                    break;
                case XOBJ_DTYPE_INT:
                    $cleanv = intval($cleanv);
                    break;
                case XOBJ_DTYPE_EMAIL:
                    if ($v['required'] && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if ($cleanv != '' && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$cleanv)) {
                        $this->setErrors("Invalid Email");
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_URL:
                    if ($v['required'] && $cleanv == '') {
                        $this->setErrors( sprintf( _XOBJ_ERR_REQUIRED, $k ) );
                        continue;
                    }
                    if ($cleanv != '' && !preg_match("/^http[s]*:\/\//i", $cleanv)) {
                        $cleanv = 'http://' . $cleanv;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv =& $ts->stripSlashesGPC($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_ARRAY:
                    $cleanv = serialize($cleanv);
                    break;
                case XOBJ_DTYPE_STIME:
                case XOBJ_DTYPE_MTIME:
                case XOBJ_DTYPE_LTIME:
                    $cleanv = !is_string($cleanv) ? intval($cleanv) : strtotime($cleanv);
                    break;
                default:
                    break;
                }
            }
            $this->cleanVars[$k] =& $cleanv;
            unset($cleanv);
        }
        if (count($this->_errors) > 0) {
            $this->_errors = array_merge($existing_errors, $this->_errors);
            return false;
        }
        $this->_errors = array_merge($existing_errors, $this->_errors);
        $this->unsetDirty();
        return true;
    }

    /**
     * dynamically register additional filter for the object
     * 
     * @param string $filtername name of the filter
     * @access public
     */
    function registerFilter($filtername)
    {
        $this->_filters[] = $filtername;
    }

    /**
     * load all additional filters that have been registered to the object
     * 
     * @access private
     */
    function _loadFilters()
    {
        static $loaded;
        if (isset($loaded)) return;
        $loaded = 1;
        
        $path = empty($this->plugin_path) ? dirname(__FILE__) . '/filters' : $this->plugin_path;
        @include_once $path . '/filter.php';
        foreach ($this->_filters as $f) {
            @include_once $path . '/' . strtolower($f) . 'php';
        }
    }
    
    /**
     * load all local filters for the object
     * 
     * Filter distribution:
     * In each module folder there is a folder "filter" containing filter files with, 
     * filename: [name_of_target_class][.][function/action_name][.php];
     * function name: [dirname][_][name_of_target_class][_][function/action_name];
     * parameter: the target object
     *
     * @param   string     $method    function or action name
     * @access  public
     */
    function loadFilters($method)
    {
        $this->_loadFilters();
        
        xoops_load("cache");
        $class = get_class($this);
        if (!$modules_active = XoopsCache::read("system_modules_active")) {
            $module_handler =& xoops_gethandler('module');
            $modules_obj = $module_handler->getObjects(new Criteria('isactive', 1));
            $modules_active = array();
            foreach (array_keys($modules_obj) as $key) {
                $modules_active[] = $modules_obj[$key]->getVar("dirname");
            }
            unset($modules_obj);
            XoopsCache::write("system_modules_active", $modules_active);
        }
        foreach ($modules_active as $dirname) {
            if (!@include_once XOOPS_ROOT_PATH . "/modules/{$dirname}/filter/{$class}.{$method}.php") continue;
            if (function_exists("{$dirname}_{$class}_{$method}")) {
                call_user_func_array("{$dirname}_{$class}_{$method}", array(&$this));
            }
        }
    }

    /**
     * create a clone(copy) of the current object
     * 
     * @access public
     * @return object clone
     */
    function &xoopsClone()
    {
        $class = get_class($this);
        $clone =& new $class();
        foreach ($this->vars as $k => $v) {
            $clone->assignVar($k, $v['value']);
        }
        // need this to notify the handler class that this is a newly created object
        $clone->setNew();
        return $clone;
    }

    /**
     * add an error 
     * 
     * @param string $value error to add
     * @access public
     */
    function setErrors($err_str)
    {
        if (is_array($err_str)) {
            $this->_errors = array_merge($this->_errors, $err_str);
        } else {
            $this->_errors[] = trim($err_str);
        }
    }

    /**
     * return the errors for this object as an array
     * 
     * @return array an array of errors
     * @access public
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     * 
     * @return string html listing the errors
     * @access public
     */
    function getHtmlErrors()
    {
        $ret = '<h4>Errors</h4>';
        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $ret .= $error.'<br />';
            }
        } else {
            $ret .= 'None<br />';
        }
        return $ret;
    }
    
    /**
     * Returns an array representation of the object
     *
     * Deprecated, use getValues() directly
     * @return array
     */
    function toArray()
    {
        return $this->getValues();
    }
}

/**
 * XOOPS object handler class.  
 * This class is an abstract class of handler classes that are responsible for providing
 * data access mechanisms to the data source of its corresponsing data objects
 * @package kernel
 * @abstract
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright &copy; 2000 The XOOPS Project
 */
class XoopsObjectHandler
{

    /**
     * holds referenced to {@link XoopsDatabase} class object
     * 
     * @var object
     * @see XoopsDatabase
     * @access protected
     */
    var $db;

    // 
    /**
     * called from child classes only
     * 
     * @param object $db reference to the {@link XoopsDatabase} object
     * @access protected
     */
    function XoopsObjectHandler(&$db)
    {
        $this->db =& $db;
    }

    /**
     * creates a new object
     * 
     * @abstract
     */
    function &create()
    {
    }

    /**
     * gets a value object
     * 
     * @param int $int_id
     * @abstract
     */
    function &get($int_id)
    {
    }

    /**
     * insert/update object
     * 
     * @param object $object
     * @abstract
     */
    function insert(&$object)
    {
    }

    /**
     * delete obejct from database
     * 
     * @param object $object
     * @abstract
     */
    function delete(&$object)
    {
    }

}



/**
 * Persistable Object Handler class.  
 *
 * @author  Taiwen Jiang <phppp@users.sourceforge.net>
 * @author  Jan Keller Pedersen <mithrandir@xoops.org> - IDG Danmark A/S <www.idg.dk>
 * @copyright copyright (c) The XOOPS project 
 * @package Kernel
 */

class XoopsPersistableObjectHandler extends XoopsObjectHandler {

    /**
     * holds reference to custom extended object handler
     * 
     * var object
     * @access private
     */
    /*static protected*/var $handler;

    /**
     * holds reference to predefined extended object handlers: read, stats, joint, write, sync
     *
     * The handlers hold methods for different purposes, which could be all put together inside of current class.
     * However, load codes only if they are necessary, thus they are now splitted out.
     *
     * var array of objects
     * @access private
     */
    /*static protected*/ var $handlers = array(
            'read'  => null,
            'stats' => null,
            'joint' => null,
            'write' => null,
            'sync'  => null,
        );
    
    /**#@+
     * Information about the class, the handler is managing
     *
     * @var string
     * @access public
     */
    var $table;
    var $keyName;
    var $className;
    var $identifierName;
    /**#@-*/

    /**
     * Constructor
     *
     * @access protected
     *
     * @param object     $db         {@link XoopsDatabase} object
     * @param string     $tablename  Name of database table
     * @param string     $classname  Name of Class, this handler is managing
     * @param string     $keyname    Name of the property, holding the key
     *
     * @return void
     */
    function __construct($db = NULL, $table = "", $className = "", $keyName = "", $identifierName = "")
    {
        $db = ( is_object($db) && is_a($db, "XoopsDatabase") ) ? $db : $GLOBALS["xoopsDB"];
        $table = $db->prefix($table);
        
        $this->XoopsObjectHandler($db);
        $this->table = $table;
        $this->keyName = $keyName;
        $this->className = $className;
        if ($identifierName) {
            $this->identifierName = $identifierName;
        }
    }
    
    /**
     * Constructor
     *
     * @access protected
     */
    function XoopsPersistableObjectHandler($db = NULL, $table = "", $className = "", $keyName = "", $identifierName = "")
    {
        $this->__construct($db, $table, $className, $keyName, $identifierName);
    }
    
    /**
     * Set custom handler
     *
     * @access  protected
     *
     * @param   object  the handler
     * @param   mixed   args
     * @param   string  $path   path to class
     * @return  object of handler
     */
    function setHandler($handler = null, $args = null, $path = null)
    {
        $this->handler = null;
        if (is_object($handler)) {
            $this->handler = $handler;
        } elseif (is_string($handler)) {
            xoops_load("model");
            $this->handler = XoopsModelFactory::loadHandler($this, $name, $args, $path);
        }
        
        return $this->handler;
    }
    
    /**
     * Load predefined handler
     *
     * @access  protected
     *
     * @param   string  $name   handler name
     * @param   mixed   $args   args
     * @return  object of handler {@link XoopsObjectAbstract}
     */
    function loadHandler($name, $args = null)
    {
        static $handlers;
        if (!isset($handlers[$name])) {
            xoops_load("model");
            $handlers[$name] = XoopsModelFactory::loadHandler($this, $name, $args);
        } else {
            $handlers[$name]->setHandler($this);
               $handlers[$name]->setVars($args);
        }
        
        return $handlers[$name];
        
        /*
        // Following code just kept as placeholder for PHP5
        if (!isset(self::$handlers[$name])) {
            self::$handlers[$name] = XoopsModelFactory::loadHandler($this, $name, $args);
        } else {
            self::$handlers[$name]->setHandler($this);
               self::$handlers[$name]->setVars($args);
        }
        
        return self::$handlers[$name];
        */
    }
    
    /**
     * Magic method for overloading of delegation
     *
     * To be enabled in XOOPS 3.0 with PHP 5
     * @access protected
     *
     * @param   string  $name    method name
     * @param   array   $args    arguments
     * @return  mixed
     */
    function __call($name, $args)
    {
        if ( is_object($this->handler) && is_callable( array($this->handler, $name) ) ) {
            return call_user_func_array( array($this->handler, $name), $args );
        }
        foreach ( array_keys($this->handlers) as $_handler ) {
            $handler = $this->loadHandler($_handler);
            if ( is_callable( array($handler, $name) ) ) {
                return call_user_func_array( array($handler, $name), $args );
            }
        }
        
        return null;
    }
    
    /**#@+
     * Methods of native handler {@link XoopsPersistableObjectHandler}
     */
    /**
     * create a new object
     *
     * @access protected
     *
     * @param   bool  $isNew Flag the new objects as new
     * @return  object {@link XoopsObject}
     */
    function &create($isNew = true) 
    {
        $obj =& new $this->className();
        if ($isNew === true) {
            $obj->setNew();
        }
        return $obj;
    }
    
    /**
     * Load a {@link XoopsObject} object from the database
     *
     * @access protected
     * 
     * @param   mixed    $id     ID
     * @param   array    $fields    fields to fetch
     * @return  object  {@link XoopsObject}
     **/
    function &get($id = null, $fields = null)
    {
        $object = null;
        if (empty($id)) {
            $object = $this->create();
            return $object;
        }
        
        if (is_array($fields) && count($fields) > 0) {
            $select = implode(",", $fields);
            if (!in_array($this->keyName, $fields)){
                $select .= ", " . $this->keyName;
            }
        } else {
            $select = "*";
        }
        $sql = "SELECT {$select} FROM {$this->table} WHERE {$this->keyName} = " . $this->db->quote($id);
        if (!$result = $this->db->query($sql)) {
            return $object;
        }
        
        if (!$this->db->getRowsNum($result)) {
            return $object;
        }
        
        $object =& $this->create(false);
        $object->assignVars($this->db->fetchArray($result));
        
        return $object;
    }    
    /**#@-*/
    
    /**#@+
     * Methods of write handler {@link XoopsObjectWrite}
     */
    /**
     * insert an object into the database
     * 
     * @param    object    $object     {@link XoopsObject} reference to object
     * @param     bool     $force         flag to force the query execution despite security settings
     * @return     mixed     object ID
     */
    function insert(&$object, $force = true)
    {
        $handler = $this->loadHandler("write");
        return $handler->insert($object, $force);
    }

    /**
     * delete an object from the database
     * 
     * @param   object  $object {@link XoopsObject} reference to the object to delete
     * @param   bool    $force
     * @return  bool    FALSE if failed.
     */
    function delete(&$object, $force = false)
    {
        $handler = $this->loadHandler("write");
        return $handler->delete($object, $force);
    }
    
    /**
     * delete all objects matching the conditions
     * 
     * @param    object    $criteria   {@link CriteriaElement} with conditions to meet
     * @param     bool    $force        force to delete
     * @param     bool    $asObject   delete in object way: instantiate all objects and delte one by one    
     * @return bool
     */
    function deleteAll($criteria = null, $force = true, $asObject = false)
    {
        $handler = $this->loadHandler("write");
        return $handler->deleteAll($criteria, $force, $asObject);
    }
    
    /**
     * Change a field for objects with a certain criteria
     * 
     * @param   string  $fieldname  Name of the field
     * @param   mixed   $fieldvalue Value to write
     * @param   object  $criteria   {@link CriteriaElement} 
     * @param     bool    $force        force to query
     * 
     * @return  bool
     **/
    function updateAll($fieldname, $fieldvalue, $criteria = null, $force = false)
    {
        $handler = $this->loadHandler("write");
        return $handler->updateAll($fieldname, $fieldvalue, $criteria, $force);
    }
    /**#@-*/
    
    /**#@+
     * Methods of read handler {@link XoopsObjectRead}
     */
    /**
     * Retrieve objects from the database
     * 
     * @param object    $criteria   {@link CriteriaElement} conditions to be met
     * @param bool      $id_as_key  use the ID as key for the array
     * @param bool      $as_object  return an array of objects
     *
     * @return array
     */
    function &getObjects($criteria = null, $id_as_key = false, $as_object = true)
    {
        $handler = $this->loadHandler("read");
        $ret = $handler->getObjects($criteria, $id_as_key, $as_object);
        return $ret;
    }
    
    /**
     * get all objects matching a condition
     * 
     * @param object    $criteria {@link CriteriaElement} to match
     * @param array        $fields     variables to fetch
     * @param bool        $asObject     flag indicating as object, otherwise as array
     * @param bool      $id_as_key use the ID as key for the array
     * @return array of objects/array {@link XoopsObject}
     */
    function &getAll($criteria = null, $fields = null, $asObject = true, $id_as_key = true)
    {
        $handler = $this->loadHandler("read");
        $ret = $handler->getAll($criteria, $fields, $asObject, $id_as_key);
        return $ret;
    }
    
    /**
     * Retrieve a list of objects data
     *
     * @param object $criteria {@link CriteriaElement} conditions to be met
     * @param int   $limit      Max number of objects to fetch
     * @param int   $start      Which record to start at
     *
     * @return array
     */
    function getList($criteria = null, $limit = 0, $start = 0) 
    {
        $handler = $this->loadHandler("read");
        $ret = $handler->getList($criteria, $limit, $start);
        return $ret;
    }

    /**
     * get IDs of objects matching a condition
     * 
     * @param     object    $criteria {@link CriteriaElement} to match
     * @return     array of object IDs
     */
    function &getIds($criteria = null)
    {
        $handler = $this->loadHandler("read");
        $ret = $handler->getIds($criteria);
        return $ret;
    }
    
    /**
     * get a limited list of objects matching a condition
     * 
     * {@link CriteriaCompo} 
     *
     * @param int       $limit      Max number of objects to fetch
     * @param int       $start      Which record to start at
     * @param object    $criteria     {@link CriteriaElement} to match
     * @param array        $fields         variables to fetch
     * @param bool        $asObject     flag indicating as object, otherwise as array
     * @return array of objects     {@link XoopsObject}
     */
    function &getByLimit($limit = 0, $start = 0, $criteria = null, $fields = null, $asObject=true)
    {
        $handler = $this->loadHandler("read");
        $ret = $handler->getByLimit($limit, $start, $criteria, $fields, $asObject);
        return $ret;
    }
    /**#@-*/
    
    /**#@+
     * Methods of stats handler {@link XoopsObjectStats}
     */
    /**
     * count objects matching a condition
     * 
     * @param   object  $criteria {@link CriteriaElement} to match
     * @return  int     count of objects
     */
    function getCount($criteria = null)
    {
        $handler = $this->loadHandler("stats");
        return $handler->getCount($criteria);
    }
    
    /**
     * Get counts of objects matching a condition
     * 
     * @param object    $criteria {@link CriteriaElement} to match
     * @return array of conunts
     */
    function getCounts($criteria = null)
    {
        $handler = $this->loadHandler("stats");
        return $handler->getCounts($criteria);
    }
    /**#@-*/
    
    /**#@+
     * Methods of joint handler {@link XoopsObjectJoint}
     */
    /**
     * get a list of objects matching a condition joint with another related object
     * 
     * @param     object    $criteria     {@link CriteriaElement} to match
     * @param     array    $fields     variables to fetch
     * @param     bool    $asObject     flag indicating as object, otherwise as array
     * @param     string    $field_link     field of linked object for JOIN
     * @param     string    $field_object   field of current object for JOIN
     * @return     array of objects {@link XoopsObject}
     */
    function &getByLink($criteria = null, $fields = null, $asObject = true, $field_link = null, $field_object = null)
    {
        $handler = $this->loadHandler("joint");
        $ret = $handler->getByLink($criteria, $fields, $asObject, $field_link, $field_object);
        return $ret;
    }

    /**
     * Count of objects matching a condition
     * 
     * @param   object $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function getCountByLink($criteria = null)
    {
        $handler = $this->loadHandler("joint");
        $ret = $handler->getCountByLink($criteria);
        return $ret;
    }
    
    /**
     * array of count of objects matching a condition of, groupby linked object keyname
     * 
     * @param   object $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function getCountsByLink($criteria = null)
    {
        $handler = $this->loadHandler("joint");
        $ret = $handler->getCountsByLink($criteria);
        return $ret;
    }
    
    /**
     * upate objects matching a condition against linked objects
     * 
     * @param   array   $data  array of key => value
     * @param   object  $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function updateByLink($data, $criteria = null)
    {
        $handler = $this->loadHandler("joint");
        $ret = $handler->updateByLink($data, $criteria);
        return $ret;
    }
    
    /**
     * Delete objects matching a condition against linked objects
     * 
     * @param   object  $criteria {@link CriteriaElement} to match
     * @return  int count of objects
     */
    function deleteByLink($criteria = null)
    {
        $handler = $this->loadHandler("joint");
        $ret = $handler->deleteByLink($criteria);
        return $ret;
    }
    /**#@-*/
    
    /**#@+
     * Methods of sync handler {@link XoopsObjectSync}
     */
    /**
     * Clean orphan objects against linked objects
     * 
     * @param     string    $table_link     table of linked object for JOIN
     * @param     string    $field_link     field of linked object for JOIN
     * @param     string    $field_object   field of current object for JOIN
     * @return     bool    true on success
     */
    function cleanOrphan($table_link = "", $field_link = "", $field_object = "")
    {
        $handler = $this->loadHandler("sync");
        $ret = $handler->cleanOrphan($table_link, $field_link, $field_object);
        return $ret;
    }
    
    /**
     * Synchronizing objects
     * 
     * @return     bool    true on success
     */
    function synchronization()
    {
        $this->cleanOrphan();
        return true;
    }
    /**#@-*/
}
?>