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

class TranslationBindObject extends AbstractDaoObject {

	function TranslationBindObject() {
		$this->initVar('path_id');
		$this->initVar('exec_id');
		$this->initVar('bind_id');
		$this->initVar('bind_type');
		$this->initVar('bind_value');
		$this->initVar('create_user_id');
		$this->initVar('update_user_id');
		$this->initVar('create_time');
		$this->initVar('update_time');
	}
}

class TranslationBindDaoImpl extends AbstractDaoComposite implements ServiceGridTranslationBindDAO {

	var $mTable = 'translation_bind';
	var $mPrimary = "path_id";
	var $mPrimaryAry = array("path_id", "exec_id", "bind_id");
	var $mClass = "TranslationBindObject";

	/**
	 * Get all records from table
	 */
	public function queryAll() {
		$wheres = array();
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	public function insert($newBind, $isNew) {
		$data = (array)$newBind->getVars();
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
	 *
	 */
	public function queryByExecObject($translationExecObj) {
		$wheres = array();
		$wheres['path_id'] = $translationExecObj->getPathId();
		$wheres['exec_id'] = $translationExecObj->getExecId();
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	public function getBindObjects($pathId, $execId) {
		$params = array();
		$params['path_id'] = $pathId;
		$params['exec_id'] = $execId;

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
		foreach($objects as $object) {
			$contents[] = $this->convertBindObjects($object);
		}
		return $contents;
	}

	/**
	 * MediaWikiオブジェクトから、VOに変換する。
	 * @param unknown_type $translationBindObjects
	 * @return Ambigous <ServiceGridTranslationBind, multitype:>
	 */
	private function convertBindObjects($translationBindObject) {
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
		return $translationBind;

	}

}
?>
