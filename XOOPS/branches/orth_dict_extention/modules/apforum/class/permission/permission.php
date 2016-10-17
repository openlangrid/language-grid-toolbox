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

		return true;
	}

	public function categoryModify() {
		if (!$this->categoryId) {
			return false;
		}
		
		require_once dirname(__FILE__).'/../manager/category-manager.php';
		
		
		$categoryM = new CategoryManager();
		$category = $categoryM->getCategory($this->categoryId);
		$category_lang = $category->getOriginalLanguage();
		$language = $this->languageManager->getSelectedLanguage();

		if ($this->categoryAccess() && $category_lang != $language) {
			return true;
		}
		return false;

	}
	public function categoryEdit() {
		if (!$this->categoryId) {
			return false;
		}
		require_once dirname(__FILE__).'/../manager/category-manager.php';
		
		
		$categoryM = new CategoryManager();
		$category = $categoryM->getCategory($this->categoryId);
		$category_lang = $category->getOriginalLanguage();		
		$language = $this->languageManager->getSelectedLanguage();

		if ($this->categoryAccess()&& $category_lang == $language) {
			return true;
		}
		return false;
	}
	
	public function categoryDelete() {
		if ($this->isAdmin()) {
			return true;
		}
		return false;
	}
	

	public function categoryAccess() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->categoryId) {
			return false;
		}
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
		$categoryAccessTable = $this->db->prefix($this->moduleName.'_category_access');
		$categoryAuthTable = $this->db->prefix($this->moduleName.'_category_auth');
		$sql  = '';
		$sql .= ' SELECT * FROM '.$categoriesTable.' ';
		$sql .= ' WHERE delete_flag=0 AND cat_id IN(';
		$sql .= '  SELECT cat_id FROM '.$categoryAccessTable;
		$sql .= '  WHERE `all` = 1 AND cat_id = ? ';
		$sql .= '  UNION SELECT cat_id FROM '.$categoryAuthTable;
		$sql .= '  WHERE groupid IN('.$this->groupId.') AND cat_id = ?';
		$sql .= ' )';
//		echo $sql;
		$this->db->prepare($sql);
		$this->db->bind_param('ii', $this->categoryId,$this->categoryId);
//		echo $this->db->mPrepareQuery;
		$result = $this->db->execute();
//		var_dump((bool)$this->db->getRowsNum($result));
		return (bool)$this->db->getRowsNum($result);
	}

	// public function categoryPost() {
		// if ($this->isAdmin()) {
			// return true;
		// }
		// if (!$this->categoryId) {
			// return false;
		// }
		// $categoryAccessTable = $this->db->prefix($this->moduleName.'_category_access');
		// $sql  = '';
		// $sql .= ' SELECT can_post FROM '.$categoryAccessTable.' WHERE ';
		// $sql .= ' cat_id = ? AND (groupid IN ('.$this->groupId.') OR `all` = 1) ';
		// $this->db->prepare($sql);
		// $this->db->bind_param('i', $this->categoryId);
		// echo $this->db->mPrepareQuery;
		// $result = $this->db->execute();
		// while ($row = $this->db->fetchArray($result)) {
			// if ($row['can_post']) {
				// return true;
			// }
		// }
		// return false;
	// }

	public function forumCreate() {

		if ($this->categoryAccess()){
			return true;
		}
		
		return false;
	}

	public function forumAccess() {
		if ($this->isAdmin()) {
			return true;
		}
		if (!$this->forumId) {
			return false;
		}
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');
		$forumAccessTable = $this->db->prefix($this->moduleName.'_forum_access');
		$forumAuthTable = $this->db->prefix($this->moduleName.'_forum_auth');
		$sql  = '';
		$sql .= ' SELECT * FROM '.$forumsTable.' ';
		$sql .= ' WHERE delete_flag=0 AND forum_id IN(';
		$sql .= '  SELECT forum_id FROM '.$forumAccessTable;
		$sql .= '  WHERE `all` = 1 AND forum_id = ?';
		$sql .= '  UNION SELECT forum_id FROM '.$forumAuthTable;
		$sql .= '  WHERE groupid IN('.$this->groupId.') AND forum_id = ?';
		$sql .= ' )';
		$this->db->prepare($sql);
		$this->db->bind_param('ii', $this->forumId,$this->forumId);
		$result = $this->db->execute();
		return (bool)$this->db->getRowsNum($result);
	}
