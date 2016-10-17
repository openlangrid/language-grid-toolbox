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
class Permission {

	private $root;
	private $groupId;
	private $db;
	private $moduleName;
	private $languageManager;

	private $topicId = 0;
	private $categoryId = 0;
	private $forumId = 0;

	public function __construct($params = array()) {
		$this->root = XCube_Root::getSingleton();
		$this->groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());
		$this->db = Database::getInstance();
//		$this->languageManager = D3LanguageManager::getInstance();
		$this->languageManager = new LanguageManager();
		$this->moduleName = basename(realpath(dirname(__FILE__).'/../../'));

		$params = array_merge(
			array(
				'postId' => 0,
				'topicId' => 0,
				'forumId' => 0,
				'categoryId' => 0
			)
			, $params
		);

		$this->postId = $params['postId'];
		$this->topicId = $params['topicId'];
		$this->forumId = $params['forumId'];
		$this->categoryId = $params['categoryId'];

		if (!$this->topicId && $this->postId) {
			$this->topicId = $this->getTopicIdByPostId($this->postId);
		}
		if (!$this->forumId && $this->topicId) {
			$this->forumId = $this->getForumIdByTopicId($this->topicId);
		}
		if (!$this->categoryId && $this->forumId) {
			$this->categoryId = $this->getCategoryIdByForumId($this->forumId);
		}
	}
	public function getTopicId() {
		return $this->topicId;
	}
	public function getForumId() {
		return $this->forumId;
	}
	public function getCategoryId() {
		return $this->categoryId;
	}
	private function getTopicIdByPostId($postId) {
		$topicId = 0;
		if ($postId) {
			$postsTable = $this->db->prefix($this->moduleName.'_posts');
			$sql = 'SELECT topic_id FROM '.$postsTable.' WHERE post_id = ? AND delete_flag = 0';
			$this->db->prepare($sql);
			$this->db->bind_param('i', $postId);
			$result = $this->db->execute();

			if ($row = $this->db->fetchArray($result)) {
				$topicId = $row['topic_id'];
			}
		}
		return $topicId;
	}
	private function getForumIdByTopicId($topicId) {
		$forumId = 0;
		if ($topicId) {
			$topicsTable = $this->db->prefix($this->moduleName.'_topics');
			$sql = 'SELECT forum_id FROM '.$topicsTable.' WHERE topic_id = ? AND delete_flag = 0';
			$this->db->prepare($sql);
			$this->db->bind_param('i', $topicId);
			$result = $this->db->execute();

			if ($row = $this->db->fetchArray($result)) {
				$forumId = $row['forum_id'];
			}
		}
		return $forumId;
	}
	private function getCategoryIdByForumId($forumId) {
		$categoryId = 0;
		if ($forumId) {
			$forumsTable = $this->db->prefix($this->moduleName.'_forums');
			$sql = 'SELECT cat_id FROM '.$forumsTable.' WHERE forum_id = ? AND delete_flag = 0';
			$this->db->prepare($sql);
			$this->db->bind_param('i', $forumId);
			$result = $this->db->execute();

			if ($row = $this->db->fetchArray($result)) {
				$categoryId = $row['cat_id'];
			}
		}
		return $categoryId;
	}

	public function categoryCreate() {
		if ($this->isAdmin()) {
			return true;
		}
		return true;
	}

	public function categoryModify() {
		if (!$this->categoryId) {
			return false;
		}
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');

		$sql  = '';
		$sql .= ' SELECT cat_original_language FROM '.$categoriesTable.' WHERE ';
		$sql .= ' `cat_id` = \'%d\'  AND delete_flag = 0';

		$sql = sprintf($sql, $this->categoryId);
		$result = $this->db->query($sql);
		if (!$result) {
			return false;
		}
		$selectedLanguage = $this->languageManager->getSelectedLanguage();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['cat_original_language'] != $selectedLanguage) {
				return true;
			}
		}
		return false;

	}
	public function categoryEdit() {
		if (!$this->categoryId) {
			return false;
		}
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');

		$language = $this->languageManager->getSelectedLanguage();
		$sql  = '';
		$sql .= ' SELECT cat_original_language, COUNT(*) AS CNT FROM '.$categoriesTable.' WHERE ';
		$sql .= ' cat_id = %d AND delete_flag = 0';
//		$this->db->prepare($sql);
//		$this->db->bind_param('ii', $this->topicId, $this->root->mContext->mXoopsUser->get('uid'));

//		$sql = sprintf($sql, $this->categoryId, $this->root->mContext->mXoopsUser->get('uid'));
		$sql = sprintf($sql, $this->categoryId);
		$result = $this->db->query($sql);
//		$result = $this->db->execute();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['CNT'] > 0 && $row['cat_original_language'] == $language) {
				return true;
			}
		}

		$sql  = '';
		$sql .= ' SELECT cat_original_language, COUNT(*) AS CNT FROM '.$categoriesTable;
		$sql .= '   WHERE `cat_id` = \''.intval($this->categoryId).'\' AND delete_flag = 0';
		$result = $this->db->query($sql);
		if ($row = $this->db->fetchArray($result)) {
			if ($this->isAdmin() && $row['cat_original_language'] == $language) {
				return true;
			}
		}
		return false;
	}
	public function categoryDelete() {
		if ($this->isAdmin()) {
			return true;
		}

		if (!$this->categoryId) {
			return false;
		}

		$categoriesTable = $this->db->prefix($this->moduleName.'_category_access');
		$userId = intval($this->root->mContext->mXoopsUser->get('uid'));

		$sql  = '';
		$sql .= ' SELECT * FROM '.$categoriesTable;
		$sql .= ' WHERE (uid = %d) AND cat_id = %d AND can_delete = 1 ';

		$sql = sprintf($sql, $userId, intval($this->categoryId));

//		echo $this->db->mPrepareQuery;
		$result = $this->db->query($sql);

		return (bool)$this->db->getRowsNum($result);
	}
	public function categoryAccess() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->categoryId) {
			return false;
		}

		$categoryAccessTable = $this->db->prefix($this->moduleName.'_category_access');

		$sql  = '';
		$sql .= ' SELECT * FROM '.$categoryAccessTable;
		$sql .= ' WHERE (groupid IN ('.$this->groupId.') OR `all` = 1) AND cat_id = ? ';
