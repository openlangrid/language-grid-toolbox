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

class TranslationBindObject extends XoopsSimpleObject {

	function TranslationBindObject() {
		$this->initVar('path_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('exec_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bind_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('bind_value', XOBJ_DTYPE_STRING, '', false);

		$root =& XCube_Root::getSingleton();
		$uid = 0;
		if ($root->mContext->mXoopsUser) {
			$uid = $root->mContext->mXoopsUser->get('uid');
		}
		$this->initVar('create_user_id', XOBJ_DTYPE_INT, $uid, false);
		$this->initVar('update_user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('create_time', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
	}
}

class TranslationBindHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = 'translation_bind';
	var $mPrimary = "path_id";
	var $mPrimaryAry = array("path_id", "exec_id", "bind_id");
	var $mClass = "TranslationBindObject";

	function getBindObjects($pathId, $execId) {
		$criteria =& new CriteriaCompo();
		$criteria->add(new Criteria('path_id', $pathId));
		$criteria->add(new Criteria('exec_id', $execId));
		$objects = parent::getObjects($criteria);
		if ($objects == null) {
			return array();
		}
		return $objects;
	}
}
?>