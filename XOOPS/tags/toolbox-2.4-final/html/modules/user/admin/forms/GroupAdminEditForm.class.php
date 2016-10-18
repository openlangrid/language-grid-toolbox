<?php
/**
 * @package user
 * @version $Id: GroupAdminEditForm.class.php,v 1.1 2007/05/15 02:34:39 minahito Exp $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_ROOT_PATH . "/core/XCube_ActionForm.class.php";
require_once XOOPS_MODULE_PATH . "/legacy/class/Legacy_Validator.class.php";

class User_GroupAdminEditForm extends XCube_ActionForm
{
	function getTokenName()
	{
		return "module.user.GroupAdminEditForm.TOKEN" . $this->get('groupid');
	}

	function prepare()
	{
		//
		// Set form properties
		//
		$this->mFormProperties['groupid'] =& new XCube_IntProperty('groupid');
		$this->mFormProperties['name'] =& new XCube_StringProperty('name');
		$this->mFormProperties['description'] =& new XCube_TextProperty('description');

//		$this->mFormProperties['group_type'] =& new XCube_StringProperty('group_type');
//		$this->mFormProperties['lg_user'] =& new XCube_StringProperty('lg_user');
//		$this->mFormProperties['lg_passwd'] =& new XCube_StringProperty('lg_passwd');

		//
		// Set field properties
		//
		$this->mFieldProperties['groupid'] =& new XCube_FieldProperty($this);
		$this->mFieldProperties['groupid']->setDependsByArray(array('required'));
		$this->mFieldProperties['groupid']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_GROUPID);

		$this->mFieldProperties['name'] =& new XCube_FieldProperty($this);
		$this->mFieldProperties['name']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['name']->addMessage('required', _MD_USER_ERROR_REQUIRED, _AD_USER_LANG_GROUP_NAME, '50');
		$this->mFieldProperties['name']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _AD_USER_LANG_GROUP_NAME, '50');
		$this->mFieldProperties['name']->addVar('maxlength', '50');

//		$this->mFieldProperties['group_type'] =& new XCube_FieldProperty($this);
//		$this->mFieldProperties['group_type']->setDependsByArray(array('required'));
//		$this->mFieldProperties['group_type']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_GROUPID);

//		$this->mFieldProperties['lg_user'] =& new XCube_FieldProperty($this);
//		$this->mFieldProperties['lg_user']->setDependsByArray(array('required'));
//		$this->mFieldProperties['lg_user']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_GROUPID);

//		$this->mFieldProperties['lg_passwd'] =& new XCube_FieldProperty($this);
//		$this->mFieldProperties['lg_passwd']->setDependsByArray(array('required'));
//		$this->mFieldProperties['lg_passwd']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_GROUPID);
	}

//	function validateName()
//	{
//		if (strlen($this->get('name')) > 0) {
//			//
//			// group id unique check
//			//
//			$handler=&xoops_gethandler('group');
//			$criteria =& new CriteriaCompo(new Criteria('name', $this->get('name')));
//			if ($this->get('groupid') > 0) {
//				$criteria->add(new Criteria('groupid', $this->get('groupid'), '<>'));
//			}
//			$objs = $handler->getObjects($criteria);
//			if (!empty($objs) && $objs > 0) {
//				$this->addErrorMessage(_MD_USER_ERROR_GROUP_ID_TAKEN);
//			}
//		}
//	}

	function load(&$obj)
	{
		$this->set('groupid', $obj->get('groupid'));
		$this->set('name', $obj->get('name'));
		$this->set('description', $obj->get('description'));
//		$this->set('group_type', $obj->get('group_type'));
//		$this->set('lg_user', $obj->get('lg_user'));
//		$this->set('lg_passwd', $obj->get('lg_passwd'));

	}

	function update(&$obj)
	{
		$obj->set('groupid', $this->get('groupid'));
		$obj->set('name', $this->get('name'));
		$obj->set('description', $this->get('description'));
//		$obj->set('group_type', $this->get('group_type'));
//		$obj->set('lg_user', $this->get('lg_user'));
//		$obj->set('lg_passwd', $this->get('lg_passwd'));
	}
}

?>
