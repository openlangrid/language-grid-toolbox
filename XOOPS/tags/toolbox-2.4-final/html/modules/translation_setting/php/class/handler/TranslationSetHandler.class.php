<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to set
// translation paths.
// Copyright (C) 2009  NICT Language Grid Project
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //

//require_once(XOOPS_ROOT_PATH.'/api/class/handler/Toolbox_ObjectGenericHandler.class.php');

class TranslationSetObject extends XoopsSimpleObject {

	function TranslationSetObject() {
		$this->initVar('set_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('set_name', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('shared_flag', XOBJ_DTYPE_STRING, '0', false);

		$root =& XCube_Root::getSingleton();
		$uid = 0;
		if ($root->mContext->mXoopsUser) {
			$uid = $root->mContext->mXoopsUser->get('uid');
		}
		$this->initVar('create_user_id', XOBJ_DTYPE_INT, $uid, false);
		$this->initVar('update_user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('create_time', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
	}
}

class TranslationSetHandler extends XoopsObjectGenericHandler {

	var $mTable = 'translation_set';
	var $mPrimary = "set_id";
	var $mClass = "TranslationSetObject";

	function &getAllByUid() {
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');

		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('user_id', $uid), 'OR');
		$mc->add(new Criteria('shared_flag', '1'), 'OR');

		return parent::getObjects($mc);
	}

	function getByName($name, $userId = null) {
		if ($userId == null) {
			$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		} else {
			$uid = $userId;
		}

		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('user_id', $uid));
		$mc->add(new Criteria('set_name', $name));

		$objects =& parent::getObjects($mc);
		if ($objects != null && count($objects) > 0) {
			return $objects[0];
		}
		return null;
	}

	function getByNameWithDefualtSet($name) {
		$uid = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		$obj = $this->getByName($name, $uid);
		if ($obj == null && $uid != '1') {
			$obj = $this->getByName($name, '1');
		}
		return $obj;
	}

//	function &get($id)
//	{
//		$ret =& parent::get($id);
//
//		if ($ret != null) {
//			$ret->_loadExecs();
//		}
//
//		return $ret;
//	}


//	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
//		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);
//		foreach ($objects as $object) {
//			$object->_loadExecs();
//		}
//		return $objects;
//	}

}
?>