<?php
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

function xoops_module_install_portfolio(&$module)
{
    return true ;
}
function xoops_module_update_portfolio(&$module, $prev_version = null)
{
    global $xoopsDB;                                     
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_service")." CHANGE `service_name` `service_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_service")." CHANGE `service_menu_name` `service_menu_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_service")." CHANGE `service_image` `service_image` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_service")." CHANGE `service_tpl` `service_tpl` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_service")." CHANGE `service_desc` `service_desc` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ' '";
    $xoopsDB->query($sql);
    
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_case")." CHANGE `case_title` `case_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_case")." CHANGE `case_menu_title` `case_menu_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_case")." CHANGE `case_summary` `case_summary` text CHARACTER SET utf8 COLLATE utf8_general_ci  NULL DEFAULT NULL";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_case")." CHANGE `case_image` `case_image` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_case")." CHANGE `case_tpl` `case_tpl` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default'";
    $xoopsDB->query($sql);
    $sql = "ALTER TABLE ".$xoopsDB->prefix("portfolio_case")." CHANGE `case_description` `case_description`  text CHARACTER SET utf8 COLLATE utf8_general_ci  NULL DEFAULT NULL";
    $xoopsDB->query($sql);    
             
    return true;
}
?>
