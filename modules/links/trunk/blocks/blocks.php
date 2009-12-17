<?php

function links_block_show($options){
    include_once XOOPS_ROOT_PATH.'/modules/links/include/functions.php';
    $cat_handler  =& xoops_getmodulehandler('category', 'links');
    $link_handler =& xoops_getmodulehandler('links', 'links');
    $module_handler =& xoops_gethandler('module');
    $config_handler =& xoops_gethandler('config');
    $module = $module_handler->getByDirname('links');
    $xoopsModuleConfig = $config_handler->getConfigList($module->getVar('mid'));
    
    $cat_name = '';
    $criteria = new CriteriaCompo();
    if(!empty($options[0])){
        $criteria->add(new Criteria('cat_id', $options[0]), 'AND');
        $cat_obj = $cat_handler->get($options[0]);
        $cat_name = $cat_obj->getVar('cat_name');
    } 
    $criteria->add(new Criteria('link_status', 1));
    $criteria->setSort($options[1]);
    if($options[1] == 'link_order'){
        $criteria->setOrder('ASC');
    }else{
        $criteria->setOrder('DESC');
    }
    $criteria->setLimit($options[2]);
    $links = $link_handler->getAll($criteria, array('link_id', 'link_title', 'cat_id', 'link_url', 'link_image', 'link_dir'), false, false);
    foreach($links as $k=>$v){            
        $links[$k]['link_title'] = cut_str($v['link_title'], $options[3], 0, 'utf8');
        if(!empty($xoopsModuleConfig['logo'])){
            $links[$k]['link_image'] = XOOPS_URL.$xoopsModuleConfig['logo_dir'].$v['link_image'];
        }else{
            $links[$k]['link_image'] = $v['link_dir'];
        }
    }
    $display['cat_name'] = $cat_name;
    $display['display_cat'] = $options[4];
    $display['display'] = $options[5];
    $display['links'] = $links;
    $display['display_css'] = XOOPS_URL.'/modules/links/templates/style.css';
    $block = array('display' => $display);

    return $block;
}

function links_block_edit($options){
    // function XoopsBlockForm
    include_once XOOPS_ROOT_PATH."/modules/links/include/xoopsformloader.php";
    $form = new XoopsBlockForm("","","");    
    $categories = new XoopsFormSelect(_MB_LINKS_SHOWCAT, 'options[0]',$options[0]);
    $categories->addOption(0, _MB_LINKS_ALL);
    $cat_handler = xoops_getmodulehandler('category', 'links');
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_order');
    $criteria->setOrder('ASC');
    $category = $cat_handler->getList($criteria);
    foreach ($cat_handler->getList($criteria) as $k=>$v){
        $categories->addOption($k, $v);
    }
    $form->addElement($categories, true);    
    $sort = new XoopsFormSelect(_MB_LINKS_SORTWAY, 'options[1]',$options[1]);
    $sort->addOption('published', _MB_LINKS_PUBLISHTIME);
    $sort->addOption('datetime', _MB_LINKS_UPDATETIME);
    $sort->addOption('link_order', _MB_LINKS_DEFAULT);
    $form->addElement($sort, true);
    $form->addElement(new XoopsFormText(_MB_LINKS_SHOWHOWLIK,"options[2]",5,2,$options[2]), true);
    $form->addElement(new XoopsFormText(_MB_LINKS_LIKTITLEMAX,"options[3]",5,2,$options[3]), true);
    $form->addElement(new XoopsFormRadioYN(_MB_LINKS_SHOWCATTITLE, 'options[4]', $options[4]), true);
    $display = new XoopsFormSelect(_MB_LINKS_BYSHOW, 'options[5]',$options[5]);
    $display->addOption('1', _MB_LINKS_LOGOHOR);
    $display->addOption('2', _MB_LINKS_LOGOVER);
    $display->addOption('3', _MB_LINKS_TITLEHOR);
    $display->addOption('4', _MB_LINKS_TITLEVER);
    $form->addElement($display, true);
    return $form->render();
    /*
    $cat_handler = xoops_getmodulehandler('category', 'links');
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_order');
    $criteria->setOrder('ASC');
    $category = $cat_handler->getList($criteria);
    $form = "显示分类：<select id='options[0]' name='options[0]'>";
    $form .="<option value='0'>全部</option>";
    foreach($category as $k=>$v){ 
        if ($k == $options[0]){
            $sel = " selected='selected'";
        }else{
            $sel = "";
        } 
        $form .= "<option value='".$k."'$sel>".$v."</option>";
    }
    $published = "";
    $datetime = "";
    $link_order = "";
    if($options[1] == 'published'){
        $published =  " selected='selected'";
    }elseif($options[1] == 'datetime'){
        $datetime =  " selected='selected'";
    }else{
        $link_order =  " selected='selected'";
    }
    $form .= "</select>
              <br /><br />显示顺序:
              <select id='options[1]' name='options[1]'>
                  <option value='published'".$published.">发布时间</option>
                  <option value='datetime'".$datetime.">更新时间</option>
                  <option value='link_order'".$link_order.">当选择分类时按照该分类下的链接排序显示</option>
              </select>              
              ";
    $form .= "<br /><br />显示几条链接：<input type='text' name='options[2]' value='".$options[2]."' size='4' maxlength='4'/>";
    $form .= "<br /><br />链接标题最大字符：<input type='text' name='options[3]' value='".$options[3]."' size='4' maxlength='4'/>";
    $form .= "<br /><br />是否显示分类标题：<input type='radio' id='options[4]' name='options[4]' value='1'";
    if ( $options[4] == 1 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._YES."<input type='radio' id='options[4]' name='options[4]' value='0'";
    if ( $options[4] == 0 ) {
        $form .= " checked='checked'";
    }
    $form .= " />&nbsp;"._NO."";
    $dis1 = "";
    $dis2 = "";
    $dis3 = "";
    $dis4 = "";
    if($options[5] == '1'){
        $dis1 =  " selected='selected'";
    }elseif($options[5] == '2'){
        $dis2 =  " selected='selected'";
    }elseif($options[5] == '3'){
        $dis3 =  " selected='selected'";
    }else{
        $dis4 =  " selected='selected'";
    }
    $form .= "</select>
              <br /><br />展示形式:
              <select id='options[5]' name='options[5]'>
                  <option value='1'".$dis1.">logo横向展示</option>
                  <option value='2'".$dis2.">logo纵向展示</option>
                  <option value='3'".$dis3.">标题横向展示</option>
                  <option value='4'".$dis4.">标题纵向展示</option>
              </select>              
              ";    
    return $form;
    */
}
?>