/* 	public function forumPost() {
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
*/
	public function forumModify() {
		if (!$this->forumId) {
			return false;
		}
		
		require_once dirname(__FILE__).'/../manager/forum-manager.php';
		
		
		$forumM = new ForumManager();
		$forum = $forumM->getForum($this->forumId);
		$forum_lang = $forum->getOriginalLanguage();
		$language = $this->languageManager->getSelectedLanguage();

		if ($this->forumAccess()&& $forum_lang != $language) {
			return true;
		}
		return false;


	}
	public function forumEdit() {
		if (!$this->forumId) {
			return false;
		}
		require_once dirname(__FILE__).'/../manager/forum-manager.php';
		
		
		$forumM = new ForumManager();
		$forum = $forumM->getForum($this->forumId);
		$forum_lang = $forum->getOriginalLanguage();
		$language = $this->languageManager->getSelectedLanguage();
		
		if ($this->forumAccess() && $forum_lang == $language) {
			return true;
		}
		return false;
	}
	public function forumDelete() {
		if ($this->isAdmin()) {
			return true;
		}
		return false;
		
	}
	public function topicCreate() {
		if ($this->forumAccess()){
			return true;
		}
		
		return false;
	}
	
	public function topicAccess() {
		if ($this->isAdmin()) {
			return true;
		}

		if (!$this->topicId) {
			return false;
		}
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$topicAccessTable = $this->db->prefix($this->moduleName.'_topic_access');
		$topicAuthTable = $this->db->prefix($this->moduleName.'_topic_auth');
		$sql  = '';
		$sql .= ' SELECT * FROM '.$topicsTable. ' ';
		$sql .= ' WHERE delete_flag = 0 AND topic_id IN( ';
		$sql .= '  SELECT topic_id FROM '.$topicAccessTable;
		$sql .= '  WHERE `all` = 1 AND topic_id = ? ';
		$sql .= '  UNION SELECT topic_id FROM '.$topicAuthTable;
		$sql .= '  WHERE groupid IN('.$this->groupId.') AND topic_id = ?';		
		$sql .= ' )';
		$this->db->prepare($sql);
		$this->db->bind_param('ii', $this->topicId,$this->topicId);
//		echo $this->db->mPrepareQuery;
		$result = $this->db->execute();

		return (bool)$this->db->getRowsNum($result);
	}

