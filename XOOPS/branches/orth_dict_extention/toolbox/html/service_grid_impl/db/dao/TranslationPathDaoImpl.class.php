<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to set
// translation paths.
// Copyright (C) 2009-2010  NICT Language Grid Project
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

class TranslationPathXoopsObject extends XoopsSimpleObject {

	private $_execs = null;

	function TranslationPathXoopsObject() {
		$this->initVar('path_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('path_name', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('set_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('source_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('target_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('revs_path_id', XOBJ_DTYPE_INT, 0, false);

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

	function getExecs() {
		if ($this->_execs == null) {
			$this->_loadExecs();
		}
		return $this->_execs;
	}
	function setExecs($execs) {
		$this->_execs = $execs;
	}
	function _loadExecs() {
		require_once(dirname(__FILE__).'/TranslationExecHandler.class.php');
		$handler = new TranslationExecHandler($GLOBALS['xoopsDB']);
		$this->_execs =& $handler->getExecObjects($this->get('path_id'));
	}
}

require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridTranslationPathDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridTranslationPath.class.php');
require_once(dirname(__FILE__).'/TranslationExecDaoImpl.class.php');
class TranslationPathDaoImpl extends XoopsObjectGenericHandler implements ServiceGridTranslationPathDAO {

	var $mTable = 'translation_path';
	var $mPrimary = "path_id";
	var $mClass = "TranslationPathXoopsObject";
	var $_translationExecDaoImpl;
	/**
	 * Construct
	 */
	function __construct($db) {
		parent::__construct($db);
		$this->_translationExecDaoImpl = new TranslationExecDaoImpl($db);
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

	function queryAll() {
		return "implements test!!";
	}

	/**
	 * UserIdとSetIdからオブジェクトを取得する。
	 * (non-PHPdoc)
	 * @see html/service_grid/db/dao/ServiceGridTranslationPathDAO#queryBySetId($userId, $setId)
	 */
	function queryBySetId($userId, $setId, $sourceLang = null, $targetLang = null) {

		$mCriteriaComp = new CriteriaCompo();
		$mCriteriaComp->add(new Criteria('user_id', $userId));
		$mCriteriaComp->add(new Criteria('set_id', $setId));
		if (!empty($sourceLang)) {
			$mCriteriaComp->add(new Criteria('source_lang', $sourceLang));
		}
		if (!empty($targetLang)) {
			$mCriteriaComp->add(new Criteria('target_lang', $targetLang));
		}

		$objects =& parent::getObjects($mCriteriaComp);

		if ($objects == null || count($objects) == 0) {
			return null;
		}
		$results = $this->convertPathObjects($objects);
//		foreach ($results as $result) {
//			$this->getTranslationExecs($result);
//		}
		return $results;
	}
	/**
	 * Pathテーブルに紐付くExecレコードを返します。
	 * @param unknown_type $translationPathObject ServiceGridTranslationPath
	 */
	public function getTranslationExecs($translationPathObj) {
		$translationExecs =
			$this->_translationExecDaoImpl->queryByPathId($translationPathObj->getPathId());
		$translationPathObj->setTranslationExecs($translationExecs);
		return $translationExecs;
	}

	public function queryByPathId($pathId) {
		$mCriteriaComp = new CriteriaCompo();
		$mCriteriaComp->add(new Criteria('path_id', $pathId));
		$objects =& parent::getObjects($mCriteriaComp);

		if ($objects == null || count($objects) == 0) {
			return null;
		}
		$results = $this->convertPathObjects($objects);
		return $results[0];
	}

	public function insert($translationPathObj) {
		$translationPath =& $this->create(true);
		$translationPath->set('user_id', $translationPathObj->getUserId());
		$translationPath->set('set_id', $translationPathObj->getSetId());
		$translationPath->set('source_lang', $translationPathObj->getSourceLang());
		$translationPath->set('target_lang', $translationPathObj->getTargetLang());
		if ($translationPathObj->getPathName() != null) {
			$translationPath->set('path_name', $translationPathObj->getPathName());
		}
		if ($translationPathObj->getRevsPathId() != null) {
			$translationPath->set('revs_path_id', $translationPathObj->getRevsPathId());
		}
//		if ($this->m_pathHandler->insert($translationPath, true)) {
//			return $translationPath;
//		} else {
//			die('SQL Error.'.__FILE__.'('.__LINE__.')');
//		}
		if (parent::insert($translationPath, true)) {
			return $this->convertPathObject($translationPath);
		}
		return false;
	}

	/**
	 * update
	 */
	public function update($pathId, $translationPathObj) {
		$obj = parent::get($pathId);
		if ($obj == null) {
			return $this->insert($translationPathObj);
		}
		$obj->set('user_id', $translationPathObj->getUserId());
		$obj->set('set_id', $translationPathObj->getSetId());
		$obj->set('source_lang', $translationPathObj->getSourceLang());
		$obj->set('target_lang', $translationPathObj->getTargetLang());
		if ($translationPathObj->getPathName()) {
			$obj->set('path_name', $translationPathObj->getPathName());
		}
		$revsPathId = $translationPathObj->getRevsPathId();
		if (isset($revsPathId)) {
			$oldRevsPathId = $obj->get('revs_path_id');
			if (isset($oldRevsPathId) && $oldRevsPathId != 0 && $oldRevsPathId != $revsPathId) {
				$this->delete($oldRevsPathId);
			}
			$obj->set('revs_path_id', $revsPathId);
		}
		$obj->set('update_user_id', DaoAdapter::getAdapter()->getUserId());
		$obj->set('update_time', time());

		if (parent::insert($obj, true)) {
			return $this->convertPathObject($obj);
		}
		return false;
	}

	/**
	 * 主キーを指定して(物理)削除
	 */
	public function delete($pathId) {
		$obj = parent::get($pathId);
		if ($obj) {
			return parent::delete($obj, true);
		}
		return false;
	}

	/**
	 * XoopsオブジェクトからVOに変換する。
	 * @param unknown_type $translationPathObjects
	 */
	private function convertPathObjects($translationPathObjects) {
		$translationPaths = array();
		foreach ($translationPathObjects as $translationPathObject) {
			$translationPaths[] = $this->convertPathObject($translationPathObject);
		}
		return $translationPaths;
	}

	public function deleteBySetId($setId) {
		return $this->delete($setId);
	}


	private function convertPathObject($translationPathObject) {
		$translationPath = new ServiceGridTranslationPath();
		$translationPath->setPathId($translationPathObject->get('path_id'));
		$translationPath->setPathName($translationPathObject->get('path_name'));
		$translationPath->setUserId($translationPathObject->get('user_id'));
		$translationPath->setSetId($translationPathObject->get('set_id'));
		$translationPath->setSourceLang($translationPathObject->get('source_lang'));
		$translationPath->setTargetLang($translationPathObject->get('target_lang'));
		$translationPath->setRevsPathId($translationPathObject->get('revs_path_id'));
		$translationPath->setCreateUserId($translationPathObject->get('create_user_id'));
		$translationPath->setUpdateUserId($translationPathObject->get('update_user_id'));
		$translationPath->setCreateTime($translationPathObject->get('create_time'));
		$translationPath->setUpdateTime($translationPathObject->get('update_time'));
		return $translationPath;
	}
}
?>
