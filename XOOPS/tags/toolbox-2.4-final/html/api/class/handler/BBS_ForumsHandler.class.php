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

class BBS_ForumObject extends XoopsSimpleObject {

	var $access;
	var $m_accessLoaded = false;
	var $body;
	var $m_bodyLoaded = false;

	function BBS_ForumObject() {
		$this->initVar('forum_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('cat_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('forum_original_language', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('update_date', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
	}

	function _loadAccess($modName) {
		$handler =& $this->_getAccessHandler($modName);
		$this->access =& $handler->get($this->get('forum_id'));
		if ($this->access) {
			$this->m_accessLoaded = true;
		}
	}

	function _loadBody($modName) {
		$handler =& $this->_getBodyHandler($modName);
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('forum_id', $this->get('forum_id')));
		$this->body =& $handler->getObjects($mCriteria);
		if ($this->body) {
			$this->m_bodyLoaded = true;
		}
	}

	private function _getAccessHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_ForumAccessHandler.class.php');
		$handler =& new BBS_ForumAccessHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}

	private function _getBodyHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_ForumsBodyHandler.class.php');
		$handler =& new BBS_ForumsBodyHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}
}

class BBS_ForumsHandler extends Toolbox_ObjectGenericHandler {

	var $mTable = "";
	var $mPrimary = "forum_id";
	var $mClass = "BBS_ForumObject";
	var $mModName = "";
	
	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_forums");
		$this->mModName = $moduleName;
	}

	function &get($id) {
		$object =& parent::get($id);
		if ($object) {
			$object->_loadAccess($this->mModName);
			$object->_loadBody($this->mModName);
		}
		return $object;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			// load to outer informations.
			foreach ($objects as &$object) {
				$object->_loadAccess($this->mModName);
				$object->_loadBody($this->mModName);
			}
		}
		return $objects;
	}

	function deleteByCategoryId($categoryId, $force = false) {

		$sql = "";
		$sql .= "UPDATE ".$this->mTable;
		$sql .= " SET `delete_flag` = '1', `update_date` = ".time();
		$sql .= " WHERE `cat_id` = '".$categoryId."'";

		return $force ? $this->db->queryF($sql) : $this->db->query($sql);
	}

}
?>