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
require_once(dirname(__FILE__).'/../../../service_grid/db/dao/ServiceGridLogDAO.interface.php');

class ServiceGridLogObject extends AbstractDaoObject {

	function __construct() {
		$this->mVar['log_id'] = '';
		$this->mVar['source_lang'] = '';
		$this->mVar['target_lang'] = '';
		$this->mVar['source'] = '';
		$this->mVar['result'] = '';
		$this->mVar['executed_time'] = '';
		$this->mVar['service_name'] = '';
		$this->mVar['url'] = '';
		$this->mVar['executed_user'] = '';
	}
}

class ServiceGridLogDaoImpl extends AbstractDao implements ServiceGridLogDao {

	var $mTable = "service_grid_log";
	var $mPrimary = "log_id";
	var $mClass = "ServiceGridLogObject";

	function &get($id) {
		$ret =& parent::get($id);
		return $ret;
	}

	function insert($obj) {
		if ($obj->isNew() == false) {
			if ($this->update($obj)) {
				return $obj;
			}
		} else {
			$data = (array)$obj->getVars();
			$data['executed_time'] = time();
			$id = parent::insert($data, true);
			$obj->set('log_id', $id);
			return $obj;
		}
	}
}
?>