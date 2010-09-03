<?php
/**
 * Transfer handler for XOOPS
 *
 * This is intended to handle content spotlight between modules 
 * 
 * @author          Magic.Shao <magic.shao@gmail.com>
 * @since           2.00
 * @version         $Id$
 * @package         Frameworks
 * @subpackage      transfer
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
    
        global $spotlight_data;
        
        if(!is_array($data) || empty($data)) {
            trigger_error('"$date" does not exist ', E_USER_WARNING);
            return false;
        }
        
        // The key for table "spotlight_page" fileds
        foreach ($data as $k=>$v) {
            if(isset($data[$k])) {
                $spotlight_data[$k] = $v;
            }
        }

        include_once XOOPS_ROOT_PATH . "/modules/" . $GLOBALS["xoopsModule"]->getVar("dirname") . "/thansfer_spotlight.php";

        exit();
    }
}
?>
