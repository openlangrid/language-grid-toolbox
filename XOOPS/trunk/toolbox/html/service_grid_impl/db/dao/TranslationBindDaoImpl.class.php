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

class TranslationBindXoopsObject extends XoopsSimpleObject {

	function TranslationBindXoopsObject() {
		$this->initVar('path_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('exec_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('bind_value', XOBJ_DTYPE_STRING, '', false);

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
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridTranslationBindDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridTranslationBind.class.php');
class TranslationBindDaoImpl extends Toolbox_CompositeKeyGenericHandler implements ServiceGridTranslationBindDAO {

	var $mTable = 'translation_bind';
	var $mPrimary = "path_id";
	var $mPrimaryAry = array("path_id", "exec_id", "bind_id");
	var $mClass = "TranslationBindXoopsObject";

	function getBindObjects($pathId, $execId) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('path_id', $pathId));
		$criteria->add(new Criteria('exec_id', $execId));
		$objects = parent::getObjects($criteria);
		if ($objects == null) {
			return array();
		}
		return $objects;
	}

	function queryAll() {
		return "implements test!!";
	}

	/**
	 * Execオブジェクトから、bindレコードを取得する。
	 * @param ServiceGridTranslationExec $execObj ServiceGridTranslationExec
	 */
	public function queryByExecObject($execObj) {
		$binds = $this->getBindObjects($execObj->getPathId(), $execObj->getExecId());
		$results = $this->convertBindObjects($binds);
		return $results;
	}

	public function insert($translationBindObj) {
		$obj = parent::create(true);
		$obj->set('path_id', $translationBindObj->getPathId());
		$obj->set('exec_id', $translationBindObj->getExecId());
		$obj->set('bind_id', $translationBindObj->getBindId());
		$obj->set('bind_type', $translationBindObj->getBindType());
		$obj->set('bind_value', $translationBindObj->getBindValue());
		$obj->set('create_user_id', DaoAdapter::getAdapter()->getUserId());
		$obj->set('create_time', time());
		return parent::insert($obj, true);
	}

	public function update($pathId, $execId, $bindId, $translationBindObj) {
		$obj = parent::get(array('path_id'=>$pathId, 'exec_id'=>$execId, 'bind_id'=>$bindId));
		if ($obj == null) {
			return $this->insert($translationBindObj);
		}
		$obj->set('bind_type', $translationBindObj->getBindType());
		$obj->set('bind_value', $translationBindObj->getBindValue());
		$obj->set('update_user_id', DaoAdapter::getAdapter()->getUserId());
		$obj->set('update_time', time());
		return parent::insert($obj, true);
	}

	public function delete($pathId, $execId, $bindId) {
		$obj = parent::get(array('path_id'=>$pathId, 'exec_id'=>$execId, 'bind_id'=>$bindId));
		if ($obj) {
			return parent::delete($obj);
		}
		return false;
	}

	public function deleteByPathId($pathId) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('path_id', $pathId));
		return parent::deleteAll($criteria, true);
	}


	/**
	 * Xoopsオブジェクトから、VOに変換する。
	 * @param unknown_type $translationBindObjects
	 * @return Ambigous <ServiceGridTranslationBind, multitype:>
	 */
	private function convertBindObjects($translationBindObjects) {
		$translationBinds = array();
		foreach ($translationBindObjects as $translationBindObject) {
			$translationBind = new ServiceGridTranslationBind();
			$translationBind->setPathId($translationBindObject->get('path_id'));
			$translationBind->setExecId($translationBindObject->get('exec_id'));
			$translationBind->setBindId($translationBindObject->get('bind_id'));
			$translationBind->setBindType($translationBindObject->get('bind_type'));
			$translationBind->setBindValue($translationBindObject->get('bind_value'));
			$translationBind->setCreateUserId($translationBindObject->get('create_user_id'));
			$translationBind->setUpdateUserId($translationBindObject->get('update_user_id'));
			$translationBind->setCreateTime($translationBindObject->get('create_time'));
			$translationBind->setUpdateTime($translationBindObject->get('update_time'));
			$translationBinds[] = $translationBind;
		}
		return $translationBinds;

	}

}
?>