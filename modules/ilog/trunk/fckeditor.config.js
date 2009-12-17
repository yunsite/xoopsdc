/**
 * FCKeditor adapter for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		4.00
 * @version		$Id: fckeditor.config.js 1319 2008-02-12 10:56:44Z phppp $
 * @package		xoopseditor
 */

FCKConfig.AutoDetectLanguage = false;
FCKConfig.ToolbarCanCollapse	= false ;
FCKConfig.ToolbarSets["ilog"] = [
	['Source','Preview','-','PasteText','PasteWord','-','RemoveFormat','-','Bold','Italic','Underline','StrikeThrough','-','OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','Anchor','-','TextColor','BGColor','-','About'],
	'/',
	['FontFormat','FontName','FontSize'],
	['Image','Flash','-','Rule','Table','Smiley']
] ;