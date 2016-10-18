<?php

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_ROOT_PATH . "/core/XCube_ActionForm.class.php";
//require_once XOOPS_ROOT_PATH . "/modules/langrid/php/config/player.php";


class User_AbstractUserEditForm extends XCube_ActionForm
{
	var $mConfig = null;

	function User_AbstractUserEditForm($userConfig)
	{
		parent::XCube_ActionForm();
		$this->mConfig = $userConfig;
	}

	function validateUname()
	{
		if($this->get('uname')) {
			//
			// uname unique check
			//
			$userHandler=&xoops_gethandler('user');
			$criteria =& new CriteriaCompo(new Criteria('uname', $this->get('uname')));
			if ($this->get('uid') > 0) {
				$criteria->add(new Criteria('uid', $this->get('uid'), '<>'));
			}
			if ($userHandler->getCount($criteria) > 0) {
				$this->addErrorMessage(_MD_USER_LANG_NICKNAMETAKEN);
			}

			//
			// Check allow uname string pattern.
			//
			$regex="";
			switch($this->mConfig['uname_test_level']) {
				case 0:
					$regex="/[^a-zA-Z0-9\_\-]/";
					break;

				case 1:
					$regex="/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/";
					break;

				case 2:
					$regex="/[\000-\040]/";
					break;
			}
			if(preg_match($regex,$this->get('uname'))) {
				$this->addErrorMessage(_MD_USER_LANG_INVALIDNICKNAME);
			}

			//
			// Check bad uname patterns.
			//
			foreach($this->mConfig['bad_unames'] as $t_uname) {
				if(!empty($t_uname) && preg_match("/${t_uname}/i", $this->get('uname'))) {
					$this->addErrorMessage(_MD_USER_LANG_NAMERESERVED);
					break;
				}
			}
		}
	}

	function validateEmail()
	{
		if (strlen($this->get('email')) > 0) {
			$validateFlag = false;
			foreach ($this->mConfig['bad_emails'] as $t_email) {
				if (!empty($t_email) && preg_match("/${t_email}/i", $this->get('email'))) {
					$validateFlag = true;
					break;
//					$this->addErrorMessage(_MD_USER_ERROR_INVALID_EMAIL);
//					return;
				}
			}
			
			if (!$validateFlag) {
				$this->addErrorMessage(_MD_USER_ERROR_INVALID_EMAIL);
				return;
			}

			//
			// email unique check
			//
			$userHandler=&xoops_gethandler('user');
			$criteria =& new CriteriaCompo(new Criteria('email', $this->get('email')));
			if ($this->get('uid') > 0) {
				$criteria->add(new Criteria('uid', $this->get('uid'), '<>'));
				}
			if ($userHandler->getCount($criteria) > 0) {
				$this->addErrorMessage(_MD_USER_ERROR_EMAILTAKEN);
			}
		}
	}

	function validateTimezone_offset()
	{
		$handler =& xoops_gethandler('timezone');
		$obj =& $handler->get($this->get('timezone_offset'));
		if (!is_object($obj)) {
			$this->addErrorMessage(_MD_USER_ERROR_TIMEZONE);
		}
	}

	function validateUrl()
	{
		$t_url = $this->get('url');
		if (strlen($t_url) > 0) {
			if (!preg_match('/^https?(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $t_url)) {
				$this->addErrorMessage(XCube_Utils::formatMessage(_MD_USER_ERROR_INJURY, _MD_USER_LANG_URL));
			}
		}
	}

	function validateCurpass()
	{
		if (strlen($this->get('curpass'))>0) {
			$MD5CurPass = md5($this->get('curpass'));

			$userHandler=&xoops_gethandler('user');
			$criteria =& new CriteriaCompo(new Criteria('pass', $MD5CurPass));
			$criteria->add(new Criteria('uid', $this->get('uid'), '='));

			if ($userHandler->getCount($criteria) == 0) {
				$this->addErrorMessage(XCube_Utils::formatMessage(_MD_USER_ERROR_MATCH_PASSWORD,_MD_USER_LANG_CURRENTPASS,_MD_USER_LANG_CURRENTPASS));
				$this->set('curpass',null);	// reset
			}
		}
	}

	function validatePass()
	{
		// precondition check
		if (strlen($this->get('pass')) > 0 && !preg_match('/^[\x21-\x7e]+$/', $this->get('pass'))) {
			$this->addErrorMessage(XCube_Utils::formatMessage(_MD_USER_ERROR_INJURY, _MD_USER_LANG_PASSWORD));
			$this->set('pass',null); // reset
			$this->set('vpass',null);
		}

		if (strlen($this->get('pass'))>0||strlen($this->get('vpass'))>0) {
			if($this->get('pass')!=$this->get('vpass')) {
				$this->addErrorMessage(XCube_Utils::formatMessage(_MD_USER_ERROR_MATCH_PASSWORD,_MD_USER_LANG_PASSWORD,_MD_USER_LANG_CONFIRMPASS));
				$this->set('pass',null);	// reset
				$this->set('vpass',null);
			}
		}
	}

	function validateGroup_Key()
	{
		$module_handler= & xoops_gethandler('module');
		$psModule = $module_handler->getByDirname('langrid');
		$config_handler =& xoops_gethandler('config');
		$langridConfig =& $config_handler->getConfigsByCat(0, $psModule->mid());
		//$player = Player::getInstance();
		//$parent_key = $player->getGroupKey();
		$parent_key = $langridConfig['group_key'];
		//$MD5_child = md5($this->get('group_key'));
		if($this->get('group_key') && $parent_key!=$this->get('group_key')) {
		//if($parent_key!=$MD5_child) {
			$this->addErrorMessage(_MD_USER_ERROR_GROUPKEY);
		}
	}

//	function validateGroupId()
//	{
//		$module_handler= & xoops_gethandler('module');
//		$psModule = $module_handler->getByDirname('user');
//		$config_handler =& xoops_gethandler('config');
//		$userConfig =& $config_handler->getConfigsByCat(0, $psModule->mid());
//		if (!$this->get('group_id')
//			|| !in_array($this->get('group_id')
//			 	, explode(',', $userConfig['group_id']))) {
//			$this->addErrorMessage(_MD_USER_ERROR_GROUP_ID);
//		}
//	}
}

?>
