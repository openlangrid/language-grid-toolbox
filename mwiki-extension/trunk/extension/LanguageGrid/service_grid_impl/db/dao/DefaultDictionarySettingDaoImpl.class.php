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

class DefaultDictionarySettingObject extends AbstractDaoObject {
	private $_binds = null;

	function DefaultDictionarySettingObject() {
		$this->initVar('setting_id');
		$this->initVar('set_id');
		$this->initVar('user_id');
		$this->initVar('create_date');
		$this->initVar('edit_date');
		$this->initVar('delete_flag');
	}
}

class DefaultDictionarySettingDaoImpl extends AbstractDao implements ServiceGridDefaultDictionarySettingDao{

	var $mTable = 'default_dictionary_setting';
	var $mPrimary = "setting_id";
	var $mClass = "DefaultDictionarySettingObject";

	var $mDefaultDictionaryBindDaoImpl = null;

	public function __construct($db) {
		parent::__construct($db);
		$this->mDefaultDictionaryBindDaoImpl = DaoAdapter::getAdapter()->getDefaultDictionaryBindDao();
	}

	public function queryAll() {
		$wheres = array();
		$wheres['delete_flag'] = '0';
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	public function queryBySetId($setId) {
		$wheres = array();
		$wheres['delete_flag'] = '0';
		$wheres['set_id'] = $setId;
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}

	public function getDefaultDictionaryBindObjects($defaultDictionarySetting) {
		return $this->mDefaultDictionaryBindDaoImpl->queryBySetId($defaultDictionarySetting->getSetId());
	}

	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertDefaultDictionarySettingObject($object);
		}
		return $contents;
	}

	public function queryBySetIdUserId($setId, $userId) {
		return null;
	} 
	private function convertDefaultDictionarySettingObject($defaultDictionarySettingObject) {
		$defaultDictionarySetting = new ServiceGridDefaultDictionarySetting();
		$defaultDictionarySetting->setSettingId($defaultDictionarySettingObject->get('setting_id'));
		$defaultDictionarySetting->setSetId($defaultDictionarySettingObject->get('set_id'));
		$defaultDictionarySetting->setUserId($defaultDictionarySettingObject->get('user_id'));
		$defaultDictionarySetting->setCreateDate($defaultDictionarySettingObject->get('create_date'));
		$defaultDictionarySetting->setEditDate($defaultDictionarySettingObject->get('edit_date'));
		$defaultDictionarySetting->setDeleteFlag($defaultDictionarySettingObject->get('delete_flag'));
		return $defaultDictionarySetting;
	}
}
?>