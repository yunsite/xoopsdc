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
 * Installer configuration check page
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
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id: page_modcheck.php 2824 2009-02-20 11:57:42Z phppp $
**/

require_once './include/common.inc.php';
if ( !defined('XOOPS_INSTALL') ) { die('XOOPS Installation wizard die'); }

$pageHasForm = false;
$diagsOK = false;

foreach ( $wizard->configs['extensions'] as $ext => $value) {
    if ( extension_loaded( $ext ) ) {
        if ( is_array($value[0] ) ) {
            $wizard->configs['extensions'][$ext][] = xoDiag( 1, implode( ',', $value[0] ) );
        } else {
            $wizard->configs['extensions'][$ext][] = xoDiag( 1, $value[0] );
        }
    } else {
        $wizard->configs['extensions'][$ext][] = xoDiag( 0, NONE );
    }
}
ob_start();
?>
<table class="diags">
<caption><?php echo REQUIREMENTS; ?></caption>
<thead><tr><th colspan='2'></th></tr></thead>
<tfoot><tr><td colspan='2'></td></tr></tfoot>
<tbody>
<tr>
    <th><?php echo SERVER_API; ?></th>
    <td><?php echo php_sapi_name(); ?><br /> <?php echo $_SERVER["SERVER_SOFTWARE"]; ?></td>
</tr>

<tr>
    <th><?php echo _PHP_VERSION; ?></th>
    <td><?php echo xoPhpVersion(); ?></td>
</tr>

<tr>
    <th><?php printf( PHP_EXTENSION, 'MySQL' ); ?></th>
    <td><?php echo xoDiag( function_exists( 'mysql_connect' ) ? 1 : -1, @mysql_get_client_info() ); ?></td>
</tr>

<tr>
    <th><?php printf( PHP_EXTENSION, 'Session' ); ?></th>
    <td><?php echo xoDiag( extension_loaded( 'session' ) ? 1 : -1 ); ?></td>
</tr>

<tr>
    <th><?php printf( PHP_EXTENSION, 'PCRE' ); ?></th>
    <td><?php echo xoDiag( extension_loaded( 'pcre' ) ? 1 : -1 ); ?></td>
</tr>

<tr>
    <th scope="row">file_uploads</th>
    <td><?php echo xoDiagBoolSetting( 'file_uploads', true ); ?></td>
</tr>
</tbody>
</table>

<table class="diags">
<caption><?php echo RECOMMENDED_EXTENSIONS; ?></caption>
<thead>
    <tr><th colspan="2"><div class='confirmMsg'><?php echo RECOMMENDED_EXTENSIONS_MSG; ?></div></th></tr>
</thead>
<tfoot><tr><td colspan='2'></td></tr></tfoot>
<tbody>
<?php
foreach ( $wizard->configs['extensions'] as $key => $value) {
    echo "<tr><th>" . $value[1] . "</th><td>" . $value[2] . "</td></tr>";
}
?>

</tbody>
</table>
<?php
$content = ob_get_contents();
ob_end_clean();

include './include/install_tpl.php';
?>