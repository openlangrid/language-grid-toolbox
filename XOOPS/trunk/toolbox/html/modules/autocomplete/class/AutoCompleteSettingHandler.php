<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: AutoCompleteSettingHandler.php 3550 2010-03-25 07:36:17Z yoshimura $ */

require_once(XOOPS_ROOT_PATH.'/api/class/handler/Toolbox_CompositeKeyGenericHandler.class.php');

class AutoCompleteSettingObject extends XoopsSimpleObject {

	function AutoCompleteSettingObject() {
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('row_id', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('search_target', XOBJ_DTYPE_STRING, true);
		$this->initVar('create_time', XOBJ_DTYPE_INT, 0, false);
	}
}

class AutoCompleteSettingHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "autocomplete_setting";
	var $mClass = "AutoCompleteSettingObject";
	var $mPrimaryAry = array('user_id', 'row_id');

	function searchByUserId($userId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('user_id', $userId));

		$objects = $this->getObjects($c);

		if ($objects == null) {
			return array();
		}

		return $objects;
	}

	function deleteByUserId($userId) {
		$_sql = 'DELETE FROM %s WHERE `user_id` = %d';
		$sql = sprintf($_sql, $this->mTable, $userId);
		return $this->db->queryF($sql);
	}
}
?>