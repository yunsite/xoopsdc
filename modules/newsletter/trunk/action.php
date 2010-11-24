<?php
include_once 'header.php';

$subscribe_handler = xoops_getmodulehandler('subscribe','newsletter');
$subscribelog_handler = xoops_getmodulehandler('subscribelog','newsletter');
$model_handler = xoops_getmodulehandler('model','newsletter');
$newsletter_handler = xoops_getmodulehandler('content','newsletter');
$sentlog_handler = xoops_getmodulehandler('sentlog','newsletter');
$member_handler =& xoops_gethandler('member');

//獲取電子報訂閱人
$user_ids = $subscribe_handler->getSubscribeUsers();

//獲取電子報設置信息
$model_automatic = '';
$model_manual = '';
$model_ids = $model_handler->getIds();

if(!empty($model_ids)) {

    //創建電子報
    foreach ($model_ids as $k=>$v) {
        $letter_id = $newsletter_handler->ActionCreateLetter($v);
        if(isset($letter_id)) {
            $letters[] = $letter_id;
        }
        unset($letter_id);
    }

    //為訂閱者創建電子報日志
    if(!empty($user_ids)) {
        if(!empty($letters)) {
            foreach ($letters as $letter_id) {
                foreach ($user_ids as $k=>$uid) {
                    $sentlog_handler->createLog($letter_id, $uid);
                }
                //如果程序正常運行完將更新電子報，記錄訂閱用戶日志已全部創建完畢
                $newsletter_obj = $newsletter_handler->get($letter_id);
                $newsletter_obj->setVar('is_users', 1);
                $newsletter_handler->insert($newsletter_obj);
                unset($newsletter_obj);
            }
        }
        
        //檢索是否有電子報沒有創建完訂閱用戶日志
        $criteria = new Criteria('is_users', 0);
        $letters = $newsletter_handler->getIds($criteria);
        if(!empty($letters)) {
            foreach ($letters as $letter_id) {
                foreach ($user_ids as $k=>$uid) {
                    $sentlog_handler->createLog($letter_id, $uid);
                }
                $newsletter_obj = $newsletter_handler->get($letter_id);
                $newsletter_obj->setVar('is_users', 1);
                $newsletter_handler->insert($newsletter_obj);
                unset($newsletter_obj);
            }
        }
    }
}

//驗證訂閱用戶是否都已經創建了電子報日志
$criteria = new Criteria('is_sent', 0);
$newsletter_ids = $newsletter_handler->getIds($criteria);

if(!empty($newsletter_ids)) {
    foreach ($newsletter_ids as $k=>$letter_id) {
        if(!empty($user_ids)) {
        $notUsers = $sentlog_handler->IsCreateUsers($letter_id);
        if(!empty($notUsers)) {
            foreach ($notUsers as $k=>$uid) {
                $sentlog_handler->createLog($letter_id, $uid);
            }
            $newsletter_obj = $newsletter_handler->get($letter_id);
            $newsletter_obj->setVar('is_users', 1);
            $newsletter_handler->insert($newsletter_obj);
            unset($newsletter_obj);
        }
        unset($notUsers);
        }
    }
}

//讀取訂閱人發送日志，檢測是否有需要發送的電子報
$criteria = new Criteria('is_sent', 0);
$sentLetter_by_users = $sentlog_handler->getAll($criteria, null, false);

