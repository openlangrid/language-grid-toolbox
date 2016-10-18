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

class TranslationPathObject extends XoopsSimpleObject {

	private $_execs = null;

	function TranslationPathObject() {
		$this->initVar('path_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('path_name', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('user_id', XOBJ_DTYPE_INT, 0, false);
//		$this->initVar('tool_type', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('set_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('source_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('target_lang', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('revs_path_id', XOBJ_DTYPE_INT, 0, false);

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

	function getExecs() {
		if ($this->_execs == null) {
			$this->_loadExecs();
		}
		return $this->_execs;
	}
	function setExecs($execs) {
		$this->_execs = $execs;
	}
	function _loadExecs() {
		require_once(dirname(__FILE__).'/TranslationExecHandler.class.php');
		$handler =& new TranslationExecHandler($GLOBALS['xoopsDB']);
		$this->_execs =& $handler->getExecObjects($this->get('path_id'));
	}
}

class TranslationPathHandler extends XoopsObjectGenericHandler {

	var $mTable = 'translation_path';
	var $mPrimary = "path_id";
	var $mClass = "TranslationPathObject";

	function &get($id)
	{
		$ret =& parent::get($id);

		if ($ret != null) {
			$ret->_loadExecs();
		}

		return $ret;
	}


	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);
		foreach ($objects as $object) {
			$object->_loadExecs();
		}
		return $objects;
	}

}
?>