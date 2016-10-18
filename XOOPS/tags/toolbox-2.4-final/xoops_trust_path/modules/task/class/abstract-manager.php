<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

require_once XOOPS_TRUST_PATH.'/modules/collabtrans/class/language-manager.php';

abstract class AbstractManager {

	protected $root;
	protected $db;
	protected $moduleName;
	protected $selectedLanguage;

	protected function __construct($lang) {
		$this->root = XCube_Root::getSingleton();
		$this->db = Database::getInstance();
		$this->moduleName = 'communication';
		$this->selectedLanguage = $lang;
	}
	protected function getAllGroupIds() {
		$groupsTable = $this->db->prefix('groups');
		$sql = ' SELECT groupid FROM '.$groupsTable;
		$result = $this->db->query($sql);
		$groupIds = array();
		while($row = $this->db->fetchArray($result)) {
			$groupIds[] = $row['groupid'];
		}
		return $groupIds;
	}
	protected function escape($str) {
		if ( get_magic_quotes_gpc() ) {
			$str = stripslashes( $str );
		}
		return mysql_real_escape_string($str);
	}
	protected function getGroupId() {
		return implode(',', $this->root->mContext->mXoopsUser->getGroups());
	}
	protected function getAllowedCategoryIds() {
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
		$categoryAccessTable = $this->db->prefix($this->moduleName.'_category_access');
		$sql  = '';
		$sql .= ' SELECT cat_id FROM '.$categoriesTable.' ';
		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= '  JOIN (SELECT cat_id FROM '.$categoryAccessTable.' WHERE (groupid IN (?) OR `all` = 1) ';
			$sql .= ' ) AS T1 USING (cat_id) ';
		}
		$sql .= ' WHERE delete_flag = 0 ';
		$this->db->prepare($sql);
		$this->db->bind_param('s', $this->getGroupId());
		$result = $this->db->execute();

		$categoryIds = array();
		while ($row = $this->db->fetchArray($result)) {
			$categoryIds[] = $row['cat_id'];
		}
		return $categoryIds;
	}
	protected function getAllowedForumIds() {
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');
		$forumAccessTable = $this->db->prefix($this->moduleName.'_forum_access');
		$allowedCategoryIdsCSV = implode(',', $this->getAllowedCategoryIds());

		$sql  = '';
		$sql .= ' SELECT forum_id FROM '.$forumsTable.' ';
		$sql .= ' WHERE delete_flag = 0 ';
		$this->db->prepare($sql);
		$this->db->bind_param('s', $this->getGroupId());
		$result = $this->db->execute();

		$forumIds = array();
		while ($row = $this->db->fetchArray($result)) {
			$forumIds[] = $row['forum_id'];
		}
		return $forumIds;
	}
	protected function getAllowedTopicIds() {
		$topicsTable = $this->db->prefix($this->moduleName.'_forums');
		$topicAccessTable = $this->db->prefix($this->moduleName.'_topic_access');
		$allowedForumIdsCSV = implode(',', $this->getAllowedForumIds());

		$sql  = '';
		$sql .= ' SELECT topic_id FROM '.$topicsTable.' ';
		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= '  JOIN (SELECT topic_id FROM '.$topicAccessTable.' WHERE groupid IN (?) OR `all` = 1 ';
			$sql .= ' ) AS T1 USING (topic_id) WHERE forum_id IN ('.$allowedForumIdsCSV.') ';
			$sql .= ' AND delete_flag = 0 ';
		} else {
			$sql .= ' WHERE delete_flag = 0 ';
		}
		$this->db->prepare($sql);
		$this->db->bind_param('s', $this->getGroupId);
		$result = $this->db->execute();

		$topicIds = array();
		while ($row = $this->db->fetchArray($result)) {
			$topicIds[] = $row['topic_id'];
		}
		return $topicIds;
	}
}
