<?php

$GLOBALS["artdirname"] = basename(dirname(dirname(__FILE__)));

// include customized variables
if ( is_object($GLOBALS["xoopsModule"]) && $GLOBALS["artdirname"] == $GLOBALS["xoopsModule"]->getVar("dirname", "n") ) {

}

?>
