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

class TranslationSetObject extends AbstractDaoObject {

	function TranslationSetObject() {
		$this->initVar('set_id');
		$this->initVar('set_name');
		$this->initVar('user_id');
		$this->initVar('shared_flag');
		$this->initVar('create_user_id');
		$this->initVar('update_user_id');
		$this->initVar('create_time');
		$this->initVar('update_time');
	}
}


class TranslationSetDaoImpl extends AbstractDao implements ServiceGridTranslationSetDAO {

	private $mTranslationPathDaoImpl;

	var $mTable = 'translation_set';
	var $mPrimary = "set_id";
	var $mClass = "TranslationSetObject";

	/**
	 * Construct
	 */
	function __construct($db) {
		parent::__construct($db);
		$this->mTranslationPathDaoImpl = DaoAdapter::getAdapter()->getTranslationPathDao();
	}

	public function queryBySetId($setId) {
		$object = parent::get($setId);
		return array($this->convertSetObject($object));
	}

	/**
	 * SetName、UserIDからレコードを取得する。
	 * @param String $name Set Name
	 * @param Integer $userId User ID
	 */
	public function queryBySetName($name, $userId = null) {
		$wheres = array();
		$wheres['set_name'] = $name;
		if ($userId != null) {
			$wheres['user_id'] = $userId;
		}
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	/**
	 * UserIDからレコードを取得する。
	 * @param Integer $userId User ID
	 */
	public function queryByUserId($userId) {
		$wheres = array();
		$wheres['user_id'] = $userId;
		$objects = parent::search($wheres);

		return $this->objects2objects($objects);
	}

	/**
	 * TranslationPathの配列を返す。
	 */
	public function getTranslationPaths($translationSetObj) {
		return $this->mTranslationPathDaoImpl->queryBySetId($translationSetObj->getUserId(), $translationSetObj->getSetId());
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
			$contents[] = $this->convertSetObject($object);
		}
		return $contents;
	}

	/**
	 * オブジェクトからVOに変換する。
	 * @param unknown_type $translationSetObj
	 * @return ServiceGridTranslationSet
	 */
	private function convertSetObject($translationSetObj) {
		$translationSet = new ServiceGridTranslationSet();
//		print_r($translationSetObj); die();
		$translationSet->setSetId($translationSetObj->get('set_id'));
		$translationSet->setSetName($translationSetObj->get('set_name'));
		$translationSet->setUserId($translationSetObj->get('user_id'));
		$translationSet->setSharedFlag($translationSetObj->get('shared_flag'));
		$translationSet->setCreateUserId($translationSetObj->get('create_user_id'));
		$translationSet->setUpdateUserId($translationSetObj->get('update_user_id'));
		$translationSet->setCreateTime($translationSetObj->get('create_time'));
		$translationSet->setUpdateTime($translationSetObj->get('update_time'));
		return $translationSet;
	}
}
?>