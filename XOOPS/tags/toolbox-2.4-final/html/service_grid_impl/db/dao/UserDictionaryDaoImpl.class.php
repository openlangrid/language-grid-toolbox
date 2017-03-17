<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
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

require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridUserDictionaryDAO.interface.php');
require_once(dirname(__FILE__).'/UserDictionaryContentsDaoImpl.class.php');

class UserDictionaryXoopsObject extends XoopsSimpleObject {

	function UserDictionaryXoopsObject() {
		$this->initVar('user_dictionary_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('type_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('dictionary_name', XOBJ_DTYPE_STRING, true, 255);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('update_date', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('deploy_flag', XOBJ_DTYPE_STRING, '0', true, 1);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
		$this->initVar('last_update_user', XOBJ_DTYPE_STRING, '0', false);
	}

	function _loadContents() {
		$dao =& new UserDictionaryContentsDaoImpl($GLOBALS['xoopsDB']);
	}
}

class UserDictionaryDaoImpl extends XoopsObjectGenericHandler implements ServiceGridUserDictionaryDAO {

	var $mTable = "user_dictionary";
	var $mPrimary = "user_dictionary_id";
	var $mClass = "UserDictionaryXoopsObject";

	function &get($id, $hasContents = false) {
		$ret =& parent::get($id);
		if ($hasContents == true && $ret != null) {
			$ret->_loadContents();
		}
		return $ret;
	}

	function insert($obj) {
		if ($obj->isNew() == false) {
			if ($this->update($obj)) {
				return $obj;
			}
		} else {
			$data = (array)$obj->getVars();
			$data['create_date'] = time();
			$id = parent::insert($data, true);
			$obj->set('user_dictionary_id', $id);
			return $obj;
		}
	}
	function update($object) {
		$data = (array)$object->getVars();
		return parent::update($data);
	}

	function delete($object) {
		$data = (array)$object->getVars();

		return parent::delete($data);
	}
	public function getUserDictionaryIdByName($dictName) {
		$mCriteriaComp =& new CriteriaCompo();
		$mCriteriaComp->add(new Criteria('dictionary_name', $dictName));
		$mCriteriaComp->add(new Criteria('delete_flag', '0'));
		$objects =& parent::getObjects($mCriteriaComp);
		if ($objects == null || count($objects) == 0) {
			return null;
		}
		return $objects[0]->get('user_dictionary_id');
	}
}
?>