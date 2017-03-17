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

class TranslationExecXoopsObject extends XoopsSimpleObject {

	private $_binds = null;

	function TranslationExecXoopsObject() {
		$this->initVar('path_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('exec_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('exec_order', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('source_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('target_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_id', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('dictionary_flag', XOBJ_DTYPE_INT, 0, false);

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

	function getBinds() {
		if ($this->_binds == null) {
			$this->_loadBinds();
		}
		return $this->_binds;
	}
	function setBinds($binds) {
		$this->_binds = $binds;
	}
	function _loadBinds() {
		require_once(dirname(__FILE__).'/TranslationBindHandler.class.php');
		$handler =& new TranslationBindHandler($GLOBALS['xoopsDB']);
		$this->_binds =& $handler->getBindObjects($this->get('path_id'), $this->get('exec_id'));
	}
}

require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridTranslationExecDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridTranslationExec.class.php');
require_once(dirname(__FILE__).'/TranslationBindDaoImpl.class.php');
class TranslationExecDaoImpl extends Toolbox_CompositeKeyGenericHandler implements ServiceGridTranslationExecDAO {

	var $mTable = 'translation_exec';
	var $mPrimary = "path_id";
	var $mPrimaryAry = array("path_id", "exec_id");
	var $mClass = "TranslationExecXoopsObject";

	var $_translationBindDaoImpl;
	/**
	 * Construct
	 */
	function __construct($db) {
		parent::__construct($db);
		$this->_translationBindDaoImpl = new TranslationBindDaoImpl($db);
	}

	function getTranslationExec($pathId) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('path_id', $pathId));
		$criteria->addSort('exec_order');

		$objects =& parent::getObjects($criteria);

		$translationExecObjs = array();
		return $objects;
	}

	function queryAll() {
		return "implements test!!";
	}

	/**
	 * Path IDをキーにレコードを取得する。
	 * @param unknown_type $pathId
	 */
	function queryByPathId($pathId) {
		$execs = $this->getTranslationExec($pathId);
		$results = $this->convertExecObjects($execs);
		return $results;
	}

	public function getTranslationBinds($translationExecObj) {
		$translationBinds =
			$this->_translationBindDaoImpl->queryByExecObject($translationExecObj);
		$translationExecObj->setTranslationBinds($translationBinds);
		return $translationBinds;
	}

	public function insert($translationExecObj) {
		$obj = parent::create(true);
		$obj->set('path_id', $translationExecObj->getPathId());
		$obj->set('exec_id', $translationExecObj->getExecId());
		$obj->set('exec_order', $translationExecObj->getExecOrder());
		$obj->set('source_lang', $translationExecObj->getSourceLang());
		$obj->set('target_lang', $translationExecObj->getTargetLang());
		$obj->set('service_type', $translationExecObj->getServiceType());
		$obj->set('service_id', $translationExecObj->getServiceId());
		$obj->set('dictionary_flag', $translationExecObj->getDictionaryFlag());
		$obj->set('create_user_id', DaoAdapter::getAdapter()->getUserId());
		$obj->set('create_time', time());
		return parent::insert($obj, true);
	}

	public function update($pathId, $execId, $translationExecObj) {
		$obj = parent::get(array('path_id'=>$pathId, 'exec_id'=>$execId));
		if ($obj == null) {
			return $this->insert($translationExecObj);
		}
		$obj->set('exec_order', $translationExecObj->getExecOrder());
		$obj->set('source_lang', $translationExecObj->getSourceLang());
		$obj->set('target_lang', $translationExecObj->getTargetLang());
		$obj->set('service_type', $translationExecObj->getServiceType());
		$obj->set('service_id', $translationExecObj->getServiceId());
		$obj->set('dictionary_flag', $translationExecObj->getDictionaryFlag());
		$obj->set('update_user_id', DaoAdapter::getAdapter()->getUserId());
		$obj->set('update_time', time());
		return parent::insert($obj, true);
	}

	public function delete($pathId, $execId) {
		$obj = parent::get(array('path_id'=>$pathId, 'exec_id'=>$execId));
		if ($obj) {
			return parent::delete($obj, true);
		}
		return false;
	}

	public function getExecObjects($pathId) {
		$execs = $this->getTranslationExec($pathId);
		return $execs;
	}

	public function deleteByPathId($pathId) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('path_id', $pathId));
		return parent::deleteAll($criteria, true);
	}



	/**
	 * Xoopsオブジェクトから、VOに変換する。
	 * @param unknown_type $translationExecObjects
	 * @return Ambigous <ServiceGridTranslationExec, multitype:>
	 */
	private function convertExecObjects($translationExecObjects) {
		$translationExecs = array();
		foreach ($translationExecObjects as $translationExecObject) {
			$translationExec = new ServiceGridTranslationExec();
			$translationExec->setPathId($translationExecObject->get('path_id'));
			$translationExec->setExecId($translationExecObject->get('exec_id'));
			$translationExec->setExecOrder($translationExecObject->get('exec_order'));
			$translationExec->setSourceLang($translationExecObject->get('source_lang'));
			$translationExec->setTargetLang($translationExecObject->get('target_lang'));
			$translationExec->setServiceType($translationExecObject->get('service_type'));
			$translationExec->setServiceId($translationExecObject->get('service_id'));
			$translationExec->setDictionaryFlag($translationExecObject->get('dictionary_flag'));
			$translationExec->setCreateUserId($translationExecObject->get('create_user_id'));
			$translationExec->setUpdateUserId($translationExecObject->get('update_user_id'));
			$translationExec->setCreateTime($translationExecObject->get('create_time'));
			$translationExec->setUpdateTime($translationExecObject->get('update_time'));
			$translationExecs[] = $translationExec;
		}
		return $translationExecs;

	}
}
?>