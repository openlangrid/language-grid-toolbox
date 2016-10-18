<?php

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once dirname(__FILE__) . "/AbstractUserEditForm.class.php";
//require_once XOOPS_MODULE_PATH . "/cubeUtils/class/MultiLanguage.class.php";


class User_RegisterEditForm extends User_AbstractUserEditForm
{
	function getTokenName()
	{
		return "module.user.UserRegisterEditForm.TOKEN";
	}

	function prepare()
	{
		parent::prepare();
		//
		// Set form properties
		//
		$this->mFormProperties['uname'] = new XCube_StringProperty('uname');
		$this->mFormProperties['name'] = new XCube_StringProperty('name');
		$this->mFormProperties['email'] = new XCube_StringProperty('email');
		$this->mFormProperties['user_viewemail'] = new XCube_BoolProperty('user_viewemail');
//		$this->mFormProperties['url'] = new XCube_StringProperty('url');
		$this->mFormProperties['timezone_offset'] = new XCube_FloatProperty('timezone_offset');
		$this->mFormProperties['pass'] = new XCube_StringProperty('pass');
		$this->mFormProperties['vpass'] = new XCube_StringProperty('vpass');
		$this->mFormProperties['user_mailok'] = new XCube_BoolProperty('user_mailok');
//		$this->mFormProperties['group_id'] = new XCube_StringProperty('group_id');
		//$this->mFormProperties['group_key'] = new XCube_StringProperty('group_key');
		$this->mFormProperties['display_language'] = new XCube_StringProperty('display_language');
		$this->mFormProperties['agree'] = new XCube_BoolProperty('agree');

//		$this->mFormProperties['level'] = new XCube_StringProperty('level');

		//
		// Set field properties
		//
		$this->mFieldProperties['uname'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['uname']->setDependsByArray(array('required', 'maxlength', 'minlength'));
		$this->mFieldProperties['uname']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_USER_ID, '25');
		//$this->mFieldProperties['uname']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_USER_ID, min(25,$this->mConfig['maxuname']));
		//$this->mFieldProperties['uname']->addMessage('minlength', _MD_USER_ERROR_MINLENGTH, _MD_USER_LANG_USER_ID, $this->mConfig['minuname']);
		$this->mFieldProperties['uname']->addMessage('maxlength', _MD_USER_LANG_REGULATION, _MD_USER_LANG_USER_ID, $this->mConfig['minuname'],min(25,$this->mConfig['maxuname']));
		$this->mFieldProperties['uname']->addMessage('minlength', _MD_USER_LANG_REGULATION, _MD_USER_LANG_USER_ID, $this->mConfig['minuname'],min(25,$this->mConfig['maxuname']));
		$this->mFieldProperties['uname']->addVar('maxlength', min(25,$this->mConfig['maxuname']));
		$this->mFieldProperties['uname']->addVar('minlength', $this->mConfig['minuname']);

		$this->mFieldProperties['name'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['name']->setDependsByArray(array('maxlength'));
		$this->mFieldProperties['name']->addMessage("maxlength", _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_USER_NAME, "60");
		$this->mFieldProperties['name']->addVar("maxlength", 60);

		$this->mFieldProperties['email'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['email']->setDependsByArray(array('required', 'maxlength', 'email'));
		$this->mFieldProperties['email']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_EMAIL, '60');
		$this->mFieldProperties['email']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_EMAIL, '60');
		$this->mFieldProperties['email']->addVar('maxlength', 60);
		$this->mFieldProperties['email']->addMessage('email', _MD_USER_ERROR_EMAIL, _MD_USER_LANG_EMAIL);

//		$this->mFieldProperties['url'] = new XCube_FieldProperty($this);
//		$this->mFieldProperties['url']->setDependsByArray(array('maxlength'));
//		$this->mFieldProperties['url']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_URL, '100');
//		$this->mFieldProperties['url']->addVar('maxlength', 100);

		$this->mFieldProperties['pass'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['pass']->setDependsByArray(array('required', 'minlength', 'maxlength'));
		$this->mFieldProperties['pass']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_PASSWORD, '32');
		//$this->mFieldProperties['pass']->addMessage('minlength', _MD_USER_ERROR_MINLENGTH, _MD_USER_LANG_PASSWORD, $this->mConfig['minpass']);
		//$this->mFieldProperties['pass']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_PASSWORD, '32');
		$this->mFieldProperties['pass']->addMessage('minlength', _MD_USER_LANG_REGULATION, _MD_USER_LANG_PASSWORD, $this->mConfig['minpass'],'32');
		$this->mFieldProperties['pass']->addMessage('maxlength', _MD_USER_LANG_REGULATION, _MD_USER_LANG_PASSWORD, $this->mConfig['minpass'],'32');
		$this->mFieldProperties['pass']->addVar('minlength', $this->mConfig['minpass']);
		$this->mFieldProperties['pass']->addVar('maxlength', 32);

		$this->mFieldProperties['vpass'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['vpass']->setDependsByArray(array('required', 'maxlength'));
		$this->mFieldProperties['vpass']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_CONFIRMPASS, '32');
		$this->mFieldProperties['vpass']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_CONFIRMPASS, '32');
		$this->mFieldProperties['vpass']->addVar('maxlength', 32);

//		$this->mFieldProperties['group_id'] = new XCube_FieldProperty($this);
//		$this->mFieldProperties['group_id']->setDependsByArray(array('required'));
//		$this->mFieldProperties['group_id']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_GROUP_ID);

