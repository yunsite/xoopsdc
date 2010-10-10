<?php
/**
 * Empty module foo
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         foo
 * @since           2.3.0
 * @author          Susheng Yang <ezskyyoung@gmail.com>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: index.php  $
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class FooCategory extends XoopsObject
{
    function __construct() 
    {
        $this->initVar('_', XOBJ_DTYPE_INT, null, true);
        $this->initVar('_', XOBJ_DTYPE_TXTBOX);
        $this->initVar('_', XOBJ_DTYPE_TXTAREA);
        $this->initVar('_', XOBJ_DTYPE_INT);
    }
    
    function FooCategory()
    {
        $this->__construct();
    }
    
    
}

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class FooCategoryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, "_", "foocategory", "_", '_');
    }
}
?>