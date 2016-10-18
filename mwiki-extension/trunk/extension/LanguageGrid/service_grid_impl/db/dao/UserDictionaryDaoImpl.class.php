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
require_once(dirname(__FILE__).'/UserDictionaryContentsDaoImpl.class.php');
require_once(dirname(__FILE__).'/../../../service_grid/db/dao/ServiceGridUserDictionaryDAO.interface.php');

class UserDictionaryObject extends AbstractDaoObject {

	function UserDictionaryObject() {
		$this->mVar['user_dictionary_id'] = '';
		$this->mVar['user_id'] = '';
		$this->mVar['type_id'] = '';
		$this->mVar['dictionary_name'] = '';
		$this->mVar['create_date'] = '';
		$this->mVar['update_date'] = '';
		$this->mVar['deploy_flag'] = '';
		$this->mVar['delete_flag'] = '';
		$this->mVar['last_update_user'] = '';
	}

	function _loadContents() {
		$dao =& new UserDictionaryContentsDaoImpl(wfGetDB(DB_MASTER));
	}
}

class UserDictionaryDaoImpl extends AbstractDao implements ServiceGridUserDictionaryDao {

	var $mTable = "user_dictionary";
	var $mPrimary = "user_dictionary_id";
	var $mClass = "UserDictionaryObject";

	function &get($id, $hasContents = false) {
		$ret =& parent::get($id);
		if ($hasContents == true && $ret != null) {
			$ret->_loadContents();
		}
		return $ret;
	}

	function insert($obj) {
		if ($obj->isNew() == false) {
			if ($this->update($obj)) {
				return $obj;
			}
		} else {
			$data = (array)$obj->getVars();
			$data['create_date'] = time();
			$id = parent::insert($data, true);
			$obj->set('user_dictionary_id', $id);
			return $obj;
		}
	}
	function update($object) {
		$data = (array)$object->getVars();
		return parent::update($data);
	}

	function delete($object) {
		$data = (array)$object->getVars();

		return parent::delete($data);
	}
	public function getUserDictionaryIdByName($dictName) {
		$wheres = array();
		$wheres['dictionary_name'] = $dictName;
		$wheres['delete_flag'] = 0;
		$objects = parent::search($wheres);
		return $objects[0]->get('user_dictionary_id');
	}	
}
?>