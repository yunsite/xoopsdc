<?php

/**
 * XOOPS Block management
 * 
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package     smarty xoopsplugin
 * @since       2.3.3 ezsky hack
 * @author      ezsky <ezskyyoung@gmail.com> 
 * @version     $Id: function.block.php $
 * @package     smarty xoops plugin
 *  Examples:
 *  <{freeBlock id=1}>                   displays just the block content 
 */


function smarty_function_freeBlock($params, &$smarty)
{
    if (!isset($params['id'])) return;


    $block_id = intval($params['id']);
    
    static $block_objs;

    $blockObjs = $smarty->get_template_vars('ezblocks');     // ezsky hack
    $blockObj = $blockObjs[$block_id] ;
    if ( empty ( $blockObj ) ) {
        return false;
    }  
    $xoopsLogger =& XoopsLogger::instance();
    $template =& $GLOBALS['xoopsTpl'];

    $bcachetime = intval($blockObj->getVar('bcachetime'));
    if (empty($bcachetime)) {
        $template->caching = 0;
    } else {
        $template->caching = 2;
        $template->cache_lifetime = $bcachetime;
    }

    $template->setCompileId($blockObj->getVar('dirname', 'n'));
    $tplName = ($tplName = $blockObj->getVar('template')) ? "db:{$tplName}" : "db:system_block_dummy.html";
    $cacheid = 'blk_' . $block_id;

    if (!$bcachetime || !$template->is_cached($tplName, $cacheid)) {
        $xoopsLogger->addBlock($blockObj->getVar('name'));
        if (!($bresult = $blockObj->buildBlock())) {
            return;
        }
       
        $template->assign('block', $bresult);
        $template->display( $tplName, $cacheid );
       
    } else {
        $xoopsLogger->addBlock($blockObj->getVar('name'), true, $bcachetime);
        
        $template->display( $tplName, $cacheid );
        
    }
    
    $template->setCompileId($blockObj->getVar('dirname', 'n'));
}

?>
