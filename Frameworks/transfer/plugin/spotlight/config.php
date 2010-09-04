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
 * @version        $Id: config.php 1 2010-9-4 ezsky$
 */

$item_name = strtoupper(basename(dirname(__FILE__)));
return $config = array(
        "title"        =>    CONSTANT("_MD_TRANSFER_{$item_name}"),
        "desc"        =>    CONSTANT("_MD_TRANSFER_{$item_name}_DESC"),
        "level"        =>    11,    /* 0 - hidden (For direct call only); >0 - display (available for selection) */
        "url"        =>    XOOPS_URL . "/modules/spotlight/action.transfer.php",
    );
?>
