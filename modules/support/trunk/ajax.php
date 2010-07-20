 <?php
include_once '../../mainfile.php';

header('Content-Type:text/html; charset='._CHARSET);

global $xoopsLogger;
$xoopsLogger->activated = false;

$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'exit';

switch ($op) {
    case 'forword':
        $cat_id = isset($_REQUEST['cat_id']) ? trim($_REQUEST['cat_id']) : '';
        if(empty($cat_id)) {
            echo '<option value="0">'._MA_SUPPORT_CHOICE.'</option>';
            exit();
        }
        
        $member_handler =& xoops_gethandler('member');
        $linkusers_handler =& xoops_getmodulehandler('linkusers','support');
        
        $support_ids = null;
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_id', $cat_id));
        $linkusers = $linkusers_handler->getAll($criteria, array('uid'), false);
        if(!empty($linkusers)){
            foreach($linkusers as $k=>$v){
                $support_ids[] = $v['uid'];
            }
        }
    
        if($support_ids != null) {
            $criteria = new Criteria("uid","(".implode(", ",$support_ids). ")","in");
            $support_users = $member_handler->getUsers($criteria);
            echo '<option value="0">_MA_SUPPORT_CHOICE</option>';
            foreach($support_users as $user){
                $name = $user->getVar('uname');
                if($user->getVar('name')) $name = $user->getVar('name');
                echo '<option  value="'.$user->getVar('uid').'">'.$name.'</option>';
                unset($name);
            }
        }
    break;
    
    default:
    case 'exit':
    break;
}

?>
