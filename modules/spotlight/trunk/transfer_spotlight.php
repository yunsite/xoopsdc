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
 * @version        $Id: transfer_spotlight.php 1 2010-8-31 ezsky$
 */

include_once '../../mainfile.php';
global $xoopsUser;

// parameter
$ac = isset($spotlight_data['action']) ? $spotlight_data['action'] : '';
$ac = isset($_REQUEST['action']) ? $_REQUEST['action'] : $ac;
$page_id = isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : '';
$sp_id = !empty($_REQUEST['sp_id']) ? $_REQUEST['sp_id'] : '';
$spotlight_data = isset($spotlight_data) ? $spotlight_data : '';
$spotlight_data = isset($_REQUEST['spotlight_data']) ? $_REQUEST['spotlight_data'] : $spotlight_data;

// handler
$sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
$page_handler =& xoops_getmodulehandler('page', 'spotlight');

// tpl
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once XOOPS_ROOT_PATH . "/class/template.php";
	$xoopsTpl = new XoopsTpl();
}
if( isset($xoopsModule) && file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar("dirname") . "/template/" . $xoopsModule->getVar("dirname") . "_transfer_spotlight.html") ) {
    $template = $xoopsModule->getVar("dirname") . "_transfer_spotlight.html";
} else {
    $template = "spotlight_transfer_spotlight.html";
}
//$template_main = $template;

$xoopsOption["template_main"] = $template;
include_once XOOPS_ROOT_PATH . "/header.php";

// page count
$mid = isset($spotlight_data['mid']) ? $spotlight_data['mid'] : '';
$id = isset($spotlight_data['id']) ? $spotlight_data['id'] : '';
$sp_id = !empty($sp_id) ? $sp_id : current($sp_handler->getIds());

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('mid', $mid));
$criteria->add(new Criteria('id', $id)); 
$criteria->add(new Criteria('sp_id', $sp_id)); 
$count = $page_handler->getCount($criteria);
if($count) $xoopsTpl->assign('count', $count);

