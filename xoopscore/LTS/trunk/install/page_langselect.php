<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * Installer language selection page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      DuGris <dugris@frxoops.org>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id: page_langselect.php 2822 2009-02-20 08:50:48Z phppp $
**/

require_once './include/common.inc.php';
if ( !defined('XOOPS_INSTALL') ) { die('XOOPS Installation wizard die'); }

setcookie( 'xo_install_lang', 'english', null, null, null );
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['lang']) ) {
    $lang = $_REQUEST['lang'];
    setcookie( 'xo_install_lang', $lang, null, null, null );

    $wizard->redirectToPage( '+1' );
    exit();
}

$_SESSION['settings'] = array();
setcookie( 'xo_install_user', '', null, null, null );

$pageHasForm = true;
$title = LANGUAGE_SELECTION;
$content = '<select name="lang" size="10" style="min-width: 10em">';

$languages = getDirList( "./language/" );
foreach ( $languages as $lang ) {
    $sel = ( $lang == $wizard->language ) ? ' selected="selected"' : '';
    $content .= "<option value=\"{$lang}\"{$sel}>{$lang}</option>\n";
}
$content .= "</select>";

include './include/install_tpl.php';
?>