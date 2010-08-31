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

function flash5ComponentsShow($options)
{
global $xoopsModuleConfig;
    include_once XOOPS_ROOT_PATH."/modules/spotlight/components/flash5/config.php";
    
    $sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
    $page_handler =& xoops_getmodulehandler('page', 'spotlight');
 
    
    // spotlight object
    $sp_obj = $sp_handler->get($options[0]);

    if(!is_object($sp_obj) || empty($options[0])) {
    
        trigger_error("spotlight is not object ", E_USER_WARNING);
        
    } else {
        //spotlight name
        $block['sp_name'] = $sp_obj->getVar('sp_name');
    }

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('sp_id', $options[0]));
    $criteria -> setLimit(5);
    $criteria -> setSort('page_order');
    $criteria -> setOrder('ASC');
    $pages = $page_handler->getAll($criteria,array('page_id', 'page_title', 'page_link', 'page_image', 'page_desc'),false);
    foreach ($pages as $k=>$v){
    
      	$block['title'][] = $v['page_title'];
      	$block['url'][] = $v['page_link'];
      	$block['images'][] = XOOPS_UPLOAD_URL.'/spotlight/'.'thumb_'.$v['page_image']; 
    }

    $block['catid'] = $options[0];
    $block['catname'] = $block['sp_name'];
    $block['type'] = 3;
    $block['title'] = implode('|',$block['title']);
    $block['url'] = implode('|',$block['url']);
    $block['images'] = implode('|',$block['images']);
    $block['component'] = $sp_obj->getVar('component_name');
 // xoops_result($block);
    return $block;

}

?>
