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

require_once(XOOPS_ROOT_PATH.'/api/class/handler/Toolbox_CompositeKeyGenericHandler.class.php');

class DefaultDictionaryBindXoopsObject extends XoopsSimpleObject  {

	function DefaultDictionaryBindXoopsObject() {

		$this->initVar('setting_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('bind_value', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('edit_date', XOBJ_DTYPE_INT, 0 ,true);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', false);
	}
}

require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridDefaultDictionaryBindDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridDefaultDictionaryBind.class.php');

class DefaultDictionaryBindDaoImpl extends Toolbox_CompositeKeyGenericHandler implements ServiceGridDefaultDictionaryBindDAO {
//class DefaultDictionaryBindDaoImpl implements ServiceGridDefaultDictionaryBindDAO {

	var $mTable = 'default_dictionary_bind';
	var $mPrimary = "setting_id";
	var $mPrimaryAry = array("setting_id", "bind_id");
	var $mClass = "DefaultDictionaryBindXoopsObject";
//	function getBindObjects($settingId) {
//		$criteria =& new CriteriaCompo();
//		$criteria->add(new Criteria('setting_id', $settingId));
//		return parent::getObjects($criteria);
//	}

	public function queryAll() {
		return 'implements test!!';
	}

	public function queryBySettingId($settingId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('setting_id', $settingId));
		$c->add(new Criteria('delete_flag', '0'));
		$objects = parent::getObjects($c);
		return $this->objects2objects($objects);
	}

	public function insert($settingId, $bindId, $bindType, $bindValue) {
		$obj = $this->create(true);
		$obj->set('setting_id', $settingId);
		$obj->set('bind_id', $bindId);
		$obj->set('bind_type', $bindType);
		$obj->set('bind_value', $bindValue);
		if (parent::insert($obj, true)) {
			return $this->convertX2D($obj);
		}
		return null;
	}

	public function deleteBySettingId($settingId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('setting_id', $settingId));
		return (parent::deleteAll($c, true));
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

	private function convertX2D($bindXoopsObject) {
		$defaultDictionaryBind = new ServiceGridDefaultDictionaryBind();
		$defaultDictionaryBind->setSettingId($bindXoopsObject->get('setting_id'));
		$defaultDictionaryBind->setBindId($bindXoopsObject->get('bind_id'));
		$defaultDictionaryBind->setBindType($bindXoopsObject->get('bind_type'));
		$defaultDictionaryBind->setBindValue($bindXoopsObject->get('bind_value'));
		$defaultDictionaryBind->setCreateDate($bindXoopsObject->get('create_date'));
		$defaultDictionaryBind->setEditDate($bindXoopsObject->get('edit_date'));
		$defaultDictionaryBind->setDeleteFlag($bindXoopsObject->get('delete_flag'));
		return $defaultDictionaryBind;
	}

}
?>