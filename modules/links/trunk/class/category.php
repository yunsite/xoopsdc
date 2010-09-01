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
 * @version        $Id: category.php 1 2010-1-22 ezsky$
 */

include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class LinksCategory extends XoopsObject 
{
    function __construct() 
    {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cat_name', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('cat_desc', XOBJ_DTYPE_TXTBOX, "");
        $this->initVar('cat_order', XOBJ_DTYPE_INT,0);
        $this->initVar('cat_status', XOBJ_DTYPE_INT,0);
        $this->initVar('cat_image', XOBJ_DTYPE_TXTBOX, "");
    }
    
    function catForm($action = false)
    {      
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }  
        include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
        $format = empty($format) ? "e" : $format;
        $title = $this->isNew() ? _AM_LINKS_ADDCAT : _AM_LINKS_UPDATECAT;
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->addElement(new XoopsFormText(_AM_LINKS_CATNAME, 'cat_name', 60, 255, $this->getVar('cat_name', $format)), true);
        $form->addElement(new XoopsFormHidden('cat_id', $this->getVar('cat_id')));
        $form->addElement(new XoopsFormHidden('ac', 'insert'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        
        return $form;
    }
}

class LinksCategoryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, 'links_category', 'LinksCategory', 'cat_id', 'cat_name');
    }
}
?>
