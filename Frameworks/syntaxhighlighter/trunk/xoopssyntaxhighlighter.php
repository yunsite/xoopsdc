<?php

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * 
 * 
 * @author	Susheng Yang	<ezskyyoung@gmail.com>
 * 
 * @package		Syntaxhighlighter 
 */
 class XoopsSyntaxhighlighter 
{

/**
* Reference for Theme
*/
var $xoTheme;

    function __construct($config = null){
    global  $xoTheme;

    if (empty($config)) {
        $config = array('bash','cpp','csharp','css','delphi','diff','groovy','java','jsript',
                        'php','plain','python','ruby','scala','sql','vb','xml');
    }
                    
    $brush = array(
        'bash'=>'shBrushBash',
        'cpp'=>'shBrushCpp',
        'csharp'=>'shBrushCSharp',
        'css'=>'shBrushCss',
        'delphi'=>'shBrushDelphi',
        'diff'=>'shBrushDiff',
        'groovy'=>'shBrushGroovy',
        'java'=>'shBrushJava',
        'jsript'=>'shBrushJScript',
        'php'=>'shBrushPhp',
        'plain'=>'shBrushPlain',
        'python'=>'shBrushPython',
        'ruby'=>'shBrushRuby',
        'scala'=>'shBrushScala',
        'sql'=>'shBrushSql',
        'vb'=>'shBrushVb',
        'xml'=>'shBrushXml'
    );
    
    
    $this->xoTheme =& $GLOBALS['xoTheme'];
    
    $xoTheme->addScript( XOOPS_URL . '/Frameworks/syntaxhighlighter/src/shCore.js', null, '' ); 
    
    foreach($config as $k=>$v){
        $xoTheme->addScript( XOOPS_URL . "/Frameworks/syntaxhighlighter/scripts/{$brush[$v]}.js", null, '' );
    }
    
    
    
    $xoTheme->addStylesheet( XOOPS_URL . '/Frameworks/syntaxhighlighter/styles/shCore.css'); 
    $xoTheme->addStylesheet( XOOPS_URL . '/Frameworks/syntaxhighlighter/styles/shThemeDefault.css');
    
    $xoTheme->addScript( null, array( 'type' => 'application/x-javascript' ), 'SyntaxHighlighter.all()' ); 
    }
    function XoopsSyntaxhighlighter()
    {
        $this->__construct();
    }
        /**
     * Access the only instance of this class
     *
     * @return    object
     *
     * @static
     * @staticvar   object
     */
    function &getInstance($config)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new XoopsSyntaxhighlighter($config);
        }
        return $instance;
    }
}
?>