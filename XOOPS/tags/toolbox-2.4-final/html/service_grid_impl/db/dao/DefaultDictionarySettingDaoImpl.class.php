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

class DefaultDictionarySettingXoopsObject extends XoopsSimpleObject {
	private $_binds = null;

	function DefaultDictionarySettingXoopsObject() {
		$this->initVar('setting_id', XOBJ_DTYPE_INT, 0, false);
		//$this->initVar('tool_type', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('set_id', XOBJ_DTYPE_INT, 0, false);

		$root =& XCube_Root::getSingleton();
		$uid = 0;
		if ($root->mContext->mXoopsUser) {
			$uid = $root->mContext->mXoopsUser->get('uid');
		}
		$this->initVar('user_id', XOBJ_DTYPE_INT, $uid, false);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('edit_date', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', false);
	}

//	function getBinds() {
//		if ($this->_binds == null) {
//			$this->_loadBinds();
//		}
//		return $this->_binds;
//	}
//	function setBinds($binds) {
//		$this->_binds = $binds;
//	}
//	function _loadBinds() {
//		require_once(dirname(__FILE__).'/DefaultDictionaryBindHandler.class.php');
//		$handler =& new DefaultDictionaryBindHandler($GLOBALS['xoopsDB']);
//		$this->_binds =& $handler->getBindObjects($this->get('setting_id'));
//	}
}

require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridDefaultDictionarySettingDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridDefaultDictionarySetting.class.php');

class DefaultDictionarySettingDaoImpl extends XoopsObjectGenericHandler implements ServiceGridDefaultDictionarySettingDao{

	var $mTable = 'default_dictionary_setting';
	var $mPrimary = "setting_id";
	var $mClass = "DefaultDictionarySettingXoopsObject";

//	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
//		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);
//		foreach ($objects as $object) {
//			$object->_loadBinds();
//		}
//		return $objects;
//	}

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return ServiceGridDefaultDictionarySetting
	 */
	public function queryBySettingId($settingId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('setting_id', $settingId));
		$c->add(new Criteria('delete_flag', '0'));
		$objects = parent::getObjects($c);
		return $this->objects2objects($objects);
	}

	function queryAll() {
		return 'implements test!!';
	}

	public function queryBySetIdUserId($setId, $userId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('set_id', $setId));
		$c->add(new Criteria('user_id', $userId));
		$c->add(new Criteria('delete_flag', '0'));
		$objects = parent::getObjects($c);
		return $this->objects2objects($objects);
	}

	public function insert($setId, $userId, $settingId = null) {
		$obj = $this->create(true);
		$obj->set('set_id', $setId);
		$obj->set('user_id', $userId);
		if ($settingId) {
			$obj->set('setting_id', $settingId);
		}
		if (parent::insert($obj, true)) {
			return $this->convertX2D($obj);
		}
		return null;
	}

	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertX2D($object);
		}
		return $contents;
	}

	private function convertX2D($settingXoopsObject) {
		$defaultDictionarySetting = new ServiceGridDefaultDictionarySetting();
		$defaultDictionarySetting->setSettingId($settingXoopsObject->get('setting_id'));
		$defaultDictionarySetting->setSetId($settingXoopsObject->get('set_id'));
		$defaultDictionarySetting->setUserId($settingXoopsObject->get('user_id'));
		$defaultDictionarySetting->setCreateDate($settingXoopsObject->get('create_date'));
		$defaultDictionarySetting->setEditDate($settingXoopsObject->get('edit_date'));
		$defaultDictionarySetting->setDeleteFlag($settingXoopsObject->get('delete_flag'));
		return $defaultDictionarySetting;
	}
}
?>