switch ($ac) {    
case 'delete':
    //delete page
    $module = isset($_REQUEST['module']) ? $_REQUEST['module'] : '';
    $file = isset($_REQUEST['file']) ? $_REQUEST['file'] : '';
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
    $page_handler->DeletePage($page_id);
    $url = XOOPS_URL . '/modules/' . $spotlight_data['module'] . '/' . $spotlight_data['file'] . '.php?id=' . $spotlight_data['id'];
    redirect_header($url, 3, _MA_SPOTLIGHT_DELETESUCCESS);
    break;

case 'order':
    //save order
    $page_order = isset($_REQUEST['page_order']) ? $_REQUEST['page_order'] : '';
    if(!empty($page_order)){
        include_once XOOPS_ROOT_PATH . "/modules/spotlight/include/functions.php";
        $ac_order = SpotlightContentOrder($page_order, 'page', 'page_order');
        $url = XOOPS_URL . '/modules/' . $spotlight_data['module'] . '/' . $spotlight_data['file'] . '.php?id=' . $spotlight_data['id'];
        redirect_header($url, 3, _MA_SPOTLIGHT_UPDATE_SUCCESSFUL);
    } 
    break;

case 'save':
    //save page
    $page["page_id"]      = $page_id;
    $page["sp_id"]        = $sp_id;
    $page["page_title"]   = @$_POST['page_title'];
    $page["page_desc"]    = @$_POST['page_desc'];
    $page["page_link"]    = @$_POST['page_link'];
    $page["page_image"]   = @$_POST['page_image'];
    $page["page_status"]  = @$_POST['page_status'];
    $page["page_order"]   = @$_POST['page_order'];
    $page["published"]    = @$_POST['published'];
    $page["datetime"]     = @$_POST['datetime'];
    $page["mid"]          = @$_POST['mid'];
    $page["id"]           = @$_POST['id'];
    $page["datetime"]     = !empty($_POST['datetime']) ? $_POST['datetime'] : time();
    
    // published
    $published["date"]    = isset($_POST["published"]["date"]) ? strtotime($_POST["published"]["date"]) : 0 ;
    $page["published"]    = !empty($published["date"]) ? $published["date"] + $_POST["published"]["time"] : time();
    
    //image 
    include_once XOOPS_ROOT_PATH . "/modules/spotlight/include/functions.php";
    $xoopsModuleConfig = spotlight_load_config();
    $upload_patch = spotlight_mkdirs( XOOPS_ROOT_PATH . $xoopsModuleConfig['spotlight_images'] );

    $sp_obj = $sp_handler->get($sp_id);
    $component = $sp_obj->getVar('component_name');
    include_once XOOPS_ROOT_PATH . "/modules/spotlight/components/{$component}/config.php";
    if(!isset($config['image_size'])) $config['image_size'] = '550|280';
    if(!isset($config['thumbs_size'])) $config['thumbs_size'] = '90|56';
    $image_wh = explode('|', $config['image_size']);
    $thumb_wh = explode('|', $config['thumbs_size']);
        
    if(!empty($_POST["xoops_upload_file"])){
        include_once XOOPS_ROOT_PATH."/class/uploader.php";
        $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
        $uploader = new XoopsMediaUploader($upload_patch, $allowed_mimetypes, $xoopsModuleConfig['upload_size'], 1200, 1200);
        if ($uploader->fetchMedia('page_image')) {
            $uploader->setPrefix('page_');
            if (!$uploader->upload()) {
                echo $uploader->getErrors();
            } else {
                spotlight_setImageThumb($upload_patch, $uploader->getSavedFileName(), $upload_patch, 'image_'.$uploader->getSavedFileName(), array($image_wh[0], $image_wh[1]));
                spotlight_cutphoto($upload_patch . $uploader->getSavedFileName(), $upload_patch . 'thumb_'.$uploader->getSavedFileName(), $thumb_wh[0], $thumb_wh[1]);
                $page["page_image"] = $uploader->getSavedFileName();
            }
        }
    }
    
    if(empty($_FILES['page_image']['size'])) {
        if(!empty($spotlight_data["page_image"])) {
            fopen($spotlight_data["page_original_image"], 'r+');
            if (copy($spotlight_data["page_original_image"], $upload_patch.$spotlight_data["page_image"])) {
                $upload_name = "page_".$spotlight_data["page_image"];
                rename($upload_patch.$spotlight_data["page_image"], $upload_patch.$upload_name);
                spotlight_setImageThumb($upload_patch, $upload_name, $upload_patch, 'image_'.$upload_name, array($image_wh[0], $image_wh[1]));
                spotlight_cutphoto($upload_patch . $upload_name, $upload_patch . 'thumb_'.$upload_name, $thumb_wh[0], $thumb_wh[1]);
                $page["page_image"] = $upload_name;
           }
        }
    }
    
    if(empty($count) && !empty($mid) && !empty($id)) $page_handler->InsertPage($page);
    $spotlight_data['count'] = false;
    $url = XOOPS_URL . '/modules/' . $spotlight_data['module'] . '/' . $spotlight_data['file'] . '.php?id=' . $spotlight_data['id'];
    redirect_header($url, 3, _MA_SPOTLIGHT_UPDATE_SUCCESSFUL);
    unset($spotlight_data);
    break;

case 'add':
    // new page
    $action = XOOPS_URL . "/modules/".$spotlight_data['module']."/thansfer_spotlight.php";
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $form = new XoopsThemeForm(_MA_SPOTLIGHT_KEEP_PUSHING_FOCUS, 'form', $action, 'post', true);
    $form->setExtra("enctype=\"multipart/form-data\"");
    
    $sp_select = new XoopsFormSelect(_MA_SPOTLIGHT_ADD_PAGE_THEIR_SLIDE, 'sp_id', $sp_id);
    $sp_select->addOptionArray($sp_handler->getList());
    $form->addElement($sp_select);
    $form->addElement(new XoopsFormText(_MA_SPOTLIGHT_MANAGEMENT_TITLE, 'page_title', 40, 255, @$spotlight_data['page_title']), true);
    $form->addElement( new XoopsFormTextArea(_MA_SPOTLIGHT_ADD_PAGE_SUMMARY,'page_desc', @$spotlight_data['page_desc'], 5, 60));
    $form->addElement(new XoopsFormText(_MA_SPOTLIGHT_ADD_PAGE_LINK, 'page_link', 60, 255, @$spotlight_data['page_link']), true);
    $page_image = new XoopsFormElementTray(_MA_SPOTLIGHT_ADD_PAGE_IMEGES);

    if( @$spotlight_data['page_image'] ){
        $form->addElement(new XoopsFormHidden('sub_image', @$spotlight_data['page_image']));
        $page_image->addElement(new XoopsFormLabel('', '<img src="'.@$spotlight_data['page_image_thumb'].'"><br><br>'));   
        $display = _MA_SPOTLIGHT_THE_NEW_IMSGE_EILL_REPLACE_THE_EXIETING;
    }else{
        $display = '';
    }
    $page_image->addElement(new XoopsFormFile('','page_image',1024*1024*2));
    $page_image->addElement(new XoopsFormLabel('',_MA_SPOTLIGHT_ADD_PAGE_ALLOW_UPLOAD_TYPEjpeg,pjpeg,gif,png));
    $page_image->addElement(new XoopsFormLabel('', $display));  
    $form->addElement($page_image);
    $form->addElement(new XoopsFormDateTime(_MA_SPOTLIGHT_MANAGEMENT_RELEASE_TIME,"published",15, @$spotlight_data['published']));
    $form->addElement(new XoopsFormHidden('datetime', time()));
    $form->addElement(new XoopsFormText(_MA_SPOTLIGHT_MANAGEMENT_SORT, 'page_order', 4, 4, @$spotlight_data['page_order']));
    
    $form->addElement(new XoopsFormHidden('page_status', 1));
    $form->addElement(new XoopsFormHidden('page_id', @$spotlight_data['page_id']));
    $form->addElement(new XoopsFormHidden('mid', @$spotlight_data['mid']));
    $form->addElement(new XoopsFormHidden('id', @$spotlight_data['id']));
    $form->addElement(new XoopsFormHidden('action', 'save'));
    if($spotlight_data) {
          foreach($spotlight_data as $k=>$v) {
              $form->addElement(new XoopsFormHidden("spotlight_data[{$k}]", $v));
          }
    }
    
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $form->assign($xoopsTpl);
    break;
}