		/*
		$this->mFieldProperties['group_key'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['group_key']->setDependsByArray(array('required', 'maxlength'));
		$this->mFieldProperties['group_key']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_GROUP_KEY, '100');
		$this->mFieldProperties['group_key']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_URL, '100');
		$this->mFieldProperties['group_key']->addVar('maxlength', 100);
		*/

		$this->mFieldProperties['timezone_offset'] = new XCube_FieldProperty($this);
//		$this->mFieldProperties['timezone_offset']->setDependsByArray(array('required'));
//		$this->mFieldProperties['timezone_offset']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_TIMEZONE_OFFSET);

		$this->set('display_language',$GLOBALS['xoopsConfig']['language']);
		var_dump($this);
	}

	function load(&$obj)
	{
	}

	function update(&$obj)
	{
		$obj->set('uname', $this->get('uname'));
		$obj->set('name', $this->get('name'));
		$obj->set('email', $this->get('email'));
		$obj->set('user_viewemail', $this->get('user_viewemail'));
//		$obj->set('url', $this->get('url'));
		$obj->set('user_avatar','blank.gif',true);
		$obj->set('timezone_offset', $this->get('timezone_offset'));
//		$obj->set('timezone_offset', '9');
		$obj->set('pass', md5($this->get('pass')));
		$obj->set('user_mailok', $this->get('user_mailok'));
//		$obj->set('group_id', $this->get('group_id'));
//		$obj->set('group_key', $this->get('group_key'));
		$obj->set('agree', $this->get('agree'));

		$actkey=substr(md5(uniqid(mt_rand(),1)),0,8);
        $obj->set('actkey',$actkey,true);
        $obj->set('user_regdate',time(),true);

//        $obj->set('level', '2');

        $CUtil = new CubeUtil_MultiLanguage;
		$Lang = $CUtil->getLangByName($this->get('display_language'));
		if($Lang){
			setcookie(CUBE_UTILS_ML_PARAM_NAME, $Lang, time()+CUBE_UTILS_ML_COOKIELIFETIME,$CUtil->mCookiePath , '', 0);
			setcookie(CUBE_UTILS_ML_COOKIE_NAME, $this->get('display_language'), time()+CUBE_UTILS_ML_COOKIELIFETIME,$CUtil->mCookiePath , '', 0);
		}
	}
}

class User_RegisterAgreeEditForm extends User_RegisterEditForm
{
	function prepare()
	{
		parent::prepare();

		// set properties
		$this->mFormProperties['agree']=new XCube_IntProperty('agree');

		// set fields
		$this->mFieldProperties['agree']=new XCube_FieldProperty($this);
		$this->mFieldProperties['agree']->setDependsByArray(array('required','intRange'));
		$this->mFieldProperties['agree']->addMessage("required",_MD_USER_ERROR_UNEEDAGREE);
		$this->mFieldProperties['agree']->addMessage("intRange",_MD_USER_ERROR_UNEEDAGREE);
		$this->mFieldProperties['agree']->addVar("min",1);
		$this->mFieldProperties['agree']->addVar("max",1);
	}
}

?>
