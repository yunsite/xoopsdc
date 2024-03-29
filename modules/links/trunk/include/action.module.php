<?php
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

function xoops_module_install_links(&$module)
{
    $data_category_file = XOOPS_ROOT_PATH."/modules/links/sql/mysql.links.sql";
    $GLOBALS["xoopsDB"]->queryF("SET NAMES utf8");
    if(!$GLOBALS["xoopsDB"]->queryFromFile($data_category_file)){
        $module->setErrors("Pre-set data were not installed");
        return true;
    }
    
    $sql  = "INSERT INTO " . $GLOBALS["xoopsDB"]->prefix("links_link") . " (`link_id`, `cat_id`, `link_title`, `link_url`, `link_desc`, `link_order`, `link_status`, `link_image`, `link_dir`, `published`, `datetime`) VALUES";
    $sql .= "(1, 1, '众锐普斯', 'http://www.xoops.com.cn/', 'XOOPS商业公司', 1, 1, '', '" . XOOPS_URL . "/modules/links/images/xoops.com.png', 1251831615, 1251831615),";
    $sql .= "(2, 1, 'XOOPS中文社区', 'http://xoops.org.cn/', 'Xoops中文支持站', 2, 1, '', '" . XOOPS_URL . "/modules/links/images/xoops.org.png', 1251831616, 1251831616);";
    $GLOBALS["xoopsDB"]->queryF($sql);
    return true ;
}
function xoops_module_update_links(&$module, $prev_version = null)
{
    global $xoopsDB;
    $query = "ALTER TABLE `".$xoopsDB->prefix("links_link")."`  ADD `link_contact` varchar (255) NOT NULL ;";
    $xoopsDB->queryF($query);
    return true;
}
?>