/* 	public function topicPost() {
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
	} */
	public function topicModify() {
		if (!$this->topicId) {
			return false;
		}
		require_once dirname(__FILE__).'/../manager/topic-manager.php';
		
		
		$topicM = new TopicManager();
		$topic = $topicM->getTopic($this->topicId);
		$topic_lang = $topic->getOriginalLanguage();
		$language = $this->languageManager->getSelectedLanguage();
	
		if ($this->topicAccess()&& $topic_lang != $language) {
			return true;
		}
		return false;
	}
	public function topicEdit() {
		if (!$this->topicId) {
			return false;
		}
		require_once dirname(__FILE__).'/../manager/topic-manager.php';
		
		
		$topicM = new TopicManager();
		$topic = $topicM->getTopic($this->topicId);
		$topic_lang = $topic->getOriginalLanguage();
		$language = $this->languageManager->getSelectedLanguage();
			
		if ($this->topicAccess()&& $topic_lang == $language) {
			return true;
		}
		return false;
	}
	public function topicDelete() {
		if ($this->isAdmin()) {
			return true;
		}
		return false;
	}
	public function postCreate() {
		
		if($this->topicAccess()){
			return true;
		}
		return false;
	}
	
		public function postAccess(){
		if ($this->isAdmin()) {
			return true;
		}

		if (!$this->postId) {
			return false;
		}
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$postAccessTable = $this->db->prefix($this->moduleName.'_post_access');
		$postAuthTable = $this->db->prefix($this->moduleName.'_post_auth');
		$sql  = '';
		$sql .= ' SELECT * FROM '.$postsTable.' ';
		$sql .= ' WHERE delete_flag = 0 AND post_id IN( ';
		$sql .= '  SELECT post_id FROM '.$postAccessTable;
		$sql .= '  WHERE `all` = 1 AND post_id = ? ';
		$sql .= '  UNION SELECT post_id FROM '.$postAuthTable;
		$sql .= '  WHERE groupid IN('.$this->groupId.') AND post_id = ?';		
		$sql .= ' )';
		$this->db->prepare($sql);
		$this->db->bind_param('ii', $this->postId,$this->postId);
		$result = $this->db->execute();

		return (bool)$this->db->getRowsNum($result);
	}
	
	public function postModify() {
		if (!$this->postId) {
			return false;
		}
		require_once dirname(__FILE__).'/../manager/post-manager.php';
		
		$postM = new PostManager();
		$post = $postM->getPost($this->postId);
		$post_lang = $post->getOriginalLanguage();
		$language = $this->languageManager->getSelectedLanguage();

		if ($this->postAccess() && $post_lang != $language) {
			return true;
		}
		return false;
	}
	
	public function postEdit() {
		if (!$this->postId) {
			return false;
		}
		require_once dirname(__FILE__).'/../manager/post-manager.php';
		
		$postM = new PostManager();
		$post = $postM->getPost($this->postId);
		$post_lang = $post->getOriginalLanguage();
		$language = $this->languageManager->getSelectedLanguage();

		if ($this->postAccess() && $post_lang == $language) {
			return true;
		}
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
		
	public function settingPermission($type){
		$log_uid = $this->root->mContext->mXoopsUser->get('uid');

		if(preg_match("/_create$/", $type)||$type=="post_reply"){
			return true;
		}else if($type=="category_edit"){
			if(!$this->categoryId){
				return false;
			}
			require_once dirname(__FILE__).'/../manager/category-manager.php';
			$categoryM = new CategoryManager();
			$category = $categoryM->getCategory($this->categoryId);
			$uid = $category->getUid();
		}else if($type=="forum_edit"){
			if(!$this->forumId){
				return false;
			}
			require_once dirname(__FILE__).'/../manager/forum-manager.php';
			$forumM = new ForumManager();
			$forum = $forumM->getForum($this->forumId);
			$uid = $forum->getUser()->getId();						
		}else if($type=="topic_edit"){
			if(!$this->topicId){
				return false;
			}
			require_once dirname(__FILE__).'/../manager/topic-manager.php';
			
			$topicM = new TopicManager();
			$topic = $topicM->getTopic($this->topicId);
			$uid = $topic->getUid();			
		}else if($type=="post_edit"){
			if(!$this->postId){
				return false;
			}
			require_once dirname(__FILE__).'/../manager/post-manager.php';
			
			$postM = new PostManager();
			$post = $postM->getPost($this->postId);
			$uid = $post->getUser()->getId();	

		}else{
			$uid = 1;
			$log_uid =0;
		}
		if($this->isAdmin()||$uid==$log_uid){
			return true;
		}
		return false;
		
	}

	public function isAdmin() {
		return $this->root->mContext->mXoopsUser->isAdmin();
	}
	
	public function setCategoryId($categoryId){
		$this->categoryId = $categoryId;
	}
	public function setForumId($forumId){
		$this->forumId = $forumId;
	}
	public function setTopicId($topicId){
		$this->topicId = $topicId;
	}
}
?>