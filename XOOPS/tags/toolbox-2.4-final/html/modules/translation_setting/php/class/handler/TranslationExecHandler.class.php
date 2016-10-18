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

class TranslationExecObject extends XoopsSimpleObject {

	private $_binds = null;

	function TranslationExecObject() {
		$this->initVar('path_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('exec_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('exec_order', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('source_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('target_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('service_id', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('dictionary_flag', XOBJ_DTYPE_INT, 0, false);

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

	function getBinds() {
		if ($this->_binds == null) {
			$this->_loadBinds();
		}
		return $this->_binds;
	}
	function setBinds($binds) {
		$this->_binds = $binds;
	}
	function _loadBinds() {
		require_once(dirname(__FILE__).'/TranslationBindHandler.class.php');
		$handler =& new TranslationBindHandler($GLOBALS['xoopsDB']);
		$this->_binds =& $handler->getBindObjects($this->get('path_id'), $this->get('exec_id'));
	}
}

class TranslationExecHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = 'translation_exec';
	var $mPrimary = "path_id";
	var $mPrimaryAry = array("path_id", "exec_id");
	var $mClass = "TranslationExecObject";

	function getExecObjects($pathId) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('path_id', $pathId));
		$criteria->addSort('exec_order');

		$objects =& parent::getObjects($criteria);

		foreach ($objects as $object) {
			$object->_loadBinds();
		}

		return $objects;
	}
}
?>