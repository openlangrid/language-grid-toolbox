<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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

require_once dirname(__FILE__).'/language-manager.php';

require_once dirname(__FILE__).'/../history/bbs-edit-history-manager.php';
require_once dirname(__FILE__).'/../history/enum-bbs-item-type-code.php';
require_once dirname(__FILE__).'/../history/enum-process-type-code.php';

abstract class AbstractManager {

	protected $root;
	protected $db;
	protected $moduleName;

	protected function __construct() {
		$this->root = XCube_Root::getSingleton();
		$this->db = Database::getInstance();
//		$this->languageManager = D3LanguageManager::getInstance();
		$this->languageManager = new LanguageManager();
		//$this->moduleName = basename(realpath(dirname(__FILE__).'/../../'));
		$this->moduleName = USE_TABLE_PREFIX;
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
		// for prototype comment out kitajima 20090717
//		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
//			$sql .= '  JOIN (SELECT forum_id FROM '.$forumAccessTable.' WHERE (groupid IN (?) OR `all` = 1)';
//			$sql .= ' ) AS T1 USING (forum_id) WHERE cat_id IN ('.$allowedCategoryIdsCSV.')  ';
//			$sql . ' AND delete_flag = 0 ';
//		} else {
			$sql .= ' WHERE delete_flag = 0 ';
//		}
//		echo $sql;die();
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
	protected function setLog($bbsId, $bbsItemTypeCode, $languageCode, $processTypeCode, $bbsText) {
		$bbsEditHistoryManager = new BBSEditHistoryManager();
		$bbsEditHistoryManager->registerModificationHistory(
			$bbsId, $bbsItemTypeCode, $languageCode
			, $processTypeCode, $bbsText, $this->root->mContext->mXoopsUser->get('uid')
		);
	}
}
?>