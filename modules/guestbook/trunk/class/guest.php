<?php
/**
 * ****************************************************************************
 * Module généré par TDMCreate de la TDM "http://www.tdmxoops.net"
 * ****************************************************************************
 * guestbook - MODULE FOR XOOPS AND IMPRESS CMS
 * Copyright (c) ezsky ()
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       ezsky ()
 * @license         GPL
 * @package         guestbook
 * @author 			ezsky ()
 *
 * Version : 1.00:
 * ****************************************************************************
 */
 
	
	if (!defined("XOOPS_ROOT_PATH")) {
		die("XOOPS root path not defined");
	}

	if (!class_exists("XoopsPersistableObjectHandler")) {
		include_once XOOPS_ROOT_PATH."/modules/guestbook/class/object.php";
	}

	class guestbook_guest extends XoopsObject
	{ 
		//Constructor
		function __construct()
		{
			$this->XoopsObject();
			$this->initVar("guest_id",XOBJ_DTYPE_INT,null,false,8);
			 $this->initVar("guest_content",XOBJ_DTYPE_TXTAREA, null, false);
			$this->initVar("guest_name",XOBJ_DTYPE_TXTBOX,null,false);
			$this->initVar("guest_email",XOBJ_DTYPE_TXTBOX,null,false);
			$this->initVar("guest_url",XOBJ_DTYPE_TXTBOX,null,false);
			$this->initVar("guest_submitter",XOBJ_DTYPE_INT,null,false,10);
			$this->initVar("guest_date_created",XOBJ_DTYPE_INT,null,false,10);
			$this->initVar("guest_online",XOBJ_DTYPE_INT,null,false,1);
			
			// Pour autoriser le html
			$this->initVar("dohtml", XOBJ_DTYPE_INT, 1, false);
			
		}

		function guestbook_guest()
		{
			$this->__construct();
		}
	
		function getForm($action = false)
		{
			global $xoopsDB, $xoopsModuleConfig;
		
			if ($action === false) {
				$action = $_SERVER["REQUEST_URI"];
			}
		
			$title = $this->isNew() ? sprintf(_AM_GUESTBOOK_GUEST_ADD) : sprintf(_AM_GUESTBOOK_GUEST_EDIT);

			include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

			$form = new XoopsThemeForm($title, "form", $action, "post", true);
			$form->setExtra("enctype=\"multipart/form-data\"");
			
			$editor_configs=array();
			$editor_configs["name"] ="guest_content";
			$editor_configs["value"] = $this->getVar("guest_content", "e");
			$editor_configs["rows"] = 20;
			$editor_configs["cols"] = 80;
			$editor_configs["width"] = "100%";
			$editor_configs["height"] = "400px";
			$editor_configs["editor"] = $xoopsModuleConfig["guestbook_editor"];				
			$form->addElement( new XoopsFormEditor(_AM_GUESTBOOK_GUEST_CONTENT, "guest_content", $editor_configs), true );
			$form->addElement(new XoopsFormText(_AM_GUESTBOOK_GUEST_NAME, "guest_name", 50, 255, $this->getVar("guest_name")), true);
			$form->addElement(new XoopsFormText(_AM_GUESTBOOK_GUEST_EMAIL, "guest_email", 50, 255, $this->getVar("guest_email")), true);
			$form->addElement(new XoopsFormText(_AM_GUESTBOOK_GUEST_URL, "guest_url", 50, 255, $this->getVar("guest_url")), true);
			$form->addElement(new XoopsFormSelectUser(_AM_GUESTBOOK_GUEST_SUBMITTER, "guest_submitter", false, $this->getVar("guest_submitter"), 1, false), true);
			$form->addElement(new XoopsFormTextDateSelect(_AM_GUESTBOOK_GUEST_DATE_CREATED, "guest_date_created", "", $this->getVar("guest_date_created")));
			 $guest_online = $this->isNew() ? 1 : $this->getVar("guest_online");
			$check_guest_online = new XoopsFormCheckBox(_AM_GUESTBOOK_GUEST_ONLINE, "guest_online", $guest_online);
			$check_guest_online->addOption(1, " ");
			$form->addElement($check_guest_online);
			
			$form->addElement(new XoopsFormHidden("op", "save_guest"));
			$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
			$form->display();
			return $form;
		}
	}
	class guestbookguestbook_guestHandler extends XoopsPersistableObjectHandler 
	{

		function __construct(&$db) 
		{
			parent::__construct($db, "guestbook_guest", "guestbook_guest", "guest_id", "");
		}

	}
	
?>