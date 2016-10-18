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

class CommunityResourcePermissionObject extends XoopsSimpleObject {

	function CommunityResourcePermissionObject() {
		$this->initVar('user_dictionary_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('permission_type', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('permission_type_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('view', XOBJ_DTYPE_INT, true);
		$this->initVar('edit', XOBJ_DTYPE_INT, true);
		$this->initVar('use', XOBJ_DTYPE_INT, true);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
	}
}

class CommunityResourcePermissionHandler extends XoopsObjectGenericHandler {

	var $mTable = "user_dictionary_permission";
	var $mPrimary = "user_dictionary_id";
	var $mClass = "CommunityResourcePermissionObject";

	function getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			return $objects;
		}
		return null;
	}

//	function _insert(&$obj) {
//		$sql = parent::_insert($obj);
//		echo $sql;
//		return $sql;
//	}
}
?>