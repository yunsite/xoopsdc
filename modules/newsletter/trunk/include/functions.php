<?php

if (!defined("XOOPS_ROOT_PATH")) exit();

function NewsletterContentOrder($ObjOrder, $ObjectName, $FieldName){
    if ( isset($ObjOrder) || is_array($ObjOrder) ){
        $handler =& xoops_getmodulehandler($ObjectName, 'newsletter');
        foreach ($ObjOrder as $id=>$order){
    	    	$obj = $handler->get($id);
    	    	
        		$obj->setVar($FieldName, $order);
        		if($handler->insert($obj)) unset($obj);    	    	
        }     
        return true;
  	}
  	return false;
}

function Newslettermkdirs($dir, $mode = 0777, $recursive = true) {
  if( is_null($dir) || $dir === "" ){
    return $dir;
  }
  if( is_dir($dir) || $dir === "/" ){
    return $dir;
  }
  if( Newslettermkdirs(dirname($dir), $mode, $recursive) ){
    return mkdir($dir, $mode);
  }
  return $dir;
}

function NewsletterCreateDir($dir){
    if ( !is_dir($dir) ) {
        if ( !@mkdir($dir, 0777, true) ){
            mkdirs($dir) ;
        } else {
            @chmod($dir, 0777);
        }
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
				/*
				$im = imagecreatefrompng($src_file);
				$new_im = $imageCreateFunction($newWidth, $newHeight);
				if(function_exists("ImageCopyResampled"))
				ImageCopyResampled($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight,$imginfo[0],$imginfo[1]); 
				else
				ImageCopyResized($new_im, $im, 0, 0, 0, 0, $newWidth, $newHeight, $imginfo[0],$imginfo[1]);
				imagepng($new_im, $new_file);
				imagedestroy($im);
				imagedestroy($new_im);
        */
        
        //magic.shao hack
        $im = imagecreatefrompng($src_file);
        imagesavealpha($im, true);
				$new_im = $imageCreateFunction($newWidth, $newHeight);
        imagealphablending($new_im,false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
        imagesavealpha($new_im,true);//这里很重要,意思是不要丢了$thumb图像的透明色;
        if(imagecopyresampled($new_im,$im,0,0,0,0,$newWidth,$newHeight,$imginfo[0],$imginfo[1])){
            imagepng($new_im, $new_file);
        }
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

?>