//		echo $sql;
		$this->db->prepare($sql);
		$this->db->bind_param('i', $this->categoryId);
//		echo $this->db->mPrepareQuery;
		$result = $this->db->execute();
//		var_dump((bool)$this->db->getRowsNum($result));
		return (bool)$this->db->getRowsNum($result);
	}

	public function categoryPost() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->categoryId) {
			return false;
		}
		$categoryAccessTable = $this->db->prefix($this->moduleName.'_category_access');
		$sql  = '';
		$sql .= ' SELECT can_post FROM '.$categoryAccessTable.' WHERE ';
		$sql .= ' cat_id = ? AND (groupid IN ('.$this->groupId.') OR `all` = 1) ';
		$this->db->prepare($sql);
		$this->db->bind_param('i', $this->categoryId);
//		echo $this->db->mPrepareQuery;
		$result = $this->db->execute();
		while ($row = $this->db->fetchArray($result)) {
			if ($row['can_post']) {
				return true;
			}
		}
		return false;
	}

	public function forumCreate() {
		if ($this->isAdmin()) {
			return true;
		}
		return true;
	}

	public function forumAccess() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->forumId) {
			return false;
		}
		$forumAccessTable = $this->db->prefix($this->moduleName.'_forum_access');
		$sql  = '';
		$sql .= ' SELECT `all` FROM '.$forumAccessTable;
		$sql .= ' WHERE (groupid IN ('.$this->groupId.') OR `all` = 1) ';
		$sql .= ' AND forum_id = ?';

		$this->db->prepare($sql);
		$this->db->bind_param('i', $this->forumId);
