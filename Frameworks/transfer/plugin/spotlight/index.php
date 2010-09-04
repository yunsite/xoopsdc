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
 * @version        $Id: index.php 1 2010-9-4 ezsky$
 */

class transfer_spotlight extends Transfer
{
    function transfer_spotlight()
    {
        $this->Transfer("spotlight");
    }
    
    function do_transfer(&$data)
    {
        eval(parent::do_transfer());
    
        include XOOPS_ROOT_PATH . "/header.php";
        
        xoops_loadLanguage('admin','spotlight');
        $page_handler =& xoops_getmodulehandler('page', 'spotlight');
        $page_obj =& $page_handler->create();
        $var_arr = array('page_title'=>$data["title"],
                         'page_desc'=>$data["summary"],
                         'page_link'=>$data["url"], 
                         'id'=>$data["id"]
                        );
                  
        $page_obj->assignVars($var_arr);
        $form = $page_obj->getForm($this->config["url"]);
        $form->display();
        
        include XOOPS_ROOT_PATH . "/footer.php";
        exit();
    }
}
?>
