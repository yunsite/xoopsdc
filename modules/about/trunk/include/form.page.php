<?php

if (!defined("XOOPS_ROOT_PATH")) exit();
include_once "../include/functions.render.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$pageType = isset($_REQUEST['type']) ? $_REQUEST['type'] : $page_obj->getVar('page_type');
$format = empty($format) ? "e" : $format;

$menu_status = $page_obj->isNew() ? 1 : $page_obj->getVar('page_menu_status');
$list_status = $page_obj->isNew() ? 1 : $page_obj->getVar('page_status');
$page_blank = $page_obj->isNew() ? 0 : $page_obj->getVar('page_blank');
$title = $page_obj->isNew() ? _AM_ABOUT_PAGE_INSERT : _AM_ABOUT_EDIT;
$form = new XoopsThemeForm($title, 'form', "admin.page.php", 'post');

if($pageType == 1){
	$form->addElement(new XoopsFormText(_AM_ABOUT_PAGE_TITLE, 'page_title', 60, 255, $page_obj->getVar('page_title', $format)), true);
	$menu= new XoopsFormElementTray(_AM_ABOUT_PAGE_MENU_LIST);
	$menu->addElement(new XoopsFormRadioYN('', 'page_menu_status', $menu_status));
	$menu->addElement(new XoopsFormText(_AM_ABOUT_PAGE_MENU_TITLE.':', 'page_menu_title', 30, 255, $page_obj->getVar('page_menu_title', $format)));
	$menu->addElement(new XoopsFormLabel('', _AM_ABOUT_PAGE_LINK_MENU));
	$form->addElement($menu , true);
    $configs = array('editor'=>'fckeditor','width'=>'100%','height'=>'500px','value'=>$page_obj->getVar('page_text')); 
    $form->addElement(new XoopsFormEditor(_AM_ABOUT_PAGE_TEXT, 'page_text',$configs), true);
    // Template set
	$templates = about_getTemplateList("page");
	if (count($templates)>0) {
	    $template_select = new XoopsFormSelect(_AM_ABOUT_TEMPLATE_SELECT, "page_tpl", $page_obj->getVar("page_tpl"));
	    $template_select->addOptionArray($templates);
	    $form->addElement($template_select);
	}
}else{
	$form->addElement(new XoopsFormText(_AM_ABOUT_PAGE_MENU_TITLE.':', 'page_menu_title', 60, 255, $page_obj->getVar('page_menu_title', $format)));
	$form->addElement(new XoopsFormHidden('page_menu_status',$menu_status));
    $form->addElement(new XoopsFormText(_AM_ABOUT_PAGE_LINK_TEXT, 'page_text', 60, 255, $page_obj->isNew() ? 'http://'.$page_obj->getVar('page_text', $format) : $page_obj->getVar('page_text', $format) ), true);
}

$form->addElement(new XoopsFormRadioYN(_AM_ABOUT_PAGE_LINK_BLANK, 'page_blank', $page_blank));
$form->addElement(new XoopsFormRadioYN(_AM_ABOUT_PAGE_STATUS, 'page_status', $list_status, $yes = _AM_ABOUT_PAGE_SUB, $no = _AM_ABOUT_PAGE_DARFT));

$form->addElement(new XoopsFormHidden('id', $page_obj->getVar('page_id')));
$form->addElement(new XoopsFormHidden('page_type', $pageType));
$form->addElement(new XoopsFormHidden('op', 'save'));
$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
return $form;
?>
