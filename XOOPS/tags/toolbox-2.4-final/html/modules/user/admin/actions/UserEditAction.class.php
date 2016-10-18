<?php
/**
 * @package user
 * @version $Id: UserEditAction.class.php,v 1.2 2007/12/22 17:54:05 minahito Exp $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_MODULE_PATH . "/user/class/AbstractEditAction.class.php";
require_once XOOPS_MODULE_PATH . "/user/admin/forms/UserAdminEditForm.class.php";
require_once XOOPS_MODULE_PATH . "/user/subProfile/class/SubProfileManager.class.php";

class User_UserEditAction extends User_AbstractEditAction
{
	function _getId()
	{
		return xoops_getrequest('uid');
	}
	
	function &_getHandler()
	{
		$handler =& xoops_getmodulehandler('users');
		return $handler;
	}

	function _setupActionForm()
	{
		$this->mActionForm =& new User_UserAdminEditForm();
		$this->mActionForm->prepare();
	}

	function _setupObject()
	{
		$id = $this->_getId();
		
		$this->mObjectHandler = $this->_getHandler();
		
		$this->mObject =& $this->mObjectHandler->get($id);
		
		if ($this->mObject == null && $this->isEnableCreate()) {
			$root =& XCube_Root::getSingleton();
			$this->mObject =& $this->mObjectHandler->create();
			$this->mObject->set('timezone_offset', $root->mContext->getXoopsConfig('server_TZ'));
		}
	}
	
	function executeViewInput(&$controller, &$xoopsUser, &$render)
	{
		$render->setTemplateName("user_edit.html");
		$render->setAttribute("actionForm", $this->mActionForm);

		//
		// Get some objects for input form.
		//
		$tzoneHandler =& xoops_gethandler('timezone');
		$timezones =& $tzoneHandler->getObjects();
		
		$render->setAttribute('timezones', $timezones);

		$rankHandler =& xoops_getmodulehandler('ranks');
		$ranks =& $rankHandler->getObjects(new Criteria('rank_special', 1));

		$render->setAttribute('ranks', $ranks);
		
		$groupHandler =& xoops_gethandler('group');
		$groups =& $groupHandler->getObjects(null, true);

		$groupOptions = array();
		foreach ($groups as $gid => $group) {
			$groupOptions[$gid] = $group->getVar('name');
		}

		$render->setAttribute('groupOptions', $groupOptions);
		
		// -----------------  subprofile  --------------------------
		$man = new SubProfileManager();
		if(isset($_GET["uid"])){$uid=$_GET["uid"];}
		else{$uid=-1;}
		$userData = $man->getData($uid);
		$config = $man->getConfiguration();
		$title = $man->getTitle();
		$c = count($title);
		$length = array();
		$subProfileOrder = array();
		$data = array();
		$dispNum = $man->getDisplayNumber();

		for ($i = 0; $i < $c; $i++) {
			$v = $dispNum[$i];
			$data[$i] = $userData["sub".$v."_value"];
			if(isset($_POST["inputSub".$v])) {
				$data[$i] = $_POST["inputSub".$v];
			}
			$length[$i]=$config["sub".$v."_length"];
			$subProfileOrder[$i]=$v;
		}

		$render->setAttribute('data', $data);
		$render->setAttribute('length', $length);
		$render->setAttribute('subProfileOrder', $subProfileOrder);
		$render->setAttribute('title', $title);
		// ----------------------------------------------------------
		
		
		//
		// umode option
		//
		$umodeOptions = array("nest" => _NESTED, "flat" => _FLAT, "thread" => _THREADED);
		$render->setAttribute('umodeOptions', $umodeOptions);

		//		
		// uorder option
		//
		$uorderOptions = array(0 => _OLDESTFIRST, 1 => _NEWESTFIRST);
		$render->setAttribute('uorderOptions', $uorderOptions);

		//
		// notify option
		//

		//
		// TODO Because abstract message catalog style is not decided, we load directly.
		//
		$root =& XCube_Root::getSingleton();
		$root->mLanguageManager->loadPageTypeMessageCatalog('notification');
		require_once XOOPS_ROOT_PATH . "/include/notification_constants.php";
		
		// Check the PM service has been installed.
		$service =& $root->mServiceManager->getService('privateMessage');

		$methodOptions = array();
		$methodOptions[XOOPS_NOTIFICATION_METHOD_DISABLE] = _NOT_METHOD_DISABLE;
		if ($service != null) {
			$methodOptions[XOOPS_NOTIFICATION_METHOD_PM] = _NOT_METHOD_PM;
		}
		$methodOptions[XOOPS_NOTIFICATION_METHOD_EMAIL] = _NOT_METHOD_EMAIL;

		$render->setAttribute('notify_methodOptions', $methodOptions);
		
		$modeOptions = array(XOOPS_NOTIFICATION_MODE_SENDALWAYS => _NOT_MODE_SENDALWAYS,
		                       XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE => _NOT_MODE_SENDONCE,
		                       XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT => _NOT_MODE_SENDONCEPERLOGIN
		                 );
		$render->setAttribute('notify_modeOptions', $modeOptions);
	}

	function executeViewSuccess(&$controller, &$xoopsUser, &$render)
	{
		$controller->executeForward("./index.php?action=UserList");
	}

	function executeViewError(&$controller, &$xoopsUser, &$render)
	{
		$controller->executeRedirect("index.php", 1, _MD_USER_ERROR_DBUPDATE_FAILED);
	}
	
	function executeViewCancel(&$controller, &$xoopsUser, &$render)
	{
		$controller->executeForward("./index.php?action=UserList");
	}
	
	//override _doExecute
	function _doExecute()
	{
		$ret = $this->mObjectHandler->insert($this->mObject);
		
		// -----------------  subprofile  --------------------------
		$_POST['uid'] = $this->mObject->get('uid');
		$mn = new SubProfileManager();
		$mn->setData();
		// ---------------------------------------------------------
		
		return $ret;
	}
}

?>