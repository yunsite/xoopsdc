<?php

if (!defined("XOOPS_ROOT_PATH")) exit();

function ResourcesContentOrder($ObjOrder, $ObjectName, $FieldName){
    if ( isset($ObjOrder) || is_array($ObjOrder) ){
        $handler =& xoops_getmodulehandler($ObjectName, 'resources');
        foreach ($ObjOrder as $id=>$order){
    	    	$obj = $handler->get($id);
    	    	
        		$obj->setVar($FieldName, $order);
        		if($handler->insert($obj)) unset($obj);    	    	
        }     
        return true;
  	}
  	return false;
}

function Resourcesmkdirs($dir, $mode = 0777, $recursive = true) {
  if( is_null($dir) || $dir === "" ){
    return $dir;
  }
  if( is_dir($dir) || $dir === "/" ){
    return $dir;
  }
  if( Resourcesmkdirs(dirname($dir), $mode, $recursive) ){
    return mkdir($dir, $mode);
  }
  return $dir;
}

function setImageThumb($imagePath, $imageName, $thumbPath, $thumbName, $thumbWH = null ){
	if ( null==$thumbWH || !is_array($thumbWH) ) {
		$thumbWH = array("0"=> 120,"1"=> 120);
	}
	$src_file = $imagePath . $imageName;
	$new_file = $thumbPath . $thumbName;
	
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

	if (in_array($type, $supported_types) )
	{
		switch ($type)
		{
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

function cutphoto($o_photo,$d_photo,$width,$height){

    $temp_img = imagecreatefromjpeg($o_photo);
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
function item_setcookie($name, $string = '', $expire = 0)
{
    if (is_array($string)) {
        $value = array();
        foreach ($string as $key => $val) {
            $value[] = $key . "|" . $val;
        }
        $string = implode(",", $value);
    }
    $expire = empty($expire) ? 3600 * 24 * 30 : intval($expire);
    setcookie($name, $string, time() + $expire, '/');
}

function item_getcookie($name, $isArray = false)
{
    $value = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    if ($isArray) {
        $_value = ($value) ? explode(",", $value) : array();
        $value = array();
        foreach ($_value as $string) {
            $key = substr($string, 0, strpos($string, "|"));
            $val = substr($string, (strpos($string, "|") + 1));
            $value[$key] = $val;
        }
        unset($_value);
    }
    return $value;
}
function item_getIP($asString = false)
{
    return mod_getIP($asString);
}
function mod_getIP($asString = false)
{
    // Gets the proxy ip sent by the user
    $proxy_ip     = '';
    if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $proxy_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (!empty($_SERVER["HTTP_X_FORWARDED"])) {
        $proxy_ip = $_SERVER["HTTP_X_FORWARDED"];
    } else if (!empty($_SERVER["HTTP_FORWARDED_FOR"])) {
        $proxy_ip = $_SERVER["HTTP_FORWARDED_FOR"];
    } else if (!empty($_SERVER["HTTP_FORWARDED"])) {
        $proxy_ip = $_SERVER["HTTP_FORWARDED"];
    } else if (!empty($_SERVER["HTTP_VIA"])) {
        $proxy_ip = $_SERVER["HTTP_VIA"];
    } else if (!empty($_SERVER["HTTP_X_COMING_FROM"])) {
        $proxy_ip = $_SERVER["HTTP_X_COMING_FROM"];
    } else if (!empty($_SERVER["HTTP_COMING_FROM"])) {
        $proxy_ip = $_SERVER["HTTP_COMING_FROM"];
    }

    if (!empty($proxy_ip) &&
        $is_ip = ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}', $proxy_ip, $regs) &&
        count($regs) > 0
  	) {
      	$the_IP = $regs[0];
  	}else{
      	$the_IP = $_SERVER['REMOTE_ADDR'];	      	
  	}
    
  	$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
  	
  	return $the_IP;
}
?>
