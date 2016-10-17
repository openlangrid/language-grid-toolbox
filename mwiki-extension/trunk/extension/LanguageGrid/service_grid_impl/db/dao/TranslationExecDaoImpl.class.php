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

class TranslationExecObject extends AbstractDaoObject {

	private $_binds = null;

	function TranslationExecObject() {
		$this->initVar('path_id');
		$this->initVar('exec_id');
		$this->initVar('exec_order');
		$this->initVar('source_lang');
		$this->initVar('target_lang');
		$this->initVar('service_type');
		$this->initVar('service_id');
		$this->initVar('dictionary_flag');
		$this->initVar('create_user_id');
		$this->initVar('update_user_id');
		$this->initVar('create_time');
		$this->initVar('update_time');
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
		$adapter = DaoAdapter::getAdapter();
		$bindDao = $adapter->getTranslationBindDao();
		$this->_binds =& $bindDao->getBindObjects($this->mVar['path_id'], $this->mVar['exec_id']);
	}
}


class TranslationExecDaoImpl extends AbstractDaoComposite implements ServiceGridTranslationExecDAO {

	var $mTable = 'translation_exec';
	var $mPrimary = "path_id";
	var $mPrimaryAry = array("path_id", "exec_id");
	var $mClass = "TranslationExecObject";

	var $mTranslationBindDaoImpl;

	/**
	 * Construct
	 */
	function __construct($db) {
		parent::__construct($db);
		$this->mTranslationBindDaoImpl = DaoAdapter::getAdapter()->getTranslationBindDao();
	}

	/**
	 * Get all records from table
	 */
	public function queryAll() {
		$wheres = array();
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	public function insert($newExec, $isNew = false) {
		$data = (array)$newExec->getVars();
		$data['create_time'] = time();
		parent::insert($data, $isNew);
		return true;
	}

	public function update($object) {
		$data = (array)$object->getVars();
		parent::update($data);
		return true;
	}

	public function delete($object) {
		$data = (array)$object->getVars();
		parent::delete($data);
		return true;
	}

	public function deleteByPathId($pathId) {
		$result = $this->mTranslationBindDaoImpl->deleteByPathId($pathId);
		$wheres = array();
		$wheres['path_id'] = $pathId;
		$objects = parent::search($wheres);
		if (is_array($objects)) {
			foreach ($objects as $object) {
				$this->delete($object);
			}
		}
		return true;
	}
	/**
	 * Path IDをキーにレコードを取得する。
	 * @param Integer path_id Path ID
	 */
	public function queryByPathId($pathId) {
		$wheres = array();
		$wheres['path_id'] = $pathId;
		$objects = parent::search($wheres);
		if ($objects == null || is_array($objects) === false) {
			throw new Exception("Translation exec objects is not found, search parameters is $pathId");
		}
		return $this->objects2objects($objects);
	}

	/**
	 *
	 */
	public function getTranslationBinds($translationExecObj) {
		return $this->mTranslationBindDaoImpl->queryByExecObject($translationExecObj);
	}

	public function getExecObjects($pathId) {
		$params = array();
		$params['path_id'] = $pathId;

		$objects =& $this->search($params);

		return $objects;
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
			$contents[] = $this->convertExecObjects($object);
		}
		return $contents;
	}

	/**
	 * MediaWikiオブジェクトから、VOに変換する。
	 * @param unknown_type $translationExecObjects
	 * @return Ambigous <ServiceGridTranslationExec, multitype:>
	 */
	private function convertExecObjects($translationExecObject) {
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
		return $translationExec;
	}
}
?>
