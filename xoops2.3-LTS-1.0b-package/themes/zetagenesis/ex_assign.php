<?php

global $xoopsUser, $xoopsModule;
if (is_object($xoopsUser)) {
    $pm_handler =& xoops_gethandler('privmessage');

    $criteria = new CriteriaCompo(new Criteria('read_msg', 0));
    $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
    $this->assign("ex_new_messages", $pm_handler->getCount($criteria));
}

require_once XOOPS_ROOT_PATH.'/modules/system/blocks/system_blocks.php';
function get_system_main_menu_show()
{
    global $xoopsUser,$xoopsModule;
    $block = array();
    $block['lang_home'] = _MB_SYSTEM_HOME;
    $block['lang_close'] = _CLOSE;
    $module_handler =& xoops_gethandler('module');
    $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
    $criteria->add(new Criteria('isactive', 1));
    $criteria->add(new Criteria('weight', 0, '>'));
    $modules = $module_handler->getObjects($criteria, true);
    $moduleperm_handler =& xoops_gethandler('groupperm');
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $read_allowed = $moduleperm_handler->getItemIds('module_read', $groups);
    foreach (array_keys($modules) as $i) {
        if (in_array($i, $read_allowed)) {
            $block['modules'][$i]['name'] = $modules[$i]->getVar('name');
            $block['modules'][$i]['directory'] = $modules[$i]->getVar('dirname');
            $sublinks = $modules[$i]->subLink();
            if ((count($sublinks) > 0) ) {
                foreach($sublinks as $sublink){
                    $block['modules'][$i]['sublinks'][] = array('name' => $sublink['name'], 'url' => XOOPS_URL.'/modules/'.$modules[$i]->getVar('dirname').'/'.$sublink['url']);
                }
            } else {
                $block['modules'][$i]['sublinks'] = array();
            }
        }
    }
    return $block;
}

$MainMenu = get_system_main_menu_show();
$this->assign( 'ex_mainmenu', $MainMenu );
if ( is_object($xoopsModule) ) {
    $this->assign('ex_moduledir', $xoopsModule->getVar('dirname'));
    $this->assign('ex_module_name', $xoopsModule->getVar('name') );
}

?> 
