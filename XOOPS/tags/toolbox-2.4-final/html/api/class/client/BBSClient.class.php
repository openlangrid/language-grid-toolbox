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
require_once(dirname(__FILE__).'/../../IBBSClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_CategoryGetAllManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_CategoryCreateEditManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_ForumGetAllManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_ForumCreateEditManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_TopicGetAllManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_TopicCreateEditManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_PostGetAllManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_PostCreateEditManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_CorrectEditHistoryManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_BBS_TagManager.class.php');

class BBSClient extends Toolbox_AbstractClient implements IBBSClient {

	protected $m_selectedLanguage;
	protected $m_modname;

	public function __construct($moduleName) {
		parent::__construct();
		if (isset($_COOKIE["selectedLanguage"])) {
			$this->m_selectedLanguage = $_COOKIE["selectedLanguage"];
		} else {
			$this->m_selectedLanguage = 'en';
		}

		//module name(module path) check
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;
		$rs = mysql_list_tables(XOOPS_DB_NAME);
		$isTableExists = false;
		while($result = $db->fetchRow( $rs )){
			if($result[0] == $db->prefix($moduleName."_categories")){
				$isTableExists = true;
				break;
			}
		}
		if(!$isTableExists){
			//die("modulename is invalid on create BBSClient");
			$moduleName = forum;
		}

		$this->m_modname = $moduleName;
	}

	public function getAllCategories($offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_CategoryGetAllManager($this->m_modname);
		return $manager->getCategoryList($offset,$limit);
	}

	public function getAllForums($categoryId, $offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_ForumGetAllManager($this->m_modname);
		return $manager->getForumList($categoryId, $offset, $limit);
	}

	public function getAllTopics($forumId, $offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_TopicGetAllManager($this->m_modname);
		return $manager->getTopicList($forumId, $offset, $limit);
	}
	public function getAllMessages($topicId, $offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_PostGetAllManager($this->m_modname);
		return $manager->getPostList($topicId, $offset, $limit);
	}

	public function createCategory($expressions) {
		$manager =& new Toolbox_BBS_CategoryCreateEditManager($this->m_modname);
		return $manager->create($this->m_selectedLanguage, $expressions);
	}
	public function editCategory($categoryId, $expressions) {
		$manager =& new Toolbox_BBS_CategoryCreateEditManager($this->m_modname);
		return $manager->update($categoryId, $expressions);
	}
	public function modifyCategory($categoryId, $expressions) {
		$manager =& new Toolbox_BBS_CategoryCreateEditManager($this->m_modname);
		return $manager->modify($categoryId, $expressions);
	}
	public function deleteCategory($categoryId) {
		$manager =& new Toolbox_BBS_CategoryCreateEditManager($this->m_modname);
		return $manager->remove($categoryId);
	}
	public function getCategory($categoryId) {
		$root =& XCube_Root::getSingleton();
		$handler =& new BBS_CategoriesHandler($root->mController->mDB,$this->m_modname);
		return $handler->get($categoryId);
	}

	public function createForum($categoryId, $expressions) {
		$manager =& new Toolbox_BBS_ForumCreateEditManager($this->m_modname);
		return $manager->create($categoryId, $this->m_selectedLanguage, $expressions);
	}
	public function editForum($forumId, $expressions) {
		$manager =& new Toolbox_BBS_ForumCreateEditManager($this->m_modname);
		return $manager->update($forumId, $expressions);
	}
	public function modifyForum($forumId, $expressions) {
		$manager =& new Toolbox_BBS_ForumCreateEditManager($this->m_modname);
		return $manager->modify($forumId, $expressions);
	}
	public function deleteForum($forumId) {
		$manager =& new Toolbox_BBS_ForumCreateEditManager($this->m_modname);
		return $manager->remove($forumId);
	}
	public function getForum($forumId) {
		$root =& XCube_Root::getSingleton();
		$handler =& new BBS_ForumsHandler($root->mController->mDB,$this->m_modname);
		return $handler->get($forumId);
	}

