<?php
 /**
 * Spotlight Component abstract class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright      The BEIJING XOOPS Co.Ltd. http://www.xoops.com.cn
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package        spotlight
 * @since          1.0.0
 * @author         Mengjue Shao <magic.shao@gmail.com>
 * @author         Susheng Yang <ezskyyoung@gmail.com>
 * @version        $Id: components.php 1 2010-8-31 ezsky$
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SpotlightComponent
{
	var $sp_id;
	var $config;
	var $page_hander;
	/**
     * Holding component folder name
     */
    var $foldername;
    
    var $template = 'template.html';
    
    var $component_path;
    var $component_url;
	
    function __construct() {
    	$this->page_hander =& xoops_getmodulehandler('page', 'spotlight');
        return true;
    }
    
    function component(){
        $this->__construct();
    }
    
	function validate() {}
    function flush() {}
/*
    function &getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }
*/    
    function show() {
    	$pages = $this->page_hander->getBySpotlight($this->sp_id, $this->config['limit'], $this->config['sort'], $this->config['order']);
    	return $pages;
    }

}

?>
