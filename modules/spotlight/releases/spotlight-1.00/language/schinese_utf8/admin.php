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
 * @version        $Id: admin.php 1 2010-8-31 ezsky$
 */

define("_AM_SPOTLIGHT_HOME_MODULE_BASICS", "模块基本信息");
define("_AM_SPOTLIGHT_UPDATE_SUCCESSFUL", "更新成功");
define("_AM_SPOTLIGHT_DELETED_SUCCESSFULLY", "删除成功"); 
define("_AM_SPOTLIGHT_SAVE_STCCESSFULLY", "保存成功"); 
define("_AM_SPOTLIGHT_WRONG_OPERATING", "操作有误"); 
define("_AM_SPOTLIGHT_ERONG_DELETE", "删除有误");   
define("_AM_SPOTLIGHT_UPDATE_SUCCESSFUL", "更新成功"); 
define("_AM_SPOTLIGHT_DELETED_IN_THE_GROUP", "确定删除 %s 是吗?");
define("_AM_SPOTLIGHT_PLEASE_ADD_SLIDE", "请先添加幻灯片"); 
define("_AM_SPOTLIGHT_ADD_SLIDE", "添加幻灯片");   
define("_AM_SPOTLIGHT_MANAGEMENT_PUBLISE", "发布");
define("_AM_SPOTLIGHT_MANAGEMENT_UNPUBLISE", "未发布"); 
define("_AM_SPOTLIGHT_CAT_LINK_INFO", "该类别存在关联信息，请先清除相关信息再进行删除");      
define("_AM_SPOTLIGHT_IMEGES_TYPE_WRONG", "图片l类型有误");
define("_AM_SPOTLIGHT_ADD_PAGE", "添加页面");
define("_AM_SPOTLIGHT_EDIT_PAGE", "编辑页面");
define("_AM_SPOTLIGHT_ADD_PAGE_THEIR_SLIDE", "所属幻灯片"); 
define("_AM_SPOTLIGHT_KEEP_PUSHING_FOCUS", "推存焦点");
define("_AM_SPOTLIGHT_MANAGEMENT_TITLE", "标题"); 
define("_AM_SPOTLIGHT_ADD_PAGE_SUMMARY", "摘要"); 
define("_AM_SPOTLIGHT_ADD_PAGE_LINK", "链接"); 
define("_AM_SPOTLIGHT_ADD_PAGE_INDICATE", "是否显示"); 
define("_AM_SPOTLIGHT_ADD_PAGE_IMEGES", "图片");     
define("_AM_SPOTLIGHT_THE_NEW_IMSGE_EILL_REPLACE_THE_EXIETING", "从新上传将会覆盖现有图片");
define("_AM_SPOTLIGHT_ADD_PAGE_ALLOW_UPLOAD_TYPE", "允许上传类型为 jpeg,pjpeg,gif,png是吗");
define("_AM_SPOTLIGHT_MANAGEMENT_RELEASE_TIME", "发布时间");
define("_AM_SPOTLIGHT_SLIDE_NAME", "幻灯片名称");  
define("_AM_SPOTLIGHT_EXPLAIN", "说明");   
define("_AM_SPOTLIGHT_COMPONENTS", "组件");
define("_AM_SPOTLIGHT_NO", "暂无");
define("_AM_SPOTLIGHT_HOME_MODULE_DIRECTORY_NAME", "模块目录名称");
define("_AM_SPOTLIGHT_HOME_BASICS", "基本信息");
define("_AM_SPOTLIGHT_FOCUS_MODULE", "焦点模块");
define("_AM_SPOTLIGHT_HOME_MODULE_VERSION", "模块版本");
define("_AM_SPOTLIGHT_HOME_MODULE_DEVELOPER", "模块开发作者");
define("_AM_SPOTLIGHT_HOME_MODULE_DEVELOPMENT_OFFICIAL_SITE", "模块开发官方站点");
define("_AM_SPOTLIGHT_HOME_MODULE_APPLICATION", "模块应用");
define("_AM_SPOTLIGHT_FOCUS_MANDGEMENT", "焦点管理");
define("_AM_SPOTLIGHT_MANAGEMENT", "管理");
define("_AM_SPOTLIGHT_HOME_MODULE_PARAMATER", "模块参数");
define("_AM_SPOTLIGHT_HOME_CATEGORY_TOTAL", "分类总数");
define("_AM_SPOTLIGHT_HOME_COMPONENT_TOTAL", "组件总数");
define("_AM_SPOTLIGHT_HOME_ARTICLE_NUMBER", "文章总数");
define("_AM_SPOTLIGHT_HOME_RESET_THUMBNAIL", "重置缩略图");
define("_AM_SPOTLIGHT_HOME_UPDATE_RECORDS", "更新记录");
define("_AM_SPOTLIGHT_HOME_EDITING_AND_NAME_REPEAT", "修复编辑幻灯片时，组件名称重复问题");
define("_AM_SPOTLIGHT_HOME_FOCUS_MODULE_1.0_ALPHA", "焦点模块 1.0 Alpha");
define("_AM_SPOTLIGHT_HOME_ADDED_FUNCTIONALITY", "在焦点模块 1.5 Alpha 的基础上增加了以下功能");
define("_AM_SPOTLIGHT_HOME_COMPONENT_FUNCTION", "组件功能");
define("_AM_SPOTLIGHT_HOME_AUTO_SELECT_FUNCTION", "区块自动选择组件功能");
define("_AM_SPOTLIGHT_HOME_FOCUS_MODULE_1.5_ALPHA", "焦点模块 1.5 Alpha");    
define("_AM_SPOTLIGHT_HOME_REPAIR_TABLE", "修复数据表");
define("_AM_SPOTLIGHT_HOME_CODE_REFAAORING", "重构代码");
define("_AM_SPOTLIGHT_HOME_FOCUS_MODULE_1.0_ALPHA", "焦点模块 1.0 Alpha");
define("_AM_SPOTLIGHT_HOME_GROUP_FUNCTIONS", "分组功能");
define("_AM_SPOTLIGHT_HOME_UPLOAD_NEWS_IMAGES", "新闻图片上传");
define("_AM_SPOTLIGHT_HOME_MEDIUM_THUMBNAIL_AND_THUMBNAIL_FUNCTIONS", "中等缩略和缩略功能");
define("_AM_SPOTLIGHT_HOME_NEWS_SORTING", "新闻排序功能");
define("_AM_SPOTLIGHT_MANAGEMENT_SORT", "排序");

