<?php
/**
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
 * @version     $Id: page_configsite.php 1529 2008-05-01 08:14:55Z phppp $
**/

$xoopsOption['checkadmin'] = true;
$xoopsOption['hascommon'] = true;
require_once './include/common.inc.php';
if ( !defined('XOOPS_INSTALL') ) { die('XOOPS Installation wizard die'); }

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $config_handler = xoops_gethandler('config');
    if ( array_key_exists('conf_ids', $_REQUEST) ) {
        foreach ($_REQUEST['conf_ids'] as $key => $conf_id) {
            $config =& $config_handler->getConfig( $conf_id );
            $new_value = $_REQUEST[ $config->getVar('conf_name')] ;
            $config->setConfValueForInput($new_value);
            $config_handler->insertConfig($config);
        }
    }
    $wizard->redirectToPage( '+1' );
}

$pageHasForm = true;
$pageHasHelp = true;

if ( !@include_once "../modules/system/language/{$wizard->language}/admin.php" ) {
    include_once "../modules/system/language/english/admin.php";
}

if ( !@include_once "../modules/system/language/{$wizard->language}/admin/preferences.php" ) {
    include_once "../modules/system/language/english/admin/preferences.php";
}

$config_handler = xoops_gethandler('config');
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('conf_modid', 0));

$criteria2 = new CriteriaCompo();
foreach ($wizard->configs['conf_names'] as $conf_name) {
    $criteria2->add(new Criteria('conf_name', $conf_name), 'OR');
}
$criteria->add($criteria2);
$criteria->setSort('conf_catid ASC, conf_order ASC');
$configs = $config_handler->getConfigs($criteria);

include './include/createconfigform.php';
$wizard->form = createConfigform($configs);
$content = $wizard->CreateForm();
include './include/install_tpl.php';
?>