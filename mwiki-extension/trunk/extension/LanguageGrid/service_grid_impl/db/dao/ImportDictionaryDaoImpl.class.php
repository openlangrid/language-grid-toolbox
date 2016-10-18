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
require_once(dirname(__FILE__).'/../../../service_grid/db/dao/ServiceGridImportDictionaryDAO.interface.php');
require_once(dirname(__FILE__).'/../../../service_grid/db/dto/ServiceGridImportDictionary.class.php');

class ImportDictionaryObject extends AbstractDaoObject {

	function __construct() {
		$this->mVar['id'] = '';
		$this->mVar['user_dictionary_id'] = '';
		$this->mVar['bind_type'] = '';
		$this->mVar['bind_value'] = '';
		$this->mVar['create_date'] = '';
	}
}

class ImportDictionaryDaoImpl extends AbstractDao implements ServiceGridImportDictionaryDAO {

	var $mTable = "import_dictionary";
	var $mPrimary = "id";
	var $mClass = "ImportDictionaryObject";

	public function queryById($id) {
		$object = parent::get($id);
		if ($object) {
			return $this->convertObject($object);
		}
		return false;
	}

	public function queryByUserDictionaryId($userDictionaryId, $bindType = '') {
		$wheres = array();
		$wheres['user_dictionary_id'] = $userDictionaryId;
		if ($bindType) {
			$wheres['bind_type'] = $bindType;
		}
		$objects = parent::search($wheres);
		if ($objects) {
			return $this->o2o($objects);
		}
		return false;
	}

	public function searchByParams($userDictionaryId, $bindType, $bindValue) {
		$wheres = array();
		$wheres['user_dictionary_id'] = $userDictionaryId;
		$wheres['bind_type'] = $bindType;
		$wheres['bind_value'] = $bindValue;
		$objects = parent::search($wheres);
		if ($objects) {
			return $this->o2o($objects);
		}
		return false;
	}

	public function insert($userDictionaryId, $bindType, $bindValue) {
		$data = array(
			'user_dictionary_id' => $userDictionaryId,
			'bind_type' => $bindType,
			'bind_value' => $bindValue,
			'create_date' => time()
		);
		$id = parent::insert($data, true);
		if ($id) {
			return $this->queryById($id);
		}
		return false;
	}

	public function delete($id) {
		$wheres = array('id' => $id);
		return parent::delete($wheres);
	}

	private function o2o($objects) {
		$list = array();
		foreach ($objects as $o) {
			$list[] = $this->convertObject($o);
		}
		return $list;
	}

	private function convertObject($object) {
		$importDictionary = new ServiceGridImportDictionary();
		$importDictionary->setId($object->get('id'));
		$importDictionary->setUserDictionaryId($object->get('user_dictionary_id'));
		$importDictionary->setBindType($object->get('bind_type'));
		$importDictionary->setBindValue($object->get('bind_value'));
		$importDictionary->setCreateDate($object->get('create_date'));
		return $importDictionary;
	}
}
?>
