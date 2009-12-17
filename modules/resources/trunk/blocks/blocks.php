<?php

function resources_att_show ($options) {
    global $GLOBALS;
    $resources_handler = xoops_getmodulehandler("resources", "resources");
    $category_handler = xoops_getmodulehandler("category", "resources");
    
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria("resources_pid","0"));
    $criteria->setSort('resources_dateline');         
  	$criteria->setOrder('DESC');
    $criteria->setLimit($options[0]);
    $resources_list = $resources_handler->getAll($criteria,null,false);
    $categories = $category_handler->getCategories();
    
    unset($criteria);
    $select = array(1=>'y-m-d', 2=>'y-m-d h:i:s', 3=>'Y年m月d日', 4=>'Y年m月d日 小时:分钟:秒');
    foreach ( $resources_list as $key=>$val ) {
        $repositories[$key] = $val;
        $repositories[$key]["name"] = !empty($repositories[$key]["uid"]) ? $repositories[$key]["uid"] : "";
        $repositories[$key]["cat_name"] = !empty($repositories[$key]["cat_id"]) ? $categories[$repositories[$key]["cat_id"]] : "";
        $repositories[$key]["resources_dateline"] = formatTimestamp($repositories[$key]['resources_dateline'], $select[$options[1]]);
    }
	  return $repositories;	
}

function resources_att_edit ($options) {
  	include_once XOOPS_ROOT_PATH."/modules/resources/class/blockform.php";
  	$form = new XoopsBlockForm("","","");
  	$form->addElement(new XoopsFormText("显示条目数","options[0]",5,2,$options[0]), true);
  	$time = new XoopsFormSelect('时间显示类型', 'options[1]',$options[1]);
    $time->addOption(1, 'y-m-d');
    $time->addOption(2, 'y-m-d h:i:s');
    $time->addOption(3, '年-月-日');
    $time->addOption(4, '年-月-日 小时:分钟:秒');
    $form->addElement($time); 

    return $form->render();
}

function b_category() {
	global $GLOBALS;
	$category_handler = xoops_getmodulehandler("category");
	$block = $category_handler->getCategories();
	return $block;
}
?>
