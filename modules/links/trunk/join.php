<?php
include "header.php";

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'join';
$cat_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : '';
$cat_handler =& xoops_getmodulehandler('category', 'links');
$link_handler =& xoops_getmodulehandler('links', 'links');

$xoopsOption['template_main'] = 'links_join.html';
include XOOPS_ROOT_PATH.'/header.php';

switch ($op) {
    default:
    case 'join':
    include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
    $title = _MD_LINKS_APPLYJOIN;
    $form = new XoopsThemeForm($title, 'form', 'join.php', 'post', true);
    $form->setExtra("enctype=\"multipart/form-data\"");
    $form->addElement(new XoopsFormText(_MD_LINKS_SITETITLE, 'link_title', 60, 255, ''), true);
    $form->addElement(new XoopsFormText(_MD_LINKS_SITEURL, 'link_url', 100, 255, 'http://'), true);
    $logo_image = new XoopsFormElementTray(_MD_LINKS_LIKLOGO);
    $logo_image->addElement(new XoopsFormFile('','link_image',1024*1024*2));
    $logo_image->addElement(new XoopsFormLabel('', _MD_LINKS_LIKLOGOFORMAT)); 	
    $form->addElement($logo_image);
    $form->addElement(new XoopsFormText(_MD_LINKS_CONTACT, 'link_contact', 60, 255, ''), true);      
    $categories = new XoopsFormSelect(_MD_LINKS_SELECTCAT, 'cat_id',$cat_id);
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_order');
    $criteria->setOrder('ASC');
    $categories->addOptionArray($cat_handler->getList($criteria));
    $form->addElement($categories, true);
    $form->addElement(new XoopsFormHidden('op', 'save'));
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $form->assign($xoopsTpl);
    break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        $link_obj =& $link_handler->create();           
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
                }
            }
        }
        $link_obj->setVar('link_status', 0);
        if ($link_handler->insert($link_obj)) {
            redirect_header('index.php', 3, _MD_LINKS_SAVEDSUCCESSTIP);
        }else{
            redirect_header('join.php', 3, _MD_LINKS_AVTIVEERROR);
        }
    break;
}
include 'footer.php';
?>
