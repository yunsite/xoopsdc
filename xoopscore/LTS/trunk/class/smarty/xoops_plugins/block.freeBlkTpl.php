<?php
/**
 * @package     smarty xoopsplugin
 * 
 * @author	    Hu Zhenghui <huzhengh@gmail.com>
 *  
 * how to use
 * The following code inserted in the template
 *  
 * @param module string module dirname
 * @param file  string block funciton file
 * @param show_func string show block function
 * @param options= string show block function's option
 * @param cachetime int  cachetime Unit for seconds 
 * @param user mix Generate cache solution

<{freeBlkTpl id=1}>
    <link rel="stylesheet" href="<{$xoops_url}>/modules/tag/templates/style.css" />
    <div class="tag-cloud" style="line-height: 150%; padding: 5px;">
    <{foreach item=tag from=$block.tags}>
    	<span class="tag-level-<{$tag.level}>" style="font-size: <{$tag.font}>%; display: inline; padding-right: 5px;">
    		<a href="<{$xoops_url}>/modules/<{$block.tag_dirname}>/view.tag.php<{$smarty.const.URL_DELIMITER}><{$tag.id}>/" title="<{$tag.term}>"><{$tag.term}></a>
    	</span>
    <{/foreach}>
    </div>
<{/freeBlkTpl}>
*/
function smarty_block_freeBlkTpl ($params, $content, &$smarty, &$repeat) {
    static $old_block;
    
    
    if (empty($content)) {
        
        $old_block = $smarty->get_template_vars('block');
        
            if (!isset($params['id'])) return;

            $block_id = intval($params['id']);
            
            static $block_objs;
        
            $blockObj = $GLOBALS['xoopsTpl']->_tpl_vars['ezblocks'][$block_id];     // ezsky hack
            if ( empty ( $blockObj ) ) {
                return false;
            }  
            $xoopsLogger =& XoopsLogger::instance();
            $template =& $GLOBALS['xoopsTpl'];
            xoops_load("cache");
            $cache_key = 'xoBlkTpl_'.md5(var_export($params, true));
            
            $bcachetime = intval($blockObj->getVar('bcachetime'));
        
            if (!$bcachetime || (!$bresult = XoopsCache::read($cache_key))) {
                $xoopsLogger->addBlock($blockObj->getVar('name'));
                if (!($bresult = $blockObj->buildBlock())) {
                    return;
                }
                if (isset($bcachetime)) {
                    XoopsCache::write($cache_key, $bresult, $bcachetime);
                }
               
            } else {
                $xoopsLogger->addBlock($blockObj->getVar('name'), true, $bcachetime); 
            }
            
            $old_block = $smarty->get_template_vars('block');
            $smarty->assign('block', $bresult);

    } else {
        echo $content;
        $smarty->assign('block', $old_block);
    }
}
?>