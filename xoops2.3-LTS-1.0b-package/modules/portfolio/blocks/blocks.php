<?php
if (!defined('XOOPS_ROOT_PATH')) { exit(); }

function portfolio_block_service_show(){
    
    $block['service_id'] = isset($_REQUEST['service_id']) ? intval($_REQUEST['service_id']) : '';
    
    $service_handler = xoops_getmodulehandler('service','portfolio');
    
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('service_status', 1), 'AND');
    $criteria->setSort('service_weight');
    $criteria->setOrder('ASC');
    $block['service'] = $service_handler->getAll($criteria, null, false);

    return  $block;
}

function portfolio_block_case_show($options){    
    
    global $xoopsDB;
    
    $service_handler = xoops_getmodulehandler('service','portfolio');
    $case_handler = xoops_getmodulehandler('case','portfolio');
    $cs_handler = xoops_getmodulehandler('cs','portfolio');
    $images_handler = xoops_getmodulehandler('images','portfolio');
    
    $cases = '';
    $case_ids = '';
    
    //weight
    if($options[1] == "case_weight") {
        $weight = "ASC";
    } elseif($options[1] == "case_datetime") {
        $weight = "DESC";
    } else {
        $weight = "ASC";
    }
    
    if(!empty($options[0])) {
        $cs = $cs_handler->getCaseIds(array($options[0]));
        if(!empty($cs)) {
            foreach ($cs as $k=>$v) {
                $case_ids[] = $v['case_id'];
            }
        }  
    }
    $criteria = new CriteriaCompo();
    
    if(!empty($case_ids)) {
        if(!is_array($case_ids)) $case_ids = array($case_ids);
        $criteria->add(new Criteria("case_id","(".implode(", ",$case_ids). ")","in"), 'AND');
        unset($case_ids);
    }
    
    if($options[12] == 0) {  
        $criteria->add(new Criteria('case_status', 1));
        $criteria->setSort($options[1]);  
        $criteria->setOrder($weight);
        $criteria->setLimit($options[2]);
        $criteria->setStart(0);
        $cases = $case_handler->getAll($criteria, null, false);
    } else {
        $query  = "SELECT * FROM " . $xoopsDB->prefix("portfolio_case");
        $query .= " WHERE case_image != '' ";
        $query .= " AND case_status = '1' ";
        if(!empty($case_ids)) {
            $case_ids = implode(", ",$case_ids);
            $query .= " AND case_id in ({$case_ids}) ";
        }
        $query .= " ORDER BY {$options[1]} {$weight} LIMIT 0 , {$options[2]}";
        
        $result = $xoopsDB->query($query);
        $rows = array();
        while ($row = $xoopsDB->fetchArray($result)) {
            $rows[] = $row;
        }

        if (count($rows) > 0){
            foreach ($rows as $row) {
                $res =& $case_handler->create(false);
                $res->assignVars($row);
                $_res = array();

                foreach ($row as $tag => $val) {
                    $_res[$tag] = @$res->getVar($tag);
                }
                $cases[] = $_res;
                unset($res, $_res);
            }
        }
    }

    if(!empty($cases)) {
    
        $myts = MyTextSanitizer::getInstance();
        // Case service
        $services = $service_handler->getList();
        foreach ($cases as $k=>$v) {
            $case_ids[] = $k;            
            $cases[$k]['case_pushtime'] = formatTimestamp($v['case_pushtime'],'Y-m-d');
            $cases[$k]['case_datetime'] = formatTimestamp($v['case_datetime'],'Y-m-d');
            $case_summary = strip_tags($myts->undoHtmlSpecialChars(strip_tags($v['case_summary'])));
            $case_description = strip_tags($myts->undoHtmlSpecialChars(strip_tags($v['case_description'])));                            
            
            if(!empty($options[7])) {
                $cases[$k]['case_title'] = xoops_substr($v['case_title'], '', $options[7]);
                $cases[$k]['case_menu_title'] = xoops_substr($v['case_menu_title'], '', $options[7]);
            }
            
            if(!empty($options[8])) {
                $cases[$k]['case_summary'] = xoops_substr($case_summary, '', $options[8]);
            }else{
                $cases[$k]['case_summary']  =  $case_summary;
            }
            
            if($options[9]) {
                $cases[$k]['case_description'] = xoops_substr($case_description, '', $options[9]);
             }else{
                $cases[$k]['case_description']  =  $case_description;
            }
        }
    
        $cs_services = $cs_handler->getServerIds($case_ids);
        foreach($cs_services as $k=>$v) {
            $cs_services[$k]['service'] = $services[$v['service_id']];
        }
        
        foreach ($cases as $k=>$v) {
            $cases[$k]['service'] = '';
            foreach ($cs_services as $key=>$val) {
                if($k == $val['case_id']) {
                    if(empty($v['service_id'])) $cases[$k]['service_id'] = $val['service_id'];
                    $cases[$k]['service'] .= $val['service'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
                }
            }       
        }
        
        // Case Gallery
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria("case_id","(".implode(", ",$case_ids). ")","in"), 'AND');
        $images = $images_handler->getAll($criteria, null, false);
        foreach ($cases as $k=>$v) {
            foreach ($images as $key=>$val) {
                if($k == $val['case_id']) {
                    if(!empty($options[10])) $val['image_title'] = xoops_substr($val['image_title'], '', $options[10]);

                    if(!empty($options[11])) {
                        $text = strip_tags($myts->undoHtmlSpecialChars(strip_tags($val['image_desc'])));
                        $val['image_desc'] = xoops_substr($text, '', $options[11]);
                    }
                    $cases[$k]['gallery'][$key] = $val;
                }   
            }       
        }
        xoops_result($cases[$k]['gallery']);
    }
    
    $block['cases'] = $cases;
    $block['is_summary'] = $options[3];
    $block['is_desc'] = $options[4];
    $block['is_image'] = $options[5];
    $block['is_gallery'] = $options[6];
//       xoops_result($block);
    return $block;

}

