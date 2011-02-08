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
 * @version        $Id: blocks.php 1 2010-8-31 ezsky$
 */

include_once dirname(dirname(__FILE__)).'/include/functions.php';

function spotlight_spotlight_show ($options)
{

    //xoops_loadlanguage('admin', 'spotlight');
    $sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');

    $block = '';

    $sp_id = $options[0];
    $sp_obj = $sp_handler->get($sp_id);

    $component = $sp_obj->getVar('component_name');

    // temple solution , will be get form spotlight object
    include_once dirname(dirname(__FILE__)) . "/components/" . $component . "/config.php";
    $instance = $sp_handler->loadComponent($component, $config);
    $instance->sp_id = $sp_id;
    $block = $instance->show();

    return $block;
}

function spotlight_spotlight_edit ($options) {
	
    if( file_exists(XOOPS_ROOT_PATH."/class/xoopsform/blockform.php") ) {
        include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
    } else {
        include_once dirname(dirname(__FILE__)) . "/class/blockform.php";
    }
    
  	$form = new XoopsBlockForm("","","");
  	$sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
  	$spotlights = $sp_handler->getList();
  	if(empty($spotlights)){
        $form->addElement(new XoopsFormLabel('', _MB_SPOTLIGHT_DO_NOT_ADD_GROUP_INFORMATION.'<a href="'.XOOPS_URL.'/modules/spotlight/admin/admin.spotlight.php">'._MB_SPOTLIGHT_HERE.'</a>'._MB_SPOTLIGHT_BE_ADDED)); 	
    }else{
      	$sp_select = new XoopsFormSelect(_MB_SPOTLIGHT_GROUP_NAME, 'options[0]', $options[0]);
        $sp_select->addOptionArray($spotlights);
        $form->addElement($sp_select);   
    }
    
    return $form->render();
}
?>