define("_AM_SPOTLIGHT_MANAGEMENT_STATUS", "状态");
define("_AM_SPOTLIGHT_OPERATING", "操作");
define("_AM_SPOTLIGHT_EDIT", "编辑");
define("_AM_SPOTLIGHT_DELETE", "删除");
define("_AM_SPOTLIGHT_ADD_PAGE_SHOW", "显示");
define("_AM_SPOTLIGHT_ADD_PAGE_NOT_SHOW", "不显示");
define("_AM_SPOTLIGHT_OK_TO_DELETE_FOCUS", "确定删除焦点 %s 是吗？");

//components
define("_AM_SPOTLIGHT_DEFAULT", "默认模式");
define("_AM_SPOTLIGHT_BIG_FOCUS_EFFECT", "大焦点图效果");      
define("_AM_SPOTLIGHT_PICTURE_ROTATION", "图片轮换焦点");                                                              
define("_AM_SPOTLIGHT_FADE_FOCUS_FLASH", "淡入淡出flash5屏焦点");                                                                
define("_AM_SPOTLIGHT_THREE_DIMENSIONAL_FOCUS", "3屏循环式立体焦点"); 
define("_AM_SPOTLIGHT_WIDE_FOCUS", "3屏980x425宽幅焦点"); 


?>                                                                        
                                        