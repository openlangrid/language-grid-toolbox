<?php

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_MODULE_PATH . "/user/forms/AbstractUserEditForm.class.php";

/***
 * @internal
 */
class User_ChangePassEditForm extends User_AbstractUserEditForm
{
	function getTokenName()
	{
		return "Module.User.ChangePassEditForm.Token." . $this->get('uid');
	}

	/**
	 * TODO The argument of this member property may be moved to constructor.
	 */
	function prepare()
	{
		parent::prepare();

		//
		// set properties
		//
		$this->mFormProperties['uid'] = new XCube_IntProperty('uid');

		$this->mFormProperties['curpass'] = new XCube_StringProperty('curpass');
		$this->mFormProperties['pass'] = new XCube_StringProperty('pass');
		$this->mFormProperties['vpass'] = new XCube_StringProperty('vpass');

		//
		// set fields
		//
		$this->mFieldProperties['curpass'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['curpass']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['curpass']->addMessage("required", _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_CURRENTPASS, "32");
		$this->mFieldProperties['curpass']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_CURRENTPASS, '32');
		$this->mFieldProperties['curpass']->addVar('maxlength', 32);

		$this->mFieldProperties['pass'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['pass']->setDependsByArray(array('required','minlength', 'maxlength'));
		$this->mFieldProperties['pass']->addMessage("required", _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_NEWPASS, "32");
		$this->mFieldProperties['pass']->addMessage('minlength', _MD_USER_ERROR_MINLENGTH, _MD_USER_LANG_NEWPASS, $this->mConfig['minpass']);
		$this->mFieldProperties['pass']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_NEWPASS, '32');
		$this->mFieldProperties['pass']->addVar('minlength', $this->mConfig['minpass']);
		$this->mFieldProperties['pass']->addVar('maxlength', 32);

		$this->mFieldProperties['vpass'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['vpass']->setDependsByArray(array('maxlength'));
		$this->mFieldProperties['vpass']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_CONFIRMNEWPASS, '32');
		$this->mFieldProperties['vpass']->addVar('maxlength', 32);

	}

	function load(&$obj)
	{
		$this->set('uid', $obj->get('uid'));

		$this->set('curpass', null);
		$this->set('pass', null);
		$this->set('vpass', null);
	}

	function update(&$obj)
	{
		if (strlen($this->get('pass'))) {
			$obj->set('pass', md5($this->get('pass')));
		}
	}

	// @OverWride
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
				$this->addErrorMessage(XCube_Utils::formatMessage(_MD_USER_ERROR_MATCH_PASSWORD,_MD_USER_LANG_NEWPASS,_MD_USER_LANG_CONFIRMNEWPASS));
				$this->set('pass',null);	// reset
				$this->set('vpass',null);
			}
		}
	}
}

?>
