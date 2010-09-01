<?php

function resources_resource_show($options){

    $category_handler =& xoops_getmodulehandler('category','resources');
    $resources_handler =& xoops_getmodulehandler('resources','resources');
    $cat_name = '';    
    $criteria = new CriteriaCompo();
    if(!empty($options[0])){
        $criteria->add(new Criteria('cat_id', $options[0]), 'AND');
        $cat_obj = $category_handler->get($options[0]);
        $cat_name = $cat_obj->getVar('cat_name');
    } 
    $criteria->add(new Criteria('res_status', 1));
    $criteria->setSort($options[1]);
    $criteria->setOrder('ASC');    
    $criteria->setLimit($options[2]);    
    $resources = $resources_handler->getAll($criteria, null, false);
    $block = $resources;
 //  xoops_result($block);
    return $block;
}

function resources_resource_edit($options){
    include_once XOOPS_ROOT_PATH."/modules/resources/include/xoopsformloader.php";
    $form = new XoopsBlockForm("","","");    
    $categories = new XoopsFormSelect('资源分类', 'options[0]',$options[0]);
    $categories->addOption(0, '全部');
    $category_handler =& xoops_getmodulehandler('category','resources');
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight');
    $criteria->setOrder('ASC');
    $category = $category_handler->getList($criteria);
    foreach ($category  as $k=>$v){
        $categories->addOption($k, $v);
    }
    $form->addElement($categories, true);    
    $sort = new XoopsFormSelect('显示顺序', 'options[1]',$options[1]);
    $sort->addOption('res_weight', '按照资源排序');
    $sort->addOption('update_time', '按照更新时间');
    $form->addElement($sort, true);
    $form->addElement(new XoopsFormText('显示前几条资源',"options[2]",5,2,$options[2]), true);
    return $form->render();
}
?>
