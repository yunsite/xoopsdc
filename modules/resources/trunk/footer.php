<?php
if ( $xoopsModuleConfig["useruploads"] ) {
   $xoopsTpl->assign("useruploads",1);
}
$xoTheme->addStylesheet( 'modules/'.$xoopsModule->getVar("dirname","n").'/template/style.css' );
include_once XOOPS_ROOT_PATH."/footer.php";
?>
