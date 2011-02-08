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

include_once dirname(dirname(__FILE__)) . '/component.php';

class SpotlightComponentDefault extends SpotlightComponent
{
	function validate()
	    {
	        return true;
	    }
	    
	function show() {
		$pages = parent::show();
		
		$myts = MyTextSanitizer::getInstance();
        foreach ($pages as $k=>$v) {
            $rel['pages'][$k] = $v;
            $rel['pages'][$k]['images'] = XOOPS_UPLOAD_URL . '/spotlight/image_'.$v['page_image'];
            $rel['pages'][$k]['thumbs'] = XOOPS_UPLOAD_URL . '/spotlight/thumb_'.$v['page_image'];
            $page_desc = strip_tags($myts->undoHtmlSpecialChars(strip_tags($v['page_desc'])));
            //$rel['pages'][$k]['page_desc'] = xoops_substr($page_desc, '', $config['page_desc_substr']);
            //$rel['pages'][$k]['page_title'] = xoops_substr($v['page_title'], '', $config['page_title_substr']);
            //$rel['pages'][$k]['published'] = formatTimestamp($v['published'],$config['timeformat']);                                      
        }  
        
        // component name
        $rel['component'] = $this->foldername;
		$rel['tpl'] = $this->component_path . '/' . $this->template;
        return $rel;
	}	
}


?>
