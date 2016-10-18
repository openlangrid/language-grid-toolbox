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

class BBS_PostObject extends XoopsSimpleObject {

	var $body;
	var $m_bodyLoaded = false;

	function BBS_PostObject() {
		$this->initVar('post_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('topic_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('poster_ip', XOBJ_DTYPE_STRING, '0.0.0.0', false);
		$this->initVar('post_original_language', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('post_time', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('post_order', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('reply_post_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
		$this->initVar('update_date', XOBJ_DTYPE_INT, time(), false);
	}

	function _loadBody($modName) {
		$handler =& $this->_getBodyHandler($modName);
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('post_id', $this->get('post_id')));
		$this->body =& $handler->getObjects($mCriteria);
		if ($this->body) {
			$this->m_bodyLoaded = true;
		}
	}

	private function _getBodyHandler($modName) {
		require_once(dirname(__FILE__).'/BBS_PostsBodyHandler.class.php');
		$handler =& new BBS_PostsBodyHandler($GLOBALS['xoopsDB'],$modName);
		return $handler;
	}
}

class BBS_PostsHandler extends Toolbox_ObjectGenericHandler {

	var $mTable = "";
	var $mPrimary = "post_id";
	var $mClass = "BBS_PostObject";
	var $mModName = "";

	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_posts");
		$this->mModName = $moduleName;
	}

	function &get($id) {
		$object =& parent::get($id);
		if ($object) {
			$object->_loadBody($this->mModName);
		}
		return $object;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			// load to outer informations.
			foreach ($objects as &$object) {
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

		$count = 0;
		foreach ($forums as $forum) {
			$forumId = $forum->get('forum_id');
			$count = $count + $this->getCountByForumId($forumId);
		}
		return $count;
	}

	function getCountByForumId($forumId) {
		require_once(dirname(__FILE__).'/BBS_TopicsHandler.class.php');
		$topicHandler =& new BBS_TopicsHandler($this->db,$this->mModName);

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('forum_id', $forumId));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$topics =& $topicHandler->getObjects($mCriteria);

		$count = 0;
		foreach ($topics as $topic) {
			$topicId = $topic->get('topic_id');
			$count = $count + $this->getCountByTopicId($topicId);
		}
		return $count;
	}

	function getCountByTopicId($topicId) {
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('topic_id', $topicId));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		return parent::getCount($mCriteria);
	}

	function deleteByCategoryId($categoryId, $force = false) {
		require_once(dirname(__FILE__).'/BBS_ForumsHandler.class.php');
		$forumHandler =& new BBS_ForumsHandler($this->db,$this->mModName);

		$topicCount = 0;

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('cat_id', $categoryId));
		$forums =& $forumHandler->getObjects($mCriteria);

		foreach ($forums as $forum) {
			$forumId = $forum->get('forum_id');
			if (!$this->deleteByForumId($forumId, $force)) {
				return false;
			}
		}
		return true;
	}

	function deleteByForumId($forumId, $force = false) {
		require_once(dirname(__FILE__).'/BBS_TopicsHandler.class.php');
		$topicHandler =& new BBS_TopicsHandler($this->db,$this->mModName);

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('forum_id', $forumId));
		$topics =& $topicHandler->getObjects($mCriteria);

		foreach ($topics as $topic) {
			$topicId = $topic->get('topic_id');
			if (!$this->deleteByTopicId($topicId, $force)) {
				return false;
			}
		}
		return true;
	}

	function deleteByTopicId($topicId, $force = false) {
		$sql = "";
		$sql .= "UPDATE ".$this->mTable;
		$sql .= " SET `delete_flag` = '1' ";
		$sql .= " WHERE `topic_id` = '".$topicId."'";

		return $force ? $this->db->queryF($sql) : $this->db->query($sql);
	}

	function getPostOrder($topicId) {
		$sql = "";
		$sql .= "SELECT COALESCE(MAX(post_order),0) AS c ";
		$sql .= " FROM ".$this->mTable;
		$sql .= " WHERE topic_id = '".$topicId."'";

		$num = parent::_getCount($sql);

		return $num + 1;
	}

	function delete(&$obj, $force = false)
	{
		$criteria =& new Criteria($this->mPrimary, $obj->get($this->mPrimary));
        $sql = "UPDATE `" . $this->mTable . "` SET `delete_flag` = '1' WHERE " . $this->_makeCriteriaElement4sql($criteria, $obj);

		return $force ? $this->db->queryF($sql) : $this->db->query($sql);
	}

	function getReplyMessageIds($PostID){
		$ret = array();

		$sql = "SELECT post_id FROM `" . $this->mTable . "` WHERE reply_post_id = ".intval($PostID)." ";
		$result = $this->db->query($sql);
		if (!$result) {return $ret;}
		while($row = $this->db->fetchArray($result)) {
			$ret[] = $row["post_id"];
		}
		return $ret;
	}
}
?>