	public function createTopic($forumId, $expressions) {
		$manager =& new Toolbox_BBS_TopicCreateEditManager($this->m_modname);
		return $manager->create($forumId, $this->m_selectedLanguage, $expressions);
	}
	public function editTopic($topicId, $expressions) {
		$manager =& new Toolbox_BBS_TopicCreateEditManager($this->m_modname);
		return $manager->update($topicId, $expressions);
	}
	public function modifyTopic($topicId, $expressions) {
		$manager =& new Toolbox_BBS_TopicCreateEditManager($this->m_modname);
		return $manager->modify($topicId, $expressions);
	}
	public function deleteTopic($topicId) {
		$manager =& new Toolbox_BBS_TopicCreateEditManager($this->m_modname);
		return $manager->remove($topicId);
	}
	public function getTopic($topicId) {
		$root =& XCube_Root::getSingleton();
		$handler =& new BBS_TopicsHandler($root->mController->mDB,$this->m_modname);
		return $handler->get($topicId);
	}

	public function postMessage($topicId, $expressions, $attachments = null, $originalMessageId = null, $messageTagIds = null) {
		$manager =& new Toolbox_BBS_PostCreateEditManager($this->m_modname);
		$post = $manager->create($topicId, $this->m_selectedLanguage, $expressions, $attachments, $originalMessageId);
		if ($messageTagIds) {
			$tagManager = new Toolbox_BBS_TagManager($this->m_modname);
			$tagManager->bindTag($post['contents']->id, $messageTagIds);
		}
		return $post;
	}
	public function editMessage($messageId, $expressions) {
		$manager =& new Toolbox_BBS_PostCreateEditManager($this->m_modname);
		return $manager->update($messageId, $expressions);
	}
	public function modifyMessage($messageId, $expressions) {
		$manager =& new Toolbox_BBS_PostCreateEditManager($this->m_modname);
		return $manager->modify($messageId, $expressions);
	}
	public function deleteMessage($messageId) {
		$manager =& new Toolbox_BBS_PostCreateEditManager($this->m_modname);
		return $manager->remove($messageId);
	}
	public function getUpdatedMessages($topicId, $timestamp) {
		$manager =& new Toolbox_BBS_PostGetAllManager($this->m_modname);
		return $manager->getUpdatedPostLimit($topicId, $timestamp);
	}
	public function getMessage($messageId) {
		$root =& XCube_Root::getSingleton();
		$handler =& new BBS_PostsHandler($root->mController->mDB,$this->m_modname);
		return $handler->get($messageId);
	}
	/*
	 * <#if locale="ja">
	 * BBSClient::getMessage($messageId)関数はToolboxAPIに準拠していないのでそれの対応版です。
	 * </#if>
	 */
	public function getPostMessage($messageId) {
		$manager =& new Toolbox_BBS_PostGetAllManager($this->m_modname);
		return $manager->getPostMessage($messageId);
	}

	public function getCategoryRevisions($categoryId) {
		$manager =& new Toolbox_BBS_CorrectEditHistoryManager($this->m_modname);
		return $manager->getCategoryRevisions($categoryId);
	}
	public function getForumRevisions($forumId) {
		$manager =& new Toolbox_BBS_CorrectEditHistoryManager($this->m_modname);
		return $manager->getForumRevisions($forumId);
	}
	public function getTopicRevisions($topicId) {
		$manager =& new Toolbox_BBS_CorrectEditHistoryManager($this->m_modname);
		return $manager->getTopicRevisions($topicId);
	}
	public function getMessageRevisions($messageId) {
		$manager =& new Toolbox_BBS_CorrectEditHistoryManager($this->m_modname);
		return $manager->getPostRevisions($messageId);
	}

