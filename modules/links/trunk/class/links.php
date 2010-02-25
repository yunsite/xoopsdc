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
 * @version        $Id: links.php 1 2010-1-22 ezsky$
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class LinksLinks extends XoopsObject 
{
    function __construct() 
    {
        $this->initVar('link_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_id', XOBJ_DTYPE_INT);
        $this->initVar('link_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('link_url', XOBJ_DTYPE_TXTBOX);
        $this->initVar('link_desc', XOBJ_DTYPE_TXTBOX);
        $this->initVar('link_order', XOBJ_DTYPE_INT, null, false);
        $this->initVar('link_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('link_image', XOBJ_DTYPE_TXTBOX);
        $this->initVar('link_dir', XOBJ_DTYPE_TXTBOX);
        $this->initVar('published', XOBJ_DTYPE_INT, null, false);
        $this->initVar('datetime', XOBJ_DTYPE_INT, null, false);
        $this->initVar('link_contact', XOBJ_DTYPE_TXTBOX);
    }

    function linksForm($action = false){
        global $xoopsModuleConfig;
      	if ($action === false) {
              $action = $_SERVER['REQUEST_URI'];
          }
          include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
          $title = $this->isNew() ? _AM_LINKS_ADDLIK : _AM_LINKS_UPDATELIK;
          $link_status = $this->isNew() ? 1 : $this->getVar('link_status');
          $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
          $form->setExtra('enctype="multipart/form-data"');
          $cat_id = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
          if(empty($cat_id)) $cat_id = $this->getVar("cat_id");
          $categories = new XoopsFormSelect(_AM_LINKS_CATNAME, 'cat_id',$cat_id);
          $cat_handler = xoops_getmodulehandler('category', 'links');
          $criteria = new CriteriaCompo();
          $criteria->setSort('cat_order');
          $criteria->setOrder('ASC');
          $categories->addOptionArray($cat_handler->getList($criteria));
          $form->addElement($categories, true);
          $form->addElement(new XoopsFormText(_AM_LINKS_TITLE, 'link_title', 40, 50, $this->getVar('link_title')), true);
          if (!$this->isNew()) {
              $form->addElement(new XoopsFormText(_AM_LINKS_LIKADD, 'link_url', 50, 50, $this->getVar('link_url'), true));            
              $form->addElement(new XoopsFormHidden('datetime', time()));
          } else {
          	 $form->addElement(new XoopsFormText(_AM_LINKS_LIKADD, 'link_url', 50, 255, 'http://'), true);
          	 $form->addElement(new XoopsFormHidden('published', time()));
          	 $form->addElement(new XoopsFormHidden('datetime', time()));
          }
          $logo_image = new XoopsFormElementTray(_AM_LINKS_LIKLOGO);
          if(!empty($xoopsModuleConfig['logo'])){
              if( $this->getVar('link_title') ){
              $logo_image->addElement(new XoopsFormLabel('', '<img src="'.XOOPS_URL.$xoopsModuleConfig['logo_dir'].$this->getVar('link_image').'"><br><br>')); 	
              $display = _AM_LINKS_LOGOWARN;
              }else{
                  $display = '';
              }  
              $logo_image->addElement(new XoopsFormFile('','link_image',1024*1024*2));

          }else{
              $logo_image->addElement(new XoopsFormText('', 'link_dir', 70, 255, $this->getVar('link_dir')));
              $display = _AM_LINKS_LOGOTIPS.XOOPS_URL.'/uploads/logo/logo.jpg';
          }
 	        $logo_image->addElement(new XoopsFormLabel('', $display));
          $form->addElement($logo_image);
          $form->addElement(new XoopsFormText(_AM_LINKS_SORT, 'link_order', 4, 2, $this->getVar('link_order')));
          $form->addElement(new XoopsFormText(_AM_LINKS_CONTACT, 'link_contact', 60, 255, $this->getVar('link_contact')));      
          $form->addElement(new XoopsFormRadioYN(_AM_LINKS_SHOW, 'link_status', $link_status));
          $form->addElement(new XoopsFormHidden('link_id', $this->getVar('link_id')));
          $form->addElement(new XoopsFormHidden('ac', 'insert'));
          $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
          return $form;
    }
}

class LinksLinksHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, 'links_link', 'LinksLinks', 'link_id', 'link_title');
    }
}
?>
