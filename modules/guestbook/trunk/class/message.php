<?php


class GuestbookMessage extends XoopsObject
{
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('msg_time', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('message', XOBJ_DTYPE_TXTAREA, null, true);
        $this->initVar('approve', XOBJ_DTYPE_INT, 0, false);
    }
    
    function GuestbookMessage()
    {
        $this->__construct();
    }
}

class GuestbookMessageHandler extends XoopsPersistableObjectHandler
{

    function __construct(&$db) 
    {
        parent::__construct($db, "guestbook_messages", 'GuestbookMessage', 'id', 'title');
    }
    
    function GuestbookMessageHandler(&$db)
    {
        $this->__construct($db);
    }


    
        
    /**
    * Get {@link XoopsForm} for setting prune criteria
    *
    * @return object
    **/
       function getForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? sprintf(_MD_GUESTBOOK_INSERT, _MD_GUESTBOOK_INSERT) : sprintf(_MD_GUESTBOOK_EDIT, _MD_GUESTBOOK_EDIT);

        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
       
        global $xoopsUser;
        
        if ($xoopsUser) {
	          $form->addElement(new XoopsFormHidden('uid', $xoopsUser->getVar('uid')));
	          $form->addElement(new XoopsFormHidden('name', $xoopsUser->getVar('uname')));
	          $form->addElement(new XoopsFormLabel(_MD_GUESTBOOK_AUTHOR, $xoopsUser->getVar('uname')));
        } else {
            $form->addElement(new XoopsFormText(_MD_GUESTBOOK_AUTHOR, 'name', 35, 35));
            $form->addElement(new XoopsFormText(_MD_GUESTBOOK_EMAIL, 'email', 35, 35));
        }

        
        $form->addElement(new XoopsFormText(_MD_GUESTBOOK_TITLE, 'title', 35, 35));
        $form->addElement(new XoopsFormTextArea(_MD_GUESTBOOK_TEXT, 'message'));
        //$form->addElement(new XoopsFormCaptcha(), true);
      
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}
?>
