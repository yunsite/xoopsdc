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
 * @version        $Id: functions.php 1 2010-8-31 ezsky$
 */

if (!defined("XOOPS_ROOT_PATH")) exit();

/**
  * @ $ObjOredr is array for key is id and value is order 
  * @ $ObjectName is you class name
  * @ $$FieldName is your data table field names
  */
  
function SpotlightContentOrder($ObjOrder, $ObjectName, $FieldName){
    if ( isset($ObjOrder) || is_array($ObjOrder) ){
        $handler =& xoops_getmodulehandler($ObjectName, 'spotlight');
        foreach ($ObjOrder as $id=>$order){
    	    	$obj = $handler->get($id);
        		$obj->setVar($FieldName, $order);
        		if($handler->insert($obj)) unset($obj);    	    	
        }     
  	}
  	return true;
}

function spotlight_load_config()
{
    static $moduleConfig;
    
    if (isset($moduleConfig["spotlight"])) {
        return $moduleConfig["spotlight"];
    }
    
    if (isset($GLOBALS["xoopsModule"]) && is_object($GLOBALS["xoopsModule"]) && $GLOBALS["xoopsModule"]->getVar("dirname", "n") == "chinanews") {
        if (!empty($GLOBALS["xoopsModuleConfig"])) {
            $moduleConfig["spotlight"] =& $GLOBALS["xoopsModuleConfig"];
        } else {
            return null;
        }
    } else {
        $module_handler =& xoops_gethandler('module');
        $module = $module_handler->getByDirname("spotlight");
        
        $config_handler =& xoops_gethandler('config');
        $criteria = new CriteriaCompo(new Criteria('conf_modid', $module->getVar('mid')));
        $configs =& $config_handler->getConfigs($criteria);
        foreach(array_keys($configs) as $i) {
            $moduleConfig["spotlight"][$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
        }
        unset($configs);
    }

    return $moduleConfig["spotlight"];
}

function spotlight_mkdirs($dir, $mode = 0777, $recursive = true) {
    if( is_null($dir) || $dir === "" ){
        return $dir;
    }
    if( is_dir($dir) || $dir === "/" ){
        return $dir;
    }
    if( spotlight_mkdirs(dirname($dir), $mode, $recursive) ){
        return mkdir($dir, $mode);
    }
    return $dir;
}

function spotlight_setImageThumb($imagePath, $imageName, $thumbPath, $thumbName, $thumbWH = null ){

    if ( null==$thumbWH || !is_array($thumbWH) ) {
		$thumbWH = array("0"=> 120,"1"=> 120);
	}

	$src_file = $imagePath."/".$imageName;
	$new_file = $thumbPath."/".$thumbName;

	if ( !filesize($src_file) || !is_readable($src_file)) {
		return false;
	}

	$imginfo = @getimagesize($src_file);

	if( $imginfo[0] <= $thumbWH[0] && $imginfo[1] <= $thumbWH[1]) {
		@copy($src_file,$new_file);
		return true;
	}

	$newWidth = (int)(min($imginfo[0],$thumbWH[0]));
	$newHeight = (int)($imginfo[1] * $newWidth / $imginfo[0]);

	if ( $newHeight > $thumbWH[1] ) {
		$newHeight = (int)(min($imginfo[1],$thumbWH[1]));
		$newWidth  = (int)($imginfo[0] * $newHeight / $imginfo[1]);
	}
	
	$type = $imginfo[2];
	$supported_types = array();
	if (!extension_loaded("gd")) return false;
	if (function_exists("imagegif")) $supported_types[] = 1;
	if (function_exists("imagejpeg"))$supported_types[] = 2;
	if (function_exists("imagepng")) $supported_types[] = 3;
	
    $imageCreateFunction = (function_exists("imagecreatetruecolor"))? "imagecreatetruecolor" : "imagecreate";

	if (in_array($type, $supported_types) ){
		switch ($type){
			case 1 :
				if (!function_exists("imagecreatefromgif")) return false;
				$im = imagecreatefromgif($src_file);
				$new_im = imagecreate($newWidth, $newHeight);
				if(function_exists("ImageCopyResampled"))
				ImageCopyResampled($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight,$imginfo[0],$imginfo[1]); 
				else
				ImageCopyResized($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight, $imginfo[0],$imginfo[1]);
				imagegif($new_im, $new_file);
				imagedestroy($im);
				imagedestroy($new_im);
				break;
			case 2 :
				$im = imagecreatefromjpeg($src_file);
				$new_im = $imageCreateFunction($newWidth, $newHeight);
				if(function_exists("ImageCopyResampled"))
				ImageCopyResampled($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight,$imginfo[0],$imginfo[1]); 
				else
				ImageCopyResized($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight, $imginfo[0],$imginfo[1]);
				imagejpeg($new_im, $new_file,90);
				imagedestroy($im);
				imagedestroy($new_im);
				break;
			case 3 :
				$im = imagecreatefrompng($src_file);
				$new_im = $imageCreateFunction($newWidth, $newHeight);
				if(function_exists("ImageCopyResampled"))
				ImageCopyResampled($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight,$imginfo[0],$imginfo[1]); 
				else
				ImageCopyResized($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight, $imginfo[0],$imginfo[1]);
				imagepng($new_im, $new_file);
				imagedestroy($im);
				imagedestroy($new_im);
				break;
		}
		return true;
	}
	return false;
    }


function spotlight_cutphoto($o_photo,$d_photo,$width,$height){


    if ( $o_photo ) {
        $ImageType = @getimagesize($o_photo);
        
        switch ( @$ImageType[2] ) {
        case 1:
            $temp_img = imagecreatefromgif($o_photo);
            break;
    
        case 2:
            $temp_img = imagecreatefromjpeg($o_photo);
            break;
    
        case 3:
            $temp_img = imagecreatefrompng($o_photo);
            break;
        }
    }

    $o_width  = imagesx($temp_img);
    $o_height = imagesy($temp_img);
    

    if($width>$o_width || $height>$o_height){       
    
        $newwidth=$o_width;
        $newheight=$o_height;
        
        if($o_width>$width){
                $newwidth=$width;
                $newheight=$o_height*$width/$o_width;
        }
        
        if($newheight>$height){
                $newwidth=$newwidth*$height/$newheight;
                $newheight=$height;
        }
        
        
        $new_img = imagecreatetruecolor($newwidth, $newheight); 
        imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $newwidth, $newheight, $o_width, $o_height); 
        imagejpeg($new_img , $d_photo, 90);                
        imagedestroy($new_img);
    
    
    }else{
    
        if($o_height*$width/$o_width>$height){
            $newwidth=$width;
            $newheight=$o_height*$width/$o_width;
            $x=0;
            $y=($newheight-$height)/2;
        }else{
            $newwidth=$o_width*$height/$o_height;
            $newheight=$height;
            $x=($newwidth-$width)/2;
            $y=0;
        }


        $new_img = imagecreatetruecolor($newwidth, $newheight); 
        imagecopyresampled($new_img, $temp_img, 0, 0, 0, 0, $newwidth, $newheight, $o_width, $o_height); 
        imagejpeg($new_img , $d_photo, 90);                
        imagedestroy($new_img);
        
        $temp_img = imagecreatefromjpeg($d_photo);
        $o_width  = imagesx($temp_img);
        $o_height = imagesy($temp_img);


        $new_imgx = imagecreatetruecolor($width,$height);
        imagecopyresampled($new_imgx,$temp_img,0,0,$x,$y,$width,$height,$width,$height);
        imagejpeg($new_imgx , $d_photo, 90);
        imagedestroy($new_imgx);
    }
}

?>
