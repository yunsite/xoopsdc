<?php
/**
 * Empty module foo
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         foo
 * @since           2.3.0
 * @author          Susheng Yang <ezskyyoung@gmail.com>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: index.php  $
 */


include 'header.php';
$xoopsOption['template_main'] = 'foo_index.html';
include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('lang_welcome', _FOO_MA_WELCOME);
include 'footer.php';
   
?>