// show page
$spotlights = $sp_handler->getList();

$sp_name = !empty($sp_id) ? $spotlights[$sp_id] : current($spotlights);

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('sp_id', $sp_id));
$criteria->setSort('page_order');
$criteria->setOrder('ASC');
$pages = $page_handler->getAll($criteria, array('sp_id','page_title','published', 'page_order', 'page_status'), false, false);

if(!empty($pages)) {
    foreach($pages as $k=>$v){
        $pages[$k]['published'] = formatTimestamp($v['published'],'Y/m/d');
        $pages[$k]['page_status'] = empty($v['page_status']) ? '<img src="'.XOOPS_URL.'/modules/spotlight/images/delete.png" title="未发布">' : '<img src="'.XOOPS_URL.'/modules/spotlight/images/accept.png" title="发布">';
        if(empty($v['page_order'])) $figures[$k]['page_order'] = '99';
    }
}

$xoopsTpl->assign('spotlights', $spotlights);
$xoopsTpl->assign('pages', $pages);
$xoopsTpl->assign('sp_name', $sp_name);
$xoopsTpl->assign('sp_id', $sp_id);

//display action
$xoopsTpl->assign('ac', $ac);
$xoopsTpl->assign('spotlight_data', @$spotlight_data);

include XOOPS_ROOT_PATH . "/footer.php";


?>
