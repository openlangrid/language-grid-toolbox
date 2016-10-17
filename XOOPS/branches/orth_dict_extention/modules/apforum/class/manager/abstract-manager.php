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
require_once dirname(__FILE__).'/../model/group.php';
abstract class AbstractManager {

	protected $root;
	protected $db;
	protected $moduleName;
	protected $isadmin;
	protected $user_gids;
	protected function __construct() {
		$this->root = XCube_Root::getSingleton();
		$this->db = Database::getInstance();
//		$this->languageManager = D3LanguageManager::getInstance();
		$this->languageManager = new LanguageManager();
		//$this->moduleName = basename(realpath(dirname(__FILE__).'/../../'));
		$this->moduleName = USE_TABLE_PREFIX;
		$this->isadmin = $this->root->mContext->mXoopsUser->isAdmin();
		$this->user_gids=is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
	}
/* 	protected function getAllGroupIds() {
		$groupsTable = $this->db->prefix('groups');
		$sql = ' SELECT groupid FROM '.$groupsTable;
		$result = $this->db->query($sql);
		$groupIds = array();
		while($row = $this->db->fetchArray($result)) {
			$groupIds[] = $row['groupid'];
		}
		return $groupIds;
	}
 */	
	
	protected function getGroupIds(){
		global $xoopsModuleConfig;
		$groupsTable = $this->db->prefix('groups');
		
		$sql  = '';
		$sql .= ' SELECT ';
		$sql .= ' groupid ';
		$sql .= ' FROM '.$groupsTable;
		$result = $this->db->query($sql);
		
		$admin_check_group = $xoopsModuleConfig['notCheckGroup'];
		$groupIds = array();
		while ($row = $this->db->fetchArray($result)){
			$groupIds[] = $row['groupid'];
		}
		if(is_array($admin_check_group)){
			$groupIds = array_diff($groupIds,$admin_check_group);
		}
		return $groupIds;
	}
	
	/**
	 * @return Array
	 */
	protected function getGroups(){
		global $xoopsModuleConfig;
		$groupsTable = $this->db->prefix('groups');
		
		$sql  = '';
		$sql .= ' SELECT ';
		$sql .= ' groupid, ';
		$sql .= ' description ';
		$sql .= ' FROM '.$groupsTable;
		$result = $this->db->query($sql);
		
		$admin_check_group = $xoopsModuleConfig['notCheckGroup'];
		$groups = array();
		while ($row = $this->db->fetchArray($result)){
			if(!is_array($admin_check_group)||!in_array($row['groupid'],$admin_check_group )){
				$group = new Group($row);
				$groups[] = $group;
			}
		}
	
		return $groups;
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
		$categoryAuthTable = $this->db->prefix($this->moduleName.'_category_auth');
		$sql  = '';
		$sql .= ' SELECT cat_id FROM '.$categoriesTable.' ';
		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= '  JOIN (SELECT cat_id FROM '.$categoryAccessTable.' WHERE `all` = 1 ';
			$sql .= '		UNION SELECT cat_id FROM '.$categoryAuthTable.' WHERE groupid IN('.implode(',',$this->user_gids).')';
			$sql .= ' ) AS T1 USING (cat_id) ';
		}
		$sql .= ' WHERE delete_flag = 0 ';
		$this->db->prepare($sql);
		$result = $this->db->execute();

		$categoryIds = array(0);
		while ($row = $this->db->fetchArray($result)) {
			$categoryIds[] = $row['cat_id'];
		}
		return $categoryIds;
	}
	protected function getAllowedForumIds() {
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');
		$forumAccessTable = $this->db->prefix($this->moduleName.'_forum_access');
		$forumAuthTable = $this->db->prefix($this->moduleName.'_forum_auth');
		//$allowedCategoryIdsCSV = implode(',', $this->getAllowedCategoryIds());

		$sql  = '';
		$sql .= ' SELECT forum_id FROM '.$forumsTable.' ';
		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= ' JOIN (SELECT forum_id FROM '.$forumAccessTable.' WHERE `all` = 1';
			$sql .= ' UNION SELECT forum_id FROM '.$forumAuthTable.' WHERE groupid IN('.implode(',',$this->user_gids).')';
			$sql .= ' ) AS T1 USING(forum_id)';
		}
		$sql .= ' WHERE delete_flag = 0 ';
		$this->db->prepare($sql);

		$result = $this->db->execute();

		$forumIds = array(0);
		while ($row = $this->db->fetchArray($result)) {
			$forumIds[] = $row['forum_id'];
		}
		return $forumIds;
	}
	protected function getAllowedTopicIds() {
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$topicAccessTable = $this->db->prefix($this->moduleName.'_topic_access');
		$topicAuthTable = $this->db->prefix($this->moduleName.'_topic_auth');
		//$allowedForumIdsCSV = implode(',', $this->getAllowedForumIds());

		$sql  = '';
		$sql .= ' SELECT topic_id FROM '.$topicsTable.' ';
		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= '  JOIN (SELECT topic_id FROM '.$topicAccessTable.' WHERE  `all` = 1 ';
			$sql .= '  UNION SELECT topic_id FROM '.$topicAuthTable.' WHERE groupid IN('.implode(',',$this->user_gids).')';
			$sql .= ' ) AS T1 USING (topic_id)';
		} else {
			$sql .= ' WHERE delete_flag = 0 ';
		}
		$this->db->prepare($sql);
		$this->db->bind_param('s', $this->getGroupId);
		$result = $this->db->execute();
		
		$topicIds = array(0);
		while ($row = $this->db->fetchArray($result)) {
			$topicIds[] = $row['topic_id'];
		}
		return $topicIds;
	}
	
	protected function getAllowedPostIds() {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$postAccessTable = $this->db->prefix($this->moduleName.'_post_access');
		$postAuthTable = $this->db->prefix($this->moduleName.'_post_auth');
		
		$sql  = '';
		$sql .= ' SELECT post_id FROM '.$postsTable.' ';
		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= '  JOIN (SELECT post_id FROM '.$postAccessTable.' WHERE  `all` = 1 ';
			$sql .= '  UNION SELECT post_id FROM '.$postAuthTable.' WHERE groupid IN('.implode(',',$this->user_gids).')';
			$sql .= ' ) AS T1 USING (post_id)';
		} else {
			$sql .= ' WHERE delete_flag = 0 ';
		}
		$this->db->prepare($sql);
		$this->db->bind_param('s', $this->getGroupId);
		$result = $this->db->execute();
		
		$postIds = array(0);
		while ($row = $this->db->fetchArray($result)) {
			$postIds[] = $row['post_id'];
		}
		return $postIds;
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