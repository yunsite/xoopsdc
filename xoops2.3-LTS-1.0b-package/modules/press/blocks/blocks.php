<?php
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
function press_block_news_show($options) {
	global $GLOBALS;
	$criteria =  new Criteria("cat_id",$options[0]);
	$criteria->setLimit($options[1]);
	$criteria->setSort("topic_date");
	$criteria->setOrder("DESC");
	$field = array("topic_id","subject","topic_date");
	$topic_handler = xoops_getmodulehandler("topics","press");
	$topics = $topic_handler->getAll($criteria,$field,false,true);
	foreach($topics as $k=>$v){
		$topics[$k]['topic_date'] = formatTimestamp($v['topic_date'],"m.d");
	}
	$block = $topics;
	return $block;
}
function press_block_news_edit($options) {
	global $GLOBALS;
	
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$category_handler =& xoops_getmodulehandler("category","press");
	$form = new XoopsBlockForm("","","");
	$form_select = new XoopsFormSelect("catgory","options[0]",$options[0]);
	$form_select->addOption(0,_ALL);
	$form_select->addOptionArray($category_handler->getList());
	$form->addElement($form_select);
	$form->addElement(new XoopsFormText("show","options[1]",5,2,$options[1]));
	$form->render();

	return $form->render();
}
/*
function b_category() {
	global $GLOBALS;
	$category_handler = xoops_getmodulehandler("category","press");
	$block = $category_handler->getCategoryList();
	return $block;
}

function press_block_archives_show() {
	global $GLOBALS;
	$topic_handler = xoops_getmodulehandler("topics","press");
	$criteria = new CriteriaCompo();
	$criteria ->add(new Criteria("topic_date","0",">"));
	$criteria->setOrder("ASC");
	$criteria->setSort("topic_date");
	unset($criteria);
	$block = $topic_handler->getArrTime();

	return $block;
}
*/
function press_block_cateogry_edit($options) {

}

function press_block_category_show($options) {

    $category_handler = xoops_getmodulehandler("category","press");
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_id');         
    $criteria->setOrder('ASC');
    $block = $category_handler->getList($criteria);

	return $block;
}
?>