//		echo $this->db->mPrepareQuery;
		$result = $this->db->execute();

		return (bool)$this->db->getRowsNum($result);
	}
	public function forumPost() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->forumId) {
			return false;
		}
		$forumAccessTable = $this->db->prefix($this->moduleName.'_forum_access');
		$sql  = '';
		$sql .= ' SELECT can_post FROM '.$forumAccessTable.' WHERE ';
		$sql .= ' forum_id = ? AND (groupid IN ('.$this->groupId.') OR `all` = 1) ';
		$this->db->prepare($sql);
		$this->db->bind_param('i', $this->forumId);
		$result = $this->db->execute();
		while ($row = $this->db->fetchArray($result)) {
			if ($row['can_post']) {
				return true;
			}
		}
		return false;
	}
	public function forumModify() {
		if (!$this->forumId) {
			return false;
		}
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');

		$sql  = '';
		$sql .= ' SELECT forum_original_language FROM '.$forumsTable.' WHERE ';
		$sql .= ' `forum_id` = \'%d\'  AND delete_flag = 0';

		$sql = sprintf($sql, $this->forumId);
		$result = $this->db->query($sql);
		if (!$result) {
			return false;
		}
		$selectedLanguage = $this->languageManager->getSelectedLanguage();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['forum_original_language'] != $selectedLanguage) {
				return true;
			}
		}
		return false;

	}
	public function forumEdit() {
		if (!$this->forumId) {
			return false;
		}
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');

		$language = $this->languageManager->getSelectedLanguage();
		$sql  = '';
		$sql .= ' SELECT forum_original_language, COUNT(*) AS CNT FROM '.$forumsTable.' WHERE ';
		$sql .= ' forum_id = %d AND uid = %d AND delete_flag = 0';
//		$this->db->prepare($sql);
//		$this->db->bind_param('ii', $this->topicId, $this->root->mContext->mXoopsUser->get('uid'));

		$sql = sprintf($sql, $this->forumId, $this->root->mContext->mXoopsUser->get('uid'));
		$result = $this->db->query($sql);
//		$result = $this->db->execute();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['CNT'] > 0 && $row['forum_original_language'] == $language) {
				return true;
			}
		}

		$sql  = '';
		$sql .= ' SELECT forum_original_language, COUNT(*) AS CNT FROM '.$topicsTable;
		$sql .= '   WHERE `forum_id` = \''.intval($this->topicId).'\' AND delete_flag = 0';
		$result = $this->db->query($sql);
		if ($row = $this->db->fetchArray($result)) {
			if ($this->isAdmin() && $row['forum_original_language'] == $language) {
				return true;
			}
		}

		return false;
	}
	public function forumDelete() {
		if ($this->isAdmin()) {
			return true;
		}

		if (!$this->forumId) {
			return false;
		}

		$forumsTable = $this->db->prefix($this->moduleName.'_forum_access');
		$userId = intval($this->root->mContext->mXoopsUser->get('uid'));

		$sql  = '';
		$sql .= ' SELECT * FROM '.$forumsTable;
		$sql .= ' WHERE (uid = %d) AND forum_id = %d AND can_delete = 1 ';

		$sql = sprintf($sql, $userId, intval($this->forumId));

//		echo $this->db->mPrepareQuery;
		$result = $this->db->query($sql);

		return (bool)$this->db->getRowsNum($result);
	}
	public function topicAccess() {
		if ($this->isAdmin()) {
			return true;
		}

		if (!$this->topicId) {
			return false;
		}

		$topicAccessTable = $this->db->prefix($this->moduleName.'_topic_access');

		$sql  = '';
		$sql .= ' SELECT * FROM '.$topicAccessTable;
		$sql .= ' WHERE (groupid IN ('.$this->groupId.') OR `all` = 1) AND topic_id = ? ';

		$this->db->prepare($sql);
		$this->db->bind_param('i', $this->topicId);
//		echo $this->db->mPrepareQuery;
		$result = $this->db->execute();

		return (bool)$this->db->getRowsNum($result);
	}

	public function topicPost() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->topicId) {
			return false;
		}
		$topicAccessTable = $this->db->prefix($this->moduleName.'_topic_access');
		$sql  = '';
		$sql .= ' SELECT can_post FROM '.$topicAccessTable.' WHERE ';
		$sql .= ' topic_id = ? AND (groupid IN ('.$this->groupId.') OR `all` = 1) ';
		$this->db->prepare($sql);
		$this->db->bind_param('i', $this->topicId);
		$result = $this->db->execute();
		while ($row = $this->db->fetchArray($result)) {
			if ($row['can_post']) {
				return true;
			}
		}
		return false;
	}
	public function topicModify() {
		if (!$this->topicId) {
			return false;
		}
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');

		$sql  = '';
		$sql .= ' SELECT topic_original_language FROM '.$topicsTable.' WHERE ';
		$sql .= ' `topic_id` = \'%d\'  AND delete_flag = 0';

		$sql = sprintf($sql, $this->topicId);
		$result = $this->db->query($sql);
		if (!$result) {
			return false;
		}
		$selectedLanguage = $this->languageManager->getSelectedLanguage();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['topic_original_language'] != $selectedLanguage) {
				return true;
			}
		}
		return false;
	}
	public function topicEdit() {
		if (!$this->topicId) {
			return false;
		}
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');

		$language = $this->languageManager->getSelectedLanguage();
		$sql  = '';
		$sql .= ' SELECT topic_original_language, COUNT(*) AS CNT FROM '.$topicsTable;
		$sql .= ' WHERE topic_id = %d AND uid = %d AND delete_flag = 0';
		$sql = sprintf($sql, $this->topicId, $this->root->mContext->mXoopsUser->get('uid'));
		$result = $this->db->query($sql);
		if ($row = $this->db->fetchArray($result)) {
			if ($row['CNT'] > 0 && $row['topic_original_language'] == $language) {
				return true;
			}
		}

		$sql  = '';
		$sql .= ' SELECT topic_original_language, COUNT(*) AS CNT FROM '.$topicsTable;
		$sql .= '   WHERE `topic_id` = \''.intval($this->topicId).'\' AND delete_flag = 0';
		$result = $this->db->query($sql);
		if ($row = $this->db->fetchArray($result)) {
			if ($this->isAdmin() && $row['topic_original_language'] == $language) {
				return true;
			}
		}

		return false;
	}
	public function topicDelete() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->topicId) {
			return false;
		}
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');

		$sql  = '';
		$sql .= ' SELECT COUNT(*) AS CNT FROM '.$topicsTable.' WHERE ';
		$sql .= ' topic_id = %d AND uid = %d AND delete_flag = 0';
