<?php
/**
 * Transfer handler for XOOPS
 *
 * This is intended to handle content intercommunication between modules as well as components
 * There might need to be a more explicit name for the handle since it is always confusing
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           1.00
 * @version         $Id$
 * @package         Frameworks
 * @subpackage      transfer
 */
$item_name = strtoupper(basename(dirname(__FILE__)));
return $config = array(
        "title"        =>    CONSTANT("_MD_TRANSFER_{$item_name}"),
        "desc"        =>    CONSTANT("_MD_TRANSFER_{$item_name}_DESC"),
        "level"        =>    11,    /* 0 - hidden (For direct call only); >0 - display (available for selection) */
    );
?>
