<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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
require_once(dirname(__FILE__).'/Toolbox_ObjectGenericHandler.class.php');
class BBS_PostFileObject extends XoopsSimpleObject {

	function BBS_PostFileObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('post_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('file_name', XOBJ_DTYPE_STRING, '', true, 255);
		$this->initVar('file_data', XOBJ_DTYPE_STRING, '', true);
		$this->initVar('file_size', XOBJ_DTYPE_INT, 0, true);
	}
}

class BBS_PostFileHandler extends Toolbox_ObjectGenericHandler {
	var $mTable = "";
	var $mPrimary = "id";
	var $mClass = "BBS_PostFileObject";
	
	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_post_file");
	}

	function insert(&$obj, $force = false)
	{
		if(!is_a($obj, $this->mClass)) {
			return false;
		}

		$new_flag = false;
		
		if ($obj->isNew()) {
			$new_flag = true;
			$sql = $this->_insert($obj);
		}
		else {
			$sql = $this->_update($obj);
		}
		
		$result = $force ? $this->db->queryF($sql) : $this->db->query($sql);
		
		if (!$result){
			return false;
		}
		
		if ($new_flag) {
			$obj->setVar($this->mPrimary, $this->db->getInsertId());
		}

		return true;
	}
}