if(!empty($sentLetter_by_users)) {
    //發送郵件，更新訂閱人日志
    foreach ($sentLetter_by_users as $k=>$v) {
        $letter_ids[] = $v['letter_id'];
        $user_ids[] = $v['uid'];
    }
    $letter_ids = array_unique($letter_ids);
    $criteria = new Criteria("letter_id","(".implode(", ",$letter_ids). ")","in");
    $letters = $newsletter_handler->getAll($criteria, null, false);
    
    $criteria = new Criteria("uid","(".implode(", ",$user_ids). ")","in");
    $user_objs = $member_handler->getUsers($criteria);
    
    foreach ($sentLetter_by_users as $k=>$v) {
        foreach ($user_objs as $key=>$user) {
            if($v['uid'] == $user->getVar('uid')) {
                $sentLetter_by_users[$k]['uname'] = $user->getVar('uname');
                $sentLetter_by_users[$k]['email'] = $user->getVar('email');
            }
        }
    }
    
    foreach ($letters as $k=>$v) {
        foreach ($sentLetter_by_users as $key=>$val) {
            if($k == $val['letter_id']) {
                $letters[$k]['users'][$val['uid']] = $val;
            }
        }
    }
    //xoops_result($letters);
    /*
    foreach ($sentLetter_by_users as $k=>$v) {
        foreach ($user_objs as $key=>$user) {
            if($v['uid'] == $user->getVar('uid')) {
                $sentLetter_by_users[$k]['user_obj'] = $user;
            }
        }
    }
    
    foreach ($letters as $k=>$v) {
        foreach ($sentLetter_by_users as $key=>$val) {
            if($k == $val['letter_id']) {
                $letters[$k]['user_objs'][$val['uid']] = $val['user_obj'];
            }
        }
    }
    */
    
    //發送電子報給用戶,都發送成功后更新電子報狀態
    foreach ($letters as $k=>$v) {
        if(isset($v['users'])) {
            foreach ($v['users'] as $user) {
                $xoopsMailer =& xoops_getMailer();
                $xoopsMailer->multimailer->ContentType="text/html";
            		$xoopsMailer->useMail();
                $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/newsletter/language/".$xoopsConfig['language']."/mail_template/");
                $xoopsMailer->setTemplate("newsletter.tpl");
                $myts =& MyTextSanitizer::getInstance();
                 $xoopsMailer->assign("X_UNAME", $user['uname'].' <br />');
                $xoopsMailer->assign("LETTER_TITLE", $v['letter_title'].'  <br />');
                $xoopsMailer->assign("LETTER_CONTENT", $v['letter_content'].'  <br />');
                $xoopsMailer->assign("LETTER_UNSUBSCRIBE", '<a href="'.XOOPS_URL.'/modules/newsletter/subcribe.php?op=delete">我不要再收到電子報</a>  <br />');
                $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename'].'  敬上<br />');
                $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail'].'  敬上<br />');
                $xoopsMailer->assign("SITEURL", XOOPS_URL."/  <br />");
                //$xoopsMailer->setToUsers($user_obj);
                $xoopsMailer->setFromName($myts->stripSlashesGPC(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));
                $xoopsMailer->setFromEmail($myts->stripSlashesGPC($xoopsConfig['adminmail']));
                $xoopsMailer->setToEmails($myts->stripSlashesGPC($user['email']));
                $xoopsMailer->setSubject($myts->stripSlashesGPC(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES).'電子報'));
                //$xoopsMailer->setBody($myts->stripSlashesGPC($v['letter_content']));        
                $xoopsMailer->send(true);
                if($xoopsMailer->getSuccess()) { 
                    //更新訂閱用戶日志
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('uid', $user['uid']));
                    $criteria->add(new Criteria('letter_id', $k));
                    $sentlog_obj = current($sentlog_handler->getAll($criteria));
                    $sentlog_obj->setVar('is_sent', 1);
                    $sentlog_obj->setVar('sent_time', time());
                    $sentlog_handler->insert($sentlog_obj);
                    unset($sentlog_obj);
                    echo $xoopsMailer->getSuccess();
                } else {
                    echo $xoopsMailer->getErrors();
                }                
            }
        }
        //更新電子報狀態
        $newsletter_obj = $newsletter_handler->get($k);
        $newsletter_obj->setVar('is_sent', 1);
        $newsletter_handler->insert($newsletter_obj);
        unset($newsletter_obj);
    }    
}

?>
