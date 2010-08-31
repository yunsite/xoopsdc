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
 * @version        $Id: action.page.php 1 2010-8-31 ezsky$
 */

include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : 'display';
$page_id = isset($_REQUEST['page_id']) ? $_REQUEST['page_id'] : '';
$sp_id = isset($_REQUEST['sp_id']) ? $_REQUEST['sp_id'] : '';
$page_handler =& xoops_getmodulehandler('page','spotlight');
$sp_handler =& xoops_getmodulehandler('spotlight','spotlight');
switch ($ac) {       
case 'insert':
    if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.page.php?sp_id='.$sp_id, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    
    if (isset($page_id)) {
        $page_obj =& $page_handler->get($page_id);
        $page_image = $page_obj->getVar('page_image');
        $message = _AM_SPOTLIGHT_UPDATE_SUCCESSFUL;
    }else {
        $page_obj =& $page_handler->create();
        $message = _AM_SPOTLIGHT_SAVE_STCCESSFULLY;   
    }
    
    foreach(array_keys($page_obj->vars) as $key) {
        if(isset($_POST[$key]) && $_POST[$key] != $page_obj->getVar($key)) {
            $page_obj->setVar($key, $_POST[$key]);
        }
    }
    
    $published["date"] = isset($_POST["published"]["date"]) ? strtotime($_POST["published"]["date"]) : 0 ;
    $page_obj->setVar('published', $published["date"] + $_POST["published"]["time"]);
    
    if(!empty($_POST["xoops_upload_file"])){
  
        include_once dirname(dirname(__FILE__)) . '/include/functions.php';
        include_once XOOPS_ROOT_PATH."/class/uploader.php";
        $upload_patch = spotlight_mkdirs( XOOPS_ROOT_PATH . $xoopsModuleConfig['spotlight_images'] );
        $sp_obj = $sp_handler->get($sp_id);
        $component = $sp_obj->getVar('component_name');
        include_once dirname(dirname(__FILE__)) . "/components/{$component}/config.php";
        if(!isset($config['image_size'])) $config['image_size'] = '550|280';
        if(!isset($config['thumbs_size'])) $config['thumbs_size'] = '90|56';
        $image_wh = explode('|', $config['image_size']);
        $thumb_wh = explode('|', $config['thumbs_size']);
    
        $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
        $uploader = new XoopsMediaUploader($upload_patch, $allowed_mimetypes, $xoopsModuleConfig['upload_size'], 1200, 1200);   
               
        if ($uploader->fetchMedia('page_image')) {
            $uploader->setPrefix('page_');
            if (!$uploader->upload()) {
                $error = $uploader->getErrors();
                redirect_header('admin.page.php?sp_id='.$sp_id, 3, _AM_SPOTLIGHT_IMEGES_TYPE_WRONG);
            } else {
                spotlight_setImageThumb($upload_patch, $uploader->getSavedFileName(), $upload_patch, 'image_'.$uploader->getSavedFileName(), array($image_wh[0], $image_wh[1]));
                spotlight_cutphoto($upload_patch . $uploader->getSavedFileName(), $upload_patch . 'thumb_'.$uploader->getSavedFileName(), $thumb_wh[0], $thumb_wh[1]);
                $page_obj->setVar('page_image', $uploader->getSavedFileName());
                if(!empty($page_image)){
                    unlink($upload_patch.$page_image);
                    unlink($upload_patch.'image_'.$page_image);
                    unlink($upload_patch.'thumb_'.$page_image);
                }
            }
        }
                       
    }
    
    
    if ($page_handler->insert($page_obj)) {
        redirect_header('admin.page.php?sp_id='.$sp_id, 3, $message);
    }else{
        redirect_header('admin.page.php?sp_id='.$sp_id, 3, _AM_SPOTLIGHT_WRONG_OPERATING);
    }
break;

case 'delete':
    $page_obj =& $page_handler->get($page_id);
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.page.php?sp_id='.$sp_id, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        if($page_handler->DeletePage($page_id)) {
            redirect_header('admin.page.php?sp_id='.$sp_id, 3, _AM_SPOTLIGHT_DELETED_SUCCESSFULLY);
        }else{
            echo _AM_SPOTLIGHT_ERONG_DELETE;
        }
    }else{
        xoops_confirm(array('ok' => 1, 'page_id'=>$page_id, 'sp_id' => $sp_id, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_SPOTLIGHT_OK_TO_DELETE_FOCUS,$page_obj->getVar('page_title')));
    }
    
break;

default:
    redirect_header('admin.page.php?sp_id='.$sp_id);
break;
}

include "footer.php";
?>