function portfolio_block_case_edit($options){
	
    include_once XOOPS_ROOT_PATH."/modules/portfolio/class/blockform.php";
  	$form = new XoopsBlockForm("","","");
  	
    $service_handler = xoops_getmodulehandler('service','portfolio');
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('service_status', 1), 'AND');
    $criteria->setSort('service_weight');
    $criteria->setOrder('ASC');
    $services = $service_handler->getList($criteria);
    
    $service_select = new XoopsFormSelect('服务选择', "options[0]", $options[0]);
    $service_select->addOption(0, "默认全部");
    $service_select->addOptionArray($services);
    $form->addElement($service_select);
    
    $weight = new XoopsFormSelect('排序方式', "options[1]", $options[1]);
    $weight->addOption("case_weight", "指定排序");
    $weight->addOption("case_pushtime", "发布时间");
    $weight->addOption("case_datetime", "更新时间");
    $form->addElement($weight);
    
    $form->addElement(new XoopsFormText("显示条目数", "options[2]", 5,100, $options[2]));
    
    $form->addElement(new XoopsFormRadioYN("显示摘要", 'options[3]', $options[3]));
    $form->addElement(new XoopsFormRadioYN("显示内容", 'options[4]', $options[4]));
    $form->addElement(new XoopsFormRadioYN("显示题头图片", 'options[5]', $options[5]));
    $form->addElement(new XoopsFormRadioYN("显示相册", 'options[6]', $options[6]));

    $form->addElement(new XoopsFormText("标题字符限制", "options[7]", 5, 100, $options[7]));
    $form->addElement(new XoopsFormText("摘要字符限制", "options[8]", 5, 100, $options[8]));
    $form->addElement(new XoopsFormText("内容字符限制", "options[9]", 5, 100, $options[9]));
    $form->addElement(new XoopsFormText("相册标题字符限制", "options[10]", 5,100, $options[10]));
    $form->addElement(new XoopsFormText("相册描述字符限制", "options[11]", 5,100, $options[11]));
    
    $form->addElement(new XoopsFormRadioYN("只搜索带标题图片的案例", 'options[12]', $options[12]));
    return $form->render();
}
?>