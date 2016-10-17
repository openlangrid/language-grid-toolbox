<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/../AbstractDao.class.php');

class TranslationPathObject extends AbstractDaoObject {

	private $_execs = null;

	function TranslationPathObject() {
		$this->initVar('path_id');
		$this->initVar('path_name');
		$this->initVar('user_id');
		$this->initVar('set_id');
		$this->initVar('source_lang');
		$this->initVar('target_lang');
		$this->initVar('revs_path_id');
		$this->initVar('create_user_id');
		$this->initVar('update_user_id');
		$this->initVar('create_time');
		$this->initVar('update_time');
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
		$adapter = DaoAdapter::getAdapter();
		//$execDao =& new TranslationExecDao(wfGetDB(DB_MASTER));
		$execDao = $adapter->getTranslationExecDao();
		$this->_execs =& $execDao->getExecObjects($this->mVar['path_id']);
	}
}


//class TranslationPathDaoImpl extends AbstractDaoComposite implements ServiceGridTranslationPathDAO {
class TranslationPathDaoImpl extends AbstractDao implements ServiceGridTranslationPathDAO {

	var $mTable = 'translation_path';
	var $mPrimary = "path_id";
	var $mClass = "TranslationPathObject";
	var $mTranslationExecDaoImpl;

	/**
	 * Construct
	 */
	function __construct($db) {
		parent::__construct($db);
		$this->mTranslationExecDaoImpl = DaoAdapter::getAdapter()->getTranslationExecDao();
	}

	public function queryByPathId($pathId) {
		$object = parent::get($pathId);
		return array($this->convertPathObject($object));
	}

	public function queryAll() {
		$wheres = array();
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	public function queryBySetId($userId, $setId, $sourceLang = null, $targetLang = null) {
		$wheres = array();
		if (isset($userId)) {
			$wheres['user_id'] = $userId;
		}
		$wheres['set_id'] = $setId;
		if ($sourceLang) {
			$wheres['source_lang'] = $sourceLang;
		}
		if ($targetLang) {
			$wheres['target_lang'] = $targetLang;
		}
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	public function getTranslationExecs($translationPathObj) {
		return $this->mTranslationExecDaoImpl->queryByPathId($translationPathObj->getPathId());
	}

	public function insert($newPath) {
		//$newPath = $this->convertPathObject($newPath);

		$data = (array)$newPath->getVars();

		$data['create_time'] = time();
		$data['create_user_id'] = $newPath->get('user_id');

		$pathId = parent::insert($data, true);
		$newPath->set('path_id', $pathId);

		return $pathId;
	}

	public function update($object) {
		$data = (array)$object->getVars();
		return parent::update($data);
	}

	public function delete($object) {
		$data = (array)$object->getVars();
		return parent::delete($data);
	}
	
	public function deleteBySetId($setId) {
		$wheres = array();
		$wheres['set_id'] = $setId;
		$objects = parent::search($wheres);
		if (is_array($objects)) {
			foreach ($objects as $object) {
				$result = $this->mTranslationExecDaoImpl->deleteByPathId($object->get('path_id'));
				$this->delete($object);
			}
		}
		return true;
	}

	/**
	 *
	 */
	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertPathObject($object);
		}
		return $contents;
	}

	private function convertTranslationPath($pathObject) {
		$translationPathObject = new TranslationPathObject();

		$translationPathObject->set('path_id', $pathObject->getPathId());
		$translationPathObject->set('path_name', $pathObject->getPathName());
		$translationPathObject->set('set_id', $pathObject->getSetId());
		$translationPathObject->set('source_lang', $pathObject->getSourceLang());
		$translationPathObject->set('target_lang', $pathObject->getTargetLang());
		$translationPathObject->set('revs_path_id', $pathObject->getRevsPathId());
		$translationPathObject->set('create_user_id', $pathObject->getCreateUserId());
		$translationPathObject->set('update_user_id', $pathObject->getUpdateUserId());
		$translationPathObject->set('create_time', $pathObject->getCreateTime());
		$translationPathObject->set('update_time', $pathObject->getUpdateTime());

		return $translationPathObject;
	}

	/**
	 *
	 */
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
