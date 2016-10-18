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

class BBS_TopicObject extends XoopsSimpleObject {

	var $access;
	var $m_accessLoaded = false;
	var $body;
	var $m_bodyLoaded = false;

	function BBS_TopicObject() {
		$this->initVar('topic_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('forum_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('topic_original_language', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('update_date', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
	}

	function _loadAccess($modName) {
		$handler =& $this->_getAccessHandler($modName);
		$this->access =& $handler->get($this->get('topic_id'));
		if ($this->access) {
			$this->m_accessLoaded = true;
		}
	}

	function _loadBody($modName) {
		$handler =& $this->_getBodyHandler($modName);
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('topic_id', $this->get('topic_id')));
		$this->body =& $handler->getObjects($mCriteria);
		if ($this->body) {
			$this->m_bodyLoaded = true;
		}
	}

	private function _getAccessHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_TopicAccessHandler.class.php');
		$handler =& new BBS_TopicAccessHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}

	private function _getBodyHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_TopicsBodyHandler.class.php');
		$handler =& new BBS_TopicsBodyHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}
}

class BBS_TopicsHandler extends Toolbox_ObjectGenericHandler {

	var $mTable = "";
	var $mPrimary = "topic_id";
	var $mClass = "BBS_TopicObject";
	var $mModName = "";

	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_topics");
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

	function getCountByCategoryId($categoryId) {
		require_once(dirname(__FILE__).'/BBS_ForumsHandler.class.php');
		$forumHandler =& new BBS_ForumsHandler($this->db,$this->mModName);

		$topicCount = 0;

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('cat_id', $categoryId));
		$mCriteria->add(new Criteria('delete_flag', '0'));

		$forums =& $forumHandler->getObjects($mCriteria);

		foreach ($forums as $forum) {
			$crt =& new CriteriaCompo();
			$crt->add(new Criteria('forum_id', $forum->get('forum_id')));
			$crt->add(new Criteria('delete_flag', '0'));
			$cnt = parent::getCount($crt);
			$topicCount = $topicCount + $cnt;
		}

		return $topicCount;
	}

	function deleteByCategoryId($categoryId, $force = false) {
		require_once(dirname(__FILE__).'/BBS_ForumsHandler.class.php');
		$forumHandler =& new BBS_ForumsHandler($this->db,$this->mModName);

		$topicCount = 0;

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('cat_id', $categoryId));
		//$mCriteria->add(new Criteria('delete_flag', '0'));

		$forums =& $forumHandler->getObjects($mCriteria);

		if (count($forums)) {
			$ids = array();
			foreach ($forums as $forum) {
				$ids[] = $forum->get('forum_id');
			}
			$sql = "";
			$sql .= "UPDATE ".$this->mTable;
			$sql .= " SET `delete_flag` = '1', `update_date` = ".time();
			$sql .= " WHERE `forum_id` IN (".implode(',', $ids).")";

			return $force ? $this->db->queryF($sql) : $this->db->query($sql);
		}

		return true;
	}

	function deleteByForumId($forumId, $force = false) {
		$sql = "";
		$sql .= "UPDATE ".$this->mTable;
		$sql .= " SET `delete_flag` = '1', `update_date` = ".time();
		$sql .= " WHERE `forum_id` = '".$forumId."'";

		return $force ? $this->db->queryF($sql) : $this->db->query($sql);
	}

}
?>