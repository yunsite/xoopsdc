<?php
/* 
* Smarty plugin 
* 
------------------------------------------------------------- 
* File: modifier.html_substr.php 
* Type: modifier 
* Name: html_substr 
* Version: 1.0 
* Date: June 19th, 2003 
* Purpose: Cut a string preserving any tag nesting and matching. 
* Install: Drop into the plugin directory. 
* Author: Original Javascript Code: Benjamin Lupu <lupufr@aol.com> 
* Translation to PHP & Smarty: Edward Dale <scompt@scompt.com> 
* http://www.phpinsider.com/smarty-forum/viewtopic.php?t=533
------------------------------------------------------------- 
*/
 
/*
* chinese optimization by IDIS
* http://web.idis.com.tw/blog/blog_article.php?ArticleID=34
*/ 

/*
* for xoops by susheng yang <ezskyyoung@gmail.com>
*/

function html_substr($string, $length){
    if( !empty( $string ) && $length>0 ) {

        $isText = true;   //是否為內文的判斷器
        $ret = '';    //最後輸出的字串
        $i = 0;     //內文字記數器 (判斷長度用)
    
        $currentChar = "";  //目前處理的字元
        $lastSpacePosition = -1;//最後設定輸出的位置
    
        $tagsArray = array(); //標籤陣列 , 堆疊設計想法
        $currentTag = "";  //目前處理中的標籤
    
        $noTagLength = mb_strlen( strip_tags( $string ),_CHARSET ); //沒有HTML標籤的字串長度

        if ($noTagLength<$length){
            return false; 
        }
        
        // 判斷所有字的迴圈
        for( $j=0; $j<mb_strlen($string,_CHARSET); $j++ ) {
        
            $currentChar = mb_substr( $string, $j, 1 ,_CHARSET);
            $ret .= $currentChar;
            
            // 如果是HTML標籤開頭
            if( $currentChar == "<") $isText = false;
            
            // 如果是內文
            if( $isText ) {
            
                // 如果遇到空白則表示暫定輸出到這
                if( $currentChar == " " ) { $lastSpacePosition = $j; }
                
                //內文長度記錄
                $i++;
            } else {
                $currentTag .= $currentChar;
            }
            
            // 如果是HTML標籤結尾
            if( $currentChar == ">" ) {
            $isText = true;
            
                // 判斷標籤是否要處理 , 是否有結尾
                if( ( mb_strpos( $currentTag, "<" ,0,_CHARSET) !== FALSE ) &&
                ( mb_strpos( $currentTag, "/>",0,_CHARSET ) === FALSE ) &&
                ( mb_strpos( $currentTag, "</",0,_CHARSET) === FALSE ) ) {
                
                    // 取出標籤名稱 (有無屬性的情況皆處理)
                    if( mb_strpos( $currentTag, " ",0,_CHARSET ) !== FALSE ) {
                        // 有屬性
                        $currentTag = mb_substr( $currentTag, 1, mb_strpos( $currentTag, " " ,0,_CHARSET) - 1 ,_CHARSET);
                    } else {
                        // 沒屬性
                        $currentTag = mb_substr( $currentTag, 1, -1 ,_CHARSET);
                    }
                    
                    // 加入標籤陣列
                    array_push( $tagsArray, $currentTag );
                
                } else if( mb_strpos( $currentTag, "</" ,0,_CHARSET) !== FALSE ) {
                    // 取出最後一個標籤(表示已結尾)
                    array_pop( $tagsArray );
                }
                
                //清除現在的標籤
                $currentTag = "";
            }
            
            // 判斷是否還要繼續抓字 (用內文長度判斷)
            if( $i >= $length) {
            break;
            }
        }
        
        // 取出要截短的HTML字串
        if( $length < $noTagLength ) {
            if( $lastSpacePosition != -1 ) {
                // 指定的結尾
                $ret = mb_substr( $string, 0, $lastSpacePosition ,_CHARSET );
            } else {
                // 預設的內文長度位置
                $ret = mb_substr( $string, 0 , $j ,_CHARSET );
            }
        }
        $ret .= '...';
            // 補上未結尾的標籤
            while( sizeof( $tagsArray ) != 0 ) {
                $aTag = array_pop( $tagsArray );
                $ret .= "</" . $aTag . ">";
            }
        
    } else {
        $ret = "";
    }

return $ret ;
}

/**
 * Function to set a cookie with module-specified name
 *
 * using customized serialization method
 */
function art_setcookie($name, $string = '', $expire = 0)
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

function art_getcookie($name, $isArray = false)
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

?>