<?php

if (!defined("XOOPS_ROOT_PATH")) exit();

function mkdirs($dir, $mode = 0777, $recursive = true) {
  if( is_null($dir) || $dir === "" ){
    return $dir;
  }
  if( is_dir($dir) || $dir === "/" ){
    return $dir;
  }
  if( mkdirs(dirname($dir), $mode, $recursive) ){
    return mkdir($dir, $mode);
  }
  return $dir;
}

?>
