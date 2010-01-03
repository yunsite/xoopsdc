<?php

if (!defined("XOOPS_ROOT_PATH")) exit();

/**
  * @ $ObjOredr is array for key is id and value is order 
  * @ $ObjectName is you class name
  * @ $$FieldName is your data table field names
  */
function ContentOrder($ObjOrder, $ObjectName, $FieldName){
    if ( isset($ObjOrder) || is_array($ObjOrder) ){
        $handler =& xoops_getmodulehandler($ObjectName, 'links');
        foreach ($ObjOrder as $id=>$order){
    	    	$obj = $handler->get($id);
        		$obj->setVar($FieldName, $order);
        		if($handler->insert($obj)) unset($obj);    	    	
        }     
        return true;
  	}
  	return false;
}

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
  

function joindelete($join = false){
    return $join;
}

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8'){   
     if($code == 'UTF-8'){   
         $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";   
         preg_match_all($pa, $string, $t_string);   
    
         if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";   
         return join('', array_slice($t_string[0], $start, $sublen));   
     }else{   
         $start = $start*2;   
         $sublen = $sublen*2;   
         $strlen = strlen($string);   
         $tmpstr = '';   
    
         for($i=0; $i< $strlen; $i++){   
             if($i>=$start && $i< ($start+$sublen)){   
                 if(ord(substr($string, $i, 1))>129){   
                     $tmpstr.= substr($string, $i, 2);   
                 }else{   
                     $tmpstr.= substr($string, $i, 1);   
                 }   
             }   
             if(ord(substr($string, $i, 1))>129) $i++;   
         }   
         if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";   
         return $tmpstr;   
     }   
 }    

?>