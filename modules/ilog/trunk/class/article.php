<?php


class IlogArticle extends XoopsObject
{
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uname', XOBJ_DTYPE_TXTBOX, null, true, 25);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('keywords', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('text_body', XOBJ_DTYPE_TXTAREA, null, true);
        $this->initVar('time_create', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('time_publish', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('counter', XOBJ_DTYPE_INT, null, false);
        $this->initVar('comments', XOBJ_DTYPE_INT, null, false);
        $this->initVar('trackbacks', XOBJ_DTYPE_INT, null, false);
        
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0, false);
    }
    
    function IlogArticle()
    {
        $this->__construct();
    }
    
        /**
    * Get {@link XoopsForm} for setting prune criteria
    *
    * @return object
    **/
    function getForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['SCRIPT_NAME'];
        }
        $title = $this->isNew() ? sprintf(_MD_ILOG_INSERT, _MD_ILOG_INSERT) : sprintf(_MD_ILOG_EDIT, _MD_ILOG_EDIT);
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->addElement(new XoopsFormText(_MD_ILOG_TITLE, 'title', 60, 255,$this->getVar('title')));
       
        //tag
        $itemid = $this->isNew() ? 0 : $this->getVar("id");
        include_once XOOPS_ROOT_PATH . "/modules/tag/include/formtag.php";
        $form->addElement(new XoopsFormTag("keywords", 60, 255, $itemid, $catid = 0));

        $configs = array('editor'=>'fckeditor','toolbarset'=>'ilog','width'=>'100%','height'=>'500px','value'=>$this->getVar('text_body','e'));  
        $form->addElement(new XoopsFormEditor(_MD_ILOG_TEXT, 'text_body',$configs), true);

        $configs = array('editor'=>'fckeditor','toolbarset'=>'Basic','width'=>'100%','height'=>'200px','value'=>$this->getVar('summary','e'));  
        $form->addElement(new XoopsFormEditor(_MD_ILOG_SUMMARY, 'summary',$configs), true);

        $form_select = new XoopsFormSelect(_MD_ILOG_STATUS,'status', $this->getVar('status'));
        $form_select->addOption('1', _MD_ILOG_ACCESSALL);
        $form_select->addOption('2', _MD_ILOG_ACCESSME);
		$form->addElement($form_select);
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('id', $this->getVar('id')));
        }
        if ($this->isNew()){
        global $xoopsUser;
        $form->addElement(new XoopsFormHidden('uid', $xoopsUser->uid()));
        $form->addElement(new XoopsFormHidden('uname', $xoopsUser->getVar('uname')));
        }
        
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

class IlogArticleHandler extends XoopsPersistableObjectHandler
{

    function __construct(&$db) 
    {
        parent::__construct($db, "ilog_article", 'IlogArticle', 'id', 'title');
    }
    
    function IlogArticleHandler(&$db)
    {
        $this->__construct($db);
    }


    /**
     * Update keyword-article links of the article
     * 
     * @param    object    $article     {@link Article} reference to Article
     * @return     bool     true on success
     */
    function updateKeywords(&$article)
    {
        if (!@include_once XOOPS_ROOT_PATH . "/modules/tag/include/functions.php") {
            return false;
        }
        if (!$tag_handler =& tag_getTagHandler()) {
            return false;
        }
        $tag_handler->updateByItem($article->getVar("keywords", "n"), $article->getVar("id"), "ilog");
        return true;
    }

    /**
     * Delete keyword-article links of the article from database
     * 
     * @param    object    $article     {@link Article} reference to Article
     * @return     bool     true on success
     */
    function deleteKeywords(&$article)
    {
        if (!@include_once XOOPS_ROOT_PATH . "/modules/tag/include/functions.php") {
            return false;
        }
        if (!$tag_handler =& tag_getTagHandler()) {
            return false;
        }
        $tag_handler->updateByItem(array(), $article->getVar("id"), "ilog");
        return true;
    }

    /**
     * delete an article from the database
     * 
     * {@link Text}
     *
     * @param    object    $article     {@link Article} reference to Article
     * @param     bool     $force         flag to force the query execution despite security settings
     * @return     bool     true on success
     */
    function delete(&$article, $force = true)
    {
    	/*
        if (empty($force) && xoops_comment_count($GLOBALS["xoopsModule"]->getVar("mid"), $article->getVar("art_id"))) {
            return false;
        }
        */
        // placeholder for files
        /*
        $file_handler =&  xoops_getmodulehandler("file", $GLOBALS["artdirname"]);
        $file_handler->deleteAll(new Criteria("art_id", $article->getVar("art_id")));
        */

        $this->deleteKeywords($article);
        
        parent::delete($article, $force);

        unset($article);
        return true;
    }
        
}
?>
