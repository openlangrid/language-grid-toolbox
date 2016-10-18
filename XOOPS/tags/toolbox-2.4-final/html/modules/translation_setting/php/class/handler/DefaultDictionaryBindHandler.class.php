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

require_once(XOOPS_ROOT_PATH.'/api/class/handler/Toolbox_CompositeKeyGenericHandler.class.php');

class DefaultDictionaryBindObject extends XoopsSimpleObject {

	function DefaultDictionaryBindObject() {
		$this->initVar('setting_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('bind_value', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('edit_date', XOBJ_DTYPE_INT, 0 ,true);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', false);
	}
}

class DefaultDictionaryBindHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = 'default_dictionary_bind';
	var $mPrimary = "setting_id";
	var $mPrimaryAry = array("setting_id", "bind_id");
	var $mClass = "DefaultDictionaryBindObject";

	function getBindObjects($settingId) {
		$criteria =& new CriteriaCompo();
		$criteria->add(new Criteria('setting_id', $settingId));
		return parent::getObjects($criteria);
	}
}
?>