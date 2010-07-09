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
 * @version        $Id: redirect.php 1 2010-1-22 ezsky$
 */

include "header.php";
$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';

if (empty($url)) {
    redirect_header($_SERVER['HTTP_REFERER'], 5, implode("<br />", $GLOBALS["xoopsSecurity"]->getErrors()) );
}else{
    header("location: $url");
}

include 'footer.php';
?>