	public function searchCategories($text, $matchingMethod, $scope = null, $offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_CategoryGetAllManager($this->m_modname);
		//return $manager->searchCategory($text, $this->m_selectedLanguage, $matchingMethod, $scope, $offset, $limit);
		return $manager->searchCategory($text, null, $matchingMethod, $scope, $offset, $limit);
	}
	public function searchForums($text, $matchingMethod, $scope = null, $offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_ForumGetAllManager($this->m_modname);
		//return $manager->searchForum($text, $this->m_selectedLanguage, $matchingMethod, $scope, $offset, $limit);
		return $manager->searchForum($text, null, $matchingMethod, $scope, $offset, $limit);
	}
	//public function searchTopics($text, $matchingMethod, $scope = null, $offset = null, $limit = null) {
	public function searchTopics($text, $matchingMethod, $offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_TopicGetAllManager($this->m_modname);
		//return $manager->searchTopic($text, $this->m_selectedLanguage, $matchingMethod, $scope, $offset, $limit);
		return $manager->searchTopic($text, null, $matchingMethod, 'title', $offset, $limit);
	}
	public function searchMessages($text, $matchingMethod, $scope = null, $offset = null, $limit = null) {
		$manager =& new Toolbox_BBS_PostGetAllManager($this->m_modname);
		//return $manager->searchPost($text, $this->m_selectedLanguage, $matchingMethod, $scope, $offset, $limit);
		return $manager->searchPost($text, null, $matchingMethod, $scope, $offset, $limit);
	}



	/**
	 *
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_BBS_TagSet[]
	 */
	public function getAllTagSets($sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->getTagSetList($sortOrder, $orderBy, $offset, $limit);
	}

	/**
	 *
	 * @param int $id
	 * @return ToolboxVO_BBS_TagSet
	 */
	public function getTagSet($id) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->getTagSet($id);
	}

	/**
	 *
	 * @param ToolboxVO_BBS_TagExpression[] $setName
	 * @return ToolboxVO_BBS_TagSet
	 */
	public function addTagSet($setName) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->addTagSet($setName);
	}

	/**
	 *
	 * @param int $id
	 * @return void
	 */
	public function deleteTagSet($id) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->deleteTagSet($id);
	}

	/**
	 *
	 * @return void
	 */
	public function deleteAllTagSets() {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		$manager->truncate();
	}

	/**
	 *
	 * @param int $id
	 * @param ToolboxVO_BBS_TagExpression[] $setName
	 * @return ToolboxVO_BBS_TagSet
	 */
	public function updateTagSet($id, $setName) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->updateTagSet($id, $setName);
	}

	/**
	 *
	 * @param int $id
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_BBS_Tag[]
	 */
	public function getAllTags($id, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->getTagList($id, $sortOrder, $orderBy, $offset, $limit);
	}

	/**
	 *
	 * @param int $tagSetId
	 * @param int $tagId
	 * @return ToolboxVO_BBS_Tag
	 */
	public function getTag($tagSetId, $tagId) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->getTag($tagSetId, $tagId);
	}

	/**
	 *
	 * @param int $tagSetId
	 * @param ToolboxVO_BBS_TagExpression[] $tagExpressions
	 * @return ToolboxVO_BBS_Tag
	 */
	public function addTag($tagSetId, $tagExpressions) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->addTag($tagSetId, $tagExpressions);
	}

	/**
	 *
	 * @param int $tagSetId
	 * @param int $tagId
	 * @return void
	 */
	public function deleteTag($tagSetId, $tagId) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->deleteTag($tagSetId, $tagId);
	}

	/**
	 *
	 * @param int $tagSetId
	 * @return void
	 */
	public function deleteAllTags($tagSetId) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $managr->deleteTags($tagSetId);
	}

	/**
	 *
	 * @param int $tagSetId
	 * @param int $tagId
	 * @param ToolboxVO_BBS_TagExpression[]
	 * @return ToolboxVO_BBS_Tag
	 */
	public function updateTag($tagSetId, $tagId, $tagExpressions) {
		$manager = new Toolbox_BBS_TagManager($this->m_modname);
		return $manager->updateTag($tagSetId, $tagId, $tagExpressions);
	}
}
?>
