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
 * @version        $Id: show.php 1 2010-8-31 ezsky$
 */

if (!defined('XOOPS_ROOT_PATH')) { exit(); }

function defaultComponentsShow($options)
{
    include XOOPS_ROOT_PATH."/modules/spotlight/components/default/config.php";
    
    $sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
    $page_handler =& xoops_getmodulehandler('page', 'spotlight');
    
    // spotlight object
    $sp_obj = $sp_handler->get($options[0]);

    if(!is_object($sp_obj) || empty($options[0])) {
    
        trigger_error("spotlight is not object ", E_USER_WARNING);
        
    } else {
        
        //spotlight name
        $block['sp_name'] = $sp_obj->getVar('sp_name');

        // page list
        $criteria = new CriteriaCompo();
        $criteria -> add(new Criteria('sp_id', $options[0]));
        $criteria -> setLimit($config['limit']);
        $criteria -> setSort($config['sort']);
        $criteria -> setOrder('ASC');
        $pages = $page_handler->getAll($criteria,array('page_id', 'page_title', 'page_link', 'page_image', 'page_desc'),false);
        foreach ($pages as $k=>$v) {
            $block['news'][$k] = $v;
        }
        
        // component name
        $block['component'] = $sp_obj->getVar('component_name');

        return $block;
    }
}

?>
