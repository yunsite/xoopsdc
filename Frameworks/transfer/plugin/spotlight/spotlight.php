<?php

if (!defined("XOOPS_ROOT_PATH")) {
    exit();
}

if (!require_once XOOPS_ROOT_PATH . "/Frameworks/transfer/transfer.php") return;

// Specify the addons to skip for the module
$GLOBALS["addons_skip_module"] = array();
// Maximum items to show on page
$GLOBALS["addons_limit_module"] = 5;

class ModuleTransferHandler extends TransferHandler
{
    function ModuleTransferHandler()
    {
        $this->TransferHandler();
    }
    
    /**
     * Get valid addon list
     * 
     * @param    array    $skip    Addons to skip
     * @param    boolean    $sort    To sort the list upon 'level'
     * return    array    $list
     */
    function getList($skip = array(), $sort = true)
    {
        $list = parent::getList($skip, $sort);
        return $list;
    }
    
    /** 
     * If need change config of an item
     * 1 parent::load_item
     * 2 $this->config
     * 3 $this->do_transfer
     */
    function do_transfer($item, &$data)
    {
        return parent::do_transfer($item, $data);
    }
    
    function InsertNews($items)
    {
        $page_handler =& xoops_getmodulehandler('page', 'spotlight');
        
        $sp_id =  $page_handler->InsertNews($page);
        
        return $sp_id;
    }
    
    function DeleteNews($page_id)
    {
        $page_handler =& xoops_getmodulehandler('page', 'spotlight');
        $page_handler->DeleteNews($page_id);
    }
}
?>
