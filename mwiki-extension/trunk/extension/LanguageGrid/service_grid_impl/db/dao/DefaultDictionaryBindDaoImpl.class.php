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
class DefaultDictionaryBindObject extends AbstractDaoObject  {
	function DefaultDictionaryBindObject() {
		$this->initVar('setting_id');
		$this->initVar('bind_id');
		$this->initVar('bind_type');
		$this->initVar('bind_value');
		$this->initVar('create_date');
		$this->initVar('edit_date');
		$this->initVar('delete_flag');
	}
}

class DefaultDictionaryBindDaoImpl extends AbstractDaoComposite implements ServiceGridDefaultDictionaryBindDAO {

	var $mTable = 'default_dictionary_bind';
	var $mPrimary = "setting_id";
	var $mPrimaryAry = array("setting_id", "bind_id");
	var $mClass = "DefaultDictionaryBindObject";

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

	/**
	 * 主キーを指定しての検索です。該当レコードが存在しない場合は例外をスローします。
	 */
	public function queryByBindId($setId, $bindId) {
		$wheres = array();
		$wheres['delete_flag'] = '0';
		$wheres['set_id'] = $setId;
		$wheres['bind_id'] = $bindId;
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}


	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertDefaultDictionaryBindObject($object);
		}
		return $contents;
	}
	
	public function queryBySettingId($settingId) {
        $wheres = array();
		$wheres['delete_flag'] = '0';
		$wheres['setting_id'] = $settingId;
		$objects = parent::search($wheres);
		return $this->objects2objects($objects);
	}
    
	public function delete($object) {
        $wheres = array();
        $wheres['setting_id'] = $object->getSettingId();
        $wheres['bind_id'] = $object->getBindId();
		parent::delete($wheres);
		return true;
	}

	private function convertDefaultDictionaryBindObject($defaultDictionaryBindObject) {
		$defaultDictionaryBind = new ServiceGridDefaultDictionaryBind();
		$defaultDictionaryBind->setSettingId($defaultDictionaryBindObject->get('setting_id'));
		$defaultDictionaryBind->setBindId($defaultDictionaryBindObject->get('bind_id'));
		$defaultDictionaryBind->setBindType($defaultDictionaryBindObject->get('bind_type'));
		$defaultDictionaryBind->setBindValue($defaultDictionaryBindObject->get('bind_value'));
		$defaultDictionaryBind->setCreateDate($defaultDictionaryBindObject->get('create_date'));
		$defaultDictionaryBind->setEditDate($defaultDictionaryBindObject->get('edit_date'));
		$defaultDictionaryBind->setDeleteFlag($defaultDictionaryBindObject->get('delete_flag'));
		return $defaultDictionaryBind;
	}

}
?>