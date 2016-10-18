<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to set
// translation paths.
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

//require_once(XOOPS_ROOT_PATH.'/api/class/handler/Toolbox_ObjectGenericHandler.class.php');

class LangridServiceObject extends XoopsSimpleObject {

	function LangridServiceObject() {
		$this->initVar('service_id', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_name', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('endpoint_url', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('supported_languages_paths', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('organization', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('copyright', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('license', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('description', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('registered_date', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('updated_date', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('create_date', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('edit_date', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', false);
	}
}

class LangridServicesHandler extends XoopsObjectGenericHandler {

	var $mTable = 'langrid_services';
	var $mPrimary = "service_id";
	var $mClass = "LangridServiceObject";

//	function &get($id)
//	{
//		$ret =& parent::get($id);
//
//		if ($ret != null) {
//			$ret->_loadExecs();
//		}
//
//		return $ret;
//	}
//
//
//	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
//		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);
//		foreach ($objects as $object) {
//			$object->_loadExecs();
//		}
//		return $objects;
//	}
}
?>