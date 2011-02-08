<?php
 /**
 * Spotlight
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
 * @version        $Id: spotlight.php 1 2010-8-31 ezsky$
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SpotlightSpotlight extends XoopsObject
{
    function __construct() {
        $this->initVar('sp_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('sp_name', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('sp_desc', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('component_name', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('component_option', XOBJ_DTYPE_TXTBOX,"");
    }
    
    function SpotlightSpotlight(){
        $this->__construct();
    }
    
    function getForm($action = false) {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
       
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php"; 
               
	      $title = $this->isNew() ? _AM_SPOTLIGHT_ADD_PAGE : _AM_SPOTLIGHT_EDIT_PAGE;
	 
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra("enctype=\"multipart/form-data\"");
        $form->addElement(new XoopsFormText(_AM_SPOTLIGHT_SLIDE_NAME, 'sp_name', 60, 255, $this->getVar('sp_name', 'e')), true);
        $form->addElement( new XoopsFormTextArea(_AM_SPOTLIGHT_EXPLAIN,'sp_desc', $this->getVar('sp_desc','e'), 5, 80));
        
        //component
        $component_name = $this->getVar('component_name','e');
        if(empty($component_name)) $component_name = 'default';

	      include_once XOOPS_ROOT_PATH."/modules/spotlight/components/{$component_name}/config.php";

        //include component logo
        $components_list =XoopsLists::getDirListAsArray(dirname(dirname(__FILE__)) . '/components');
        
        foreach ($components_list as $key=>$val) {
            include_once XOOPS_ROOT_PATH."/modules/spotlight/components/{$val}/config.php";
            $_name = isset($config['name']) ? $config['name'] : $val;
            $component_names[$val] = $_name;
            unset($_name);
            unset($config['name']);
        }
        
        $component = new XoopsFormElementTray(_AM_SPOTLIGHT_COMPONENTS);
        $component_select = new XoopsFormSelect('', 'component_name', $component_name);
        $component_select->addOptionArray($component_names);
        $component->addElement($component_select);
        $component->addElement(new XoopsFormLabel('','<div id="component_logo">'));
        
        if ( isset($config['logo']) && file_exists(dirname(dirname(__FILE__))."/components/{$component_name}/{$config['logo']}") ) {
            $img_patch = XOOPS_URL . "/modules/spotlight/components/{$component_name}/{$config['logo']}";
            $component->addElement(new XoopsFormLabel('',"<img src='{$img_patch}'>"));
        } else {
            $component->addElement(new XoopsFormLabel('',_AM_SPOTLIGHT_NO));
        }
        
        $component->addElement(new XoopsFormLabel('','</div>'));
        $form->addElement($component);

        $form->addElement(new XoopsFormHidden('sp_id', $this->getVar('sp_id')));
        $form->addElement(new XoopsFormHidden('ac', 'insert'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

      	return $form;
    }
}

class SpotlightSpotlightHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) {
            parent::__construct($db, 'sp_spotlight', 'SpotlightSpotlight', 'sp_id', 'sp_name');
    }
    
    /**
     * Load the Spotlight Component
     *
     * @param string $component
     */
    function loadComponent($component, $config) {
    	$instance = '';
    	
    	if (!empty($component)) {
            $class = 'SpotlightComponent' . ucfirst($component);
            if (!class_exists($class)) {
                include_once dirname(dirname(__FILE__)) . '/components/' . $component . '/show.php';
            }
            
            if (class_exists($class)) {
                if (call_user_func(array($class , 'validate'))) {
                    $instance = new $class();
                    $instance->foldername = $component;
                    $instance->config = $config;
                    $instance->component_path = dirname(dirname(__FILE__)) .'/components/'. $instance->foldername;
                    $instance->component_url  = XOOPS_URL . '/modules/spotlight/components/'.$instance->foldername;
                }
            }
        }
        
        return $instance;
        
    }
    

}

?>
