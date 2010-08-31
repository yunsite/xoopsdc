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
 * @version        $Id: page.php 1 2010-8-31 ezsky$
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class SpotlightPage extends XoopsObject
{
    function __construct() {
        $this->initVar('page_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('sp_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('page_title', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('page_desc', XOBJ_DTYPE_TXTBOX,"");        
        $this->initVar('page_link', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('page_image', XOBJ_DTYPE_TXTBOX,"");
        $this->initVar('published', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('datetime', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('page_order', XOBJ_DTYPE_INT, 99, false);
        $this->initVar('page_status', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('mid', XOBJ_DTYPE_INT, 0, false);   
        $this->initVar('id', XOBJ_DTYPE_INT, 0, false);               
    }
    
    function SpotlightPage(){
        $this->__construct();
    }
    
    function getForm($action = false) {
        
        global $xoopsModuleConfig;
        
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
            
  		$sp_id = isset($_REQUEST['sp_id']) ? $_REQUEST['sp_id'] : '';
  		$sp_handler =& xoops_getmodulehandler('spotlight', 'spotlight');
  		$format = empty($format) ? "e" : $format;

  		$title = $this->isNew() ? _AM_SPOTLIGHT_ADD_PAGE : _AM_SPOTLIGHT_EDIT_PAGE;

  		$form = new XoopsThemeForm($title, 'form', $action, 'post', true);
  		$form->setExtra("enctype=\"multipart/form-data\"");

  		$sp_select = new XoopsFormSelect(_AM_SPOTLIGHT_ADD_PAGE_THEIR_SLIDE, 'sp_id', $sp_id);
  		$sp_select->addOptionArray($sp_handler->getList());
  		$form->addElement($sp_select);
  		$form->addElement(new XoopsFormText(_AM_SPOTLIGHT_MANAGEMENT_TITLE, 'page_title', 80, 255, $this->getVar('page_title', $format)), true);
  		$form->addElement( new XoopsFormTextArea(_AM_SPOTLIGHT_ADD_PAGE_SUMMARY,'page_desc', $this->getVar('page_desc'), 5, 80));
  		$form->addElement(new XoopsFormText(_AM_SPOTLIGHT_ADD_PAGE_LINK, 'page_link', 60, 255, $this->getVar('page_link')), true);
  		$form->addElement(new XoopsFormRadioYN(_AM_SPOTLIGHT_ADD_PAGE_INDICATE, 'page_status', $this->getVar('page_status'), $yes =_AM_SPOTLIGHT_ADD_PAGE_SHOW, $no = _AM_SPOTLIGHT_ADD_PAGE_NOT_SHOW));
  		$page_image = new XoopsFormElementTray(_AM_SPOTLIGHT_ADD_PAGE_IMEGES);

  		if( $this->getVar('page_image') ){
  		    $page_image->addElement(new XoopsFormLabel('', '<img src="'.XOOPS_URL.$xoopsModuleConfig['spotlight_images'].'image_'.$this->getVar('page_image').'"><br><br>'));   
  		    $display = _AM_SPOTLIGHT_THE_NEW_IMSGE_EILL_REPLACE_THE_EXIETING;
  		}else{
  		    $display = '';
  		}  

  		$page_image->addElement(new XoopsFormFile('','page_image',1024*1024*2));
  		$page_image->addElement(new XoopsFormLabel('',_AM_SPOTLIGHT_ADD_PAGE_ALLOW_UPLOAD_TYPE));
  		$page_image->addElement(new XoopsFormLabel('', $display));  
  		$form->addElement($page_image);
  		$form->addElement(new XoopsFormDateTime(_AM_SPOTLIGHT_MANAGEMENT_RELEASE_TIME,"published",15,$this->getVar('published', $format)));
  		$form->addElement(new XoopsFormHidden('datetime', time()));
  		$form->addElement(new XoopsFormHidden('page_id', $this->getVar('page_id')));
  		$form->addElement(new XoopsFormHidden('ac', 'insert'));
  		$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

  		return $form;
    }
}

class SpotlightPageHandler extends XoopsPersistableObjectHandler
{

    function __construct(&$db){
        parent::__construct($db, 'sp_page', "SpotlightPage", "page_id", "page_title");
    }
    
    function InsertPage($page)
    {
        $page_handler =& xoops_getmodulehandler('page', 'spotlight');
        
        if (isset($page['page_id'])) {
            $page_obj =& $page_handler->get($page['page_id']);
        }else {
            $page_obj =& $page_handler->create();            
        }
        
        foreach(array_keys($page_obj->vars) as $key) {
            if(isset($page[$key])) {
                $page_obj->setVar($key, $page[$key]);
            }
        }
        
        $page_id =  $page_handler->insert($page_obj);
        
        return $page_id;
    }
    
    function DeletePage($page_id)
    {
        global $xoopsModuleConfig;
        $page_handler =& xoops_getmodulehandler('page', 'spotlight');
        
        $page_obj =& $page_handler->get($page_id);
        if(!is_object($page_obj)) return false;
        if($page_obj->getVar('page_image')){
            unlink(XOOPS_ROOT_PATH . $xoopsModuleConfig['spotlight_images'].$page_obj->getVar('page_image'));
            unlink(XOOPS_ROOT_PATH . $xoopsModuleConfig['spotlight_images'].'image_'.$page_obj->getVar('page_image'));
            unlink(XOOPS_ROOT_PATH . $xoopsModuleConfig['spotlight_images'].'thumb_'.$page_obj->getVar('page_image'));
        }
        
        if($page_handler->delete($page_obj , true)) return true;        
        return false;
    }
}

?>
