<?php
 /**
 * Links
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright      The XOOPS Co.Ltd. http://www.xoops.com.cn
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package        links
 * @since          1.0.0
 * @author         Mengjue Shao <magic.shao@gmail.com>
 * @author         Susheng Yang <ezskyyoung@gmail.com>    
 * @version        $Id: blocks.php 1 2010-1-22 ezsky$
 */

function links_block_show($options){
    include_once XOOPS_ROOT_PATH.'/modules/links/include/functions.php';
    $cat_handler  =& xoops_getmodulehandler('category', 'links');
    $link_handler =& xoops_getmodulehandler('links', 'links');
    $module_handler =& xoops_gethandler('module');
    $config_handler =& xoops_gethandler('config');
    $module = $module_handler->getByDirname('links');
    $xoopsModuleConfig = $config_handler->getConfigList($module->getVar('mid'));
    
    $cat_name = '';
    $criteria = new CriteriaCompo();
    if(!empty($options[0])){
        $criteria->add(new Criteria('cat_id', $options[0]), 'AND');
        $cat_obj = $cat_handler->get($options[0]);
        $cat_name = $cat_obj->getVar('cat_name');
    } 
    $criteria->add(new Criteria('link_status', 1));
    $criteria->setSort($options[1]);
    if($options[1] == 'link_order'){
        $criteria->setOrder('ASC');
    }else{
        $criteria->setOrder('DESC');
    }
    $criteria->setLimit($options[2]);
    $links = $link_handler->getAll($criteria, array('link_id', 'link_title', 'cat_id', 'link_url', 'link_image', 'link_dir'), false, false);
    foreach($links as $k=>$v){            
        $links[$k]['link_title'] = cut_str($v['link_title'], $options[3], 0, 'utf8');
        if(!empty($xoopsModuleConfig['logo'])){
            $links[$k]['link_image'] = XOOPS_URL.$xoopsModuleConfig['logo_dir'].$v['link_image'];
        }else{
            $links[$k]['link_image'] = $v['link_dir'];
        }
    }
    $display['cat_name'] = $cat_name;
    $display['display_cat'] = $options[4];
    $display['display'] = $options[5];
    $display['links'] = $links;
    $display['display_css'] = XOOPS_URL.'/modules/links/templates/style.css';
    $block = array('display' => $display);
    return $block;
}

function links_block_edit($options){
    include_once XOOPS_ROOT_PATH."/modules/links/include/xoopsformloader.php";
    $form = new XoopsBlockForm("","","");    
    $categories = new XoopsFormSelect(_MB_LINKS_SHOWCAT, 'options[0]',$options[0]);
    $categories->addOption(0, _MB_LINKS_ALL);
    $cat_handler = xoops_getmodulehandler('category', 'links');
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_order');
    $criteria->setOrder('ASC');
    $category = $cat_handler->getList($criteria);
    foreach ($cat_handler->getList($criteria) as $k=>$v){
        $categories->addOption($k, $v);
    }
    $form->addElement($categories, true);    
    $sort = new XoopsFormSelect(_MB_LINKS_SORTWAY, 'options[1]',$options[1]);
    $sort->addOption('published', _MB_LINKS_PUBLISHTIME);
    $sort->addOption('datetime', _MB_LINKS_UPDATETIME);
    $sort->addOption('link_order', _MB_LINKS_DEFAULT);
    $form->addElement($sort, true);
    $form->addElement(new XoopsFormText(_MB_LINKS_SHOWHOWLIK,"options[2]",5,2,$options[2]), true);
    $form->addElement(new XoopsFormText(_MB_LINKS_LIKTITLEMAX,"options[3]",5,2,$options[3]), true);
    $form->addElement(new XoopsFormRadioYN(_MB_LINKS_SHOWCATTITLE, 'options[4]', $options[4]), true);
    $display = new XoopsFormSelect(_MB_LINKS_BYSHOW, 'options[5]',$options[5]);
    $display->addOption('1', _MB_LINKS_LOGOHOR);
    $display->addOption('2', _MB_LINKS_LOGOVER);
    $display->addOption('3', _MB_LINKS_TITLEHOR);
    $display->addOption('4', _MB_LINKS_TITLEVER);
    $form->addElement($display, true);
    return $form->render();
}
?>
