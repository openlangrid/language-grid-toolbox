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

class Profile_UsersObject extends XoopsSimpleObject {

	function Profile_UsersObject() {
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('name', XOBJ_DTYPE_STRING, '', false, 60);
		$this->initVar('uname', XOBJ_DTYPE_STRING, '', false, 30);
		$this->initVar('email', XOBJ_DTYPE_STRING, '', false, 60);
		$this->initVar('user_avatar', XOBJ_DTYPE_STRING, '', false, 30);
		$this->initVar('user_viewemail', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('timezone_offset', XOBJ_DTYPE_FLOAT, 0, false);
	}
}

class Profile_UsersHandler extends Toolbox_ObjectGenericHandler {

	var $mTable = "users";
	var $mPrimary = "uid";
	var $mClass = "Profile_UsersObject";

	function &get($id) {
		$object =& parent::get($id);
		return $object;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);
		return $objects;
	}
//
//	function insert(&$obj, $force = false) {
//		$sql = parent::_insert($obj, $force);
//
//		echo '<pre>'.$sql.'</pre><br>';
//
//		return $sql;
//	}
}
?>