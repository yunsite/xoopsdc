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
 * @version        $Id: action.links.php 1 2010-1-22 ezsky$
 */

include "header.php";
xoops_cp_header();

$ac = isset($_REQUEST['ac']) ? $_REQUEST['ac'] : 'display';
$link_id = isset($_REQUEST['link_id']) ? $_REQUEST['link_id'] : '';
$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'published';
$link_handler =& xoops_getmodulehandler('links', 'links');

switch ($ac) {       
    case 'insert':
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.links.php?sort='.$sort, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        if (!empty($link_id)) {
            $link_obj =& $link_handler->get($link_id);
            $link_image = $link_obj->getVar('link_image');
            $message = _AM_LINKS_UPDATEDSUCCESS;
        }else {
            $link_obj =& $link_handler->create();         
            $message = _AM_LINKS_SAVEDSUCCESS;   
        }
        foreach(array_keys($link_obj->vars) as $key) {
            if(isset($_POST[$key]) && $_POST[$key] != $link_obj->getVar($key)) {
                $link_obj->setVar($key, $_POST[$key]);
            }
        }
        if ( !empty($_POST["xoops_upload_file"][0]) ){
            include_once XOOPS_ROOT_PATH."/class/uploader.php";
            $link_dir = XOOPS_ROOT_PATH . $xoopsModuleConfig['logo_dir'];
            $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
            $maxfilesize = 500000;
            $maxfilewidth = 1200;
            $maxfileheight = 1200;
            $uploader = new XoopsMediaUploader($link_dir, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
            if ($uploader->fetchMedia('link_image')) {
            $uploader->setPrefix('link_');
                if (!$uploader->upload()) {
                    echo $uploader->getErrors();
                } else {
                    $link_obj->setVar('link_image', $uploader->getSavedFileName());
                    if(!empty($link_image)) unlink(str_replace("\\", "/", realpath($link_dir.$link_image)));
                }
            }
        }
        
        if ($link_handler->insert($link_obj)) {
            redirect_header('admin.links.php?sort='.$sort, 3, $message);
        }else{
            redirect_header('admin.links.php?sort='.$sort, 3, _AM_LINKS_ACTIVEERROR);
        }
    break;
    
    case 'delete':
        $link_obj =& $link_handler->get($link_id);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('admin.links.php?sort='.$sort, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            $link_image = $link_obj->getVar('link_image');
            $link_dir = XOOPS_ROOT_PATH . $xoopsModuleConfig['logo_dir'];
            if($link_handler->delete($link_obj)) {
                if(!empty($link_image)) unlink($link_dir.$link_image);
                redirect_header('admin.links.php?sort='.$sort, 3, _AM_LINKS_DELETESUCCESS);            
            }else{
                echo $link_obj->getHtmlErrors();
            }
        }else{
            xoops_confirm(array('ok' => 1, 'link_id' => $link_id, 'sort'=>$sort, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_LINKS_RESUREDELLIK,$link_obj->getVar('link_title')));
        }        
    break;
    
    default:
        redirect_header('admin.links.php?sort='.$sort);
    break;
}

include "footer.php";
?>