//		$this->db->prepare($sql);
//		$this->db->bind_param('ii', $this->topicId, $this->root->mContext->mXoopsUser->get('uid'));

		$sql = sprintf($sql, $this->topicId, $this->root->mContext->mXoopsUser->get('uid'));
		$result = $this->db->query($sql);
//		$result = $this->db->execute();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['CNT'] > 0) {
				return true;
			}
		}
		return false;
	}
	public function postEdit() {
		if (!$this->postId) {
			return false;
		}
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$sql  = '';
		$sql .= ' SELECT post_original_language, COUNT(*) AS CNT FROM '.$postsTable;
		$sql .= '   WHERE `post_id` = ? AND `uid` = ? AND delete_flag = 0';
		$this->db->prepare($sql);
		$this->db->bind_param('ii', $this->postId, $this->root->mContext->mXoopsUser->get('uid'));
		$language = $this->languageManager->getSelectedLanguage();
		$result = $this->db->execute();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['CNT'] > 0 && $row['post_original_language'] == $language) {
				return true;
			}
		}

		$sql  = '';
		$sql .= ' SELECT post_original_language, COUNT(*) AS CNT FROM '.$postsTable;
		$sql .= '   WHERE `post_id` = \''.intval($this->postId).'\' AND delete_flag = 0';
		$result = $this->db->query($sql);
		if ($row = $this->db->fetchArray($result)) {
			if ($this->isAdmin() && $row['post_original_language'] == $language) {
					return true;
			}
		}
//		if ($row = $this->db->fetchArray($result)) {
//			return true;
//		}
		return false;
	}
	public function postDelete() {
		if (!$this->postId) {
			return false;
		}
		if ($this->isAdmin()) {
			return true;
		}
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$sql  = '';
		$sql .= ' SELECT COUNT(*) AS CNT FROM '.$postsTable;
		$sql .= '   WHERE `post_id` = ? AND `uid` = ?  AND delete_flag = 0';
		$this->db->prepare($sql);
		$this->db->bind_param('ii', $this->postId, $this->root->mContext->mXoopsUser->get('uid'));
		$language = $this->languageManager->getSelectedLanguage();
		$result = $this->db->execute();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['CNT'] > 0) {
				return true;
			}
		}
//		if ($row = $this->db->fetchArray($result)) {
//			return true;
//		}
		return false;
	}

	public function postModify() {
		if (!$this->postId) {
			return false;
		}
		$postsTable = $this->db->prefix($this->moduleName.'_posts');

		$sql  = '';
		$sql .= ' SELECT post_original_language FROM '.$postsTable.' WHERE ';
		$sql .= ' `post_id` = \'%d\'  AND delete_flag = 0';

		$sql = sprintf($sql, $this->postId);
		$result = $this->db->query($sql);
		if (!$result) {
			return false;
		}
		$selectedLanguage = $this->languageManager->getSelectedLanguage();
		if ($row = $this->db->fetchArray($result)) {
			if ($row['post_original_language'] != $selectedLanguage) {
				return true;
			}
		}
		return false;
	}
	public function isAdmin() {
		return $this->root->mContext->mXoopsUser->isAdmin();
	}
}
?>