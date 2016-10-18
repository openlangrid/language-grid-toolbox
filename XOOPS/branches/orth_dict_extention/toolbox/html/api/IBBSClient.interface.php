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

interface IBBSClient {

	/**
	 *
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 */
	public function getAllCategories($offset = null, $limit = null);

	/**
	 *
	 * @param $categoryId
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 */
	public function getAllForums($categoryId, $offset = null, $limit = null);

	/**
	 *
	 * @param forumId
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 */
	public function getAllTopics($forumId, $offset = null, $limit = null);

	/**
	 *
	 * @param $topicId
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 */
	public function getAllMessages($topicId, $offset = null, $limit = null);

	/**
	 *
	 * @param $expressions
	 * @return array
	 */
	public function createCategory($expressions);

	/**
	 *
	 * @param $categoryId
	 * @param $expressions
	 * @return array
	 */
	public function editCategory($categoryId, $expressions);

	/**
	 *
	 * @param $categoryId
	 * @param $expressions
	 * @return array
	 */
	public function modifyCategory($categoryId, $expressions);

	/**
	 *
	 * @param $categoryId
	 * @return boolean
	 */
	public function deleteCategory($categoryId);

	/**
	 *
	 * @param $categoryId
	 * @param $expressions
	 * @return array
	 */
	public function createForum($categoryId, $expressions);

	/**
	 *
	 * @param $forumId
	 * @param $expressions
	 * @return array
	 */
	public function editForum($forumId, $expressions);

	/**
	 *
	 * @param $forumId
	 * @param $expressions
	 * @return array
	 */
	public function modifyForum($forumId, $expressions);

	/**
	 *
	 * @param $forumId
	 * @return boolean
	 */
	public function deleteForum($forumId);

	/**
	 *
	 * @param $forumId
	 * @param $expressions
	 * @return array
	 */
	public function createTopic($forumId, $expressions);

	/**
	 *
	 * @param $topicId
	 * @param $expressions
	 * @return array
	 */
	public function editTopic($topicId, $expressions);

	/**
	 *
	 * @param $topicId
	 * @param $expressions
	 * @return array
	 */
	public function modifyTopic($topicId, $expressions);

	/**
	 *
	 * @param $topicId
	 * @return boolean
	 */
	public function deleteTopic($topicId);

	/**
	 *
	 * @param $topicId
	 * @param $expressions
	 * @param $attachments
	 * @param $parentMessageId
	 * @return array
	 */
//	public function postMessage($topicId, $expressions, $parentMessageId = null);
//	public function postMessage($topicId, $expressions, $attachments = null, $originalMessageId = null);
	public function postMessage($topicId, $expressions, $attachments = null, $originalMessageId = null, $messageTagIds = null);

	/**
	 *
	 * @param $messageId
	 * @param $expressions
	 * @return array
	 */
	public function editMessage($messageId, $expressions);

	/**
	 *
	 * @param $messageId
	 * @param $expressions
	 * @return array
	 */
	public function modifyMessage($messageId, $expressions);

	/**
	 *
	 * @param $messageId
	 * @return boolean
	 */
	public function deleteMessage($messageId);

	/**
	 * @param $topicId
	 * @param $timestamp
	 * @return array
	 */
	public function getUpdatedMessages($topicId, $timestamp);

	/**
	 *
	 * @param $categoryId
	 * @return array
	 */
	public function getCategoryRevisions($categoryId);

	/**
	 *
	 * @param $forumId
	 * @return array
	 */
	public function getForumRevisions($forumId);

	/**
	 *
	 * @param $topicId
	 * @return array
	 */
	public function getTopicRevisions($topicId);

	/**
	 *
	 * @param $messageId
	 * @return array
	 */
	public function getMessageRevisions($messageId);

	/**
	 *
	 * @param $text
	 * @param $matchingMethod
	 * @param $scope
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 */
	public function searchCategories($text, $matchingMethod, $scope = null, $offset = null, $limit = null);

	/**
	 *
	 * @param $text
	 * @param $matchingMethod
	 * @param $scope
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 */
	public function searchForums($text, $matchingMethod, $scope = null, $offset = null, $limit = null);

	/**
	 *
	 * @param $text
	 * @param $matchingMethod
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 * 09.11.27 @param $scope remove -> title only
	 */
	public function searchTopics($text, $matchingMethod, $offset = null, $limit = null);

	/**
	 *
	 * @param $text
	 * @param $matchingMethod
	 * @param $scope
	 * @param [$offset]
	 * @param [$limit]
	 * @return array
	 */
	public function searchMessages($text, $matchingMethod, $scope = null, $offset = null, $limit = null);


	/**
	 *
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_BBS_TagSet[]
	 */
	public function getAllTagSets($sortOrder = null, $orderBy = null, $offset = null, $limit = null);

	/**
	 *
	 * @param int $id
	 * @return ToolboxVO_BBS_TagSet
	 */
	public function getTagSet($id);

	/**
	 *
	 * @param ToolboxVO_BBS_TagExpression[] $setName
	 * @return ToolboxVO_BBS_TagSet
	 */
	public function addTagSet($setName);

	/**
	 *
	 * @param int $id
	 * @return void
	 */
	public function deleteTagSet($id);

	/**
	 *
	 * @return void
	 */
	public function deleteAllTagSets();

	/**
	 *
	 * @param int $id
	 * @param ToolboxVO_BBS_TagExpression[] $setName
	 * @return ToolboxVO_BBS_TagSet
	 */
	public function updateTagSet($id, $setName);

	/**
	 *
	 * @param int $id
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_BBS_Tag[]
	 */
	public function getAllTags($id, $sortOrder = null, $orderBy = null, $offset = null, $limit = null);

	/**
	 *
	 * @param int $tagSetId
	 * @param int $tagId
	 * @return ToolboxVO_BBS_Tag
	 */
	public function getTag($tagSetId, $tagId);

	/**
	 *
	 * @param int $tagSetId
	 * @param ToolboxVO_BBS_TagExpression[] $tagExpressions
	 * @return ToolboxVO_BBS_Tag
	 */
	public function addTag($tagSetId, $tagExpressions);

	/**
	 *
	 * @param int $tagSetId
	 * @param int $tagId
	 * @return void
	 */
	public function deleteTag($tagSetId, $tagId);

	/**
	 *
	 * @param int $tagSetId
	 * @return void
	 */
	public function deleteAllTags($tagSetId);

	/**
	 *
	 * @param int $tagSetId
	 * @param int $tagId
	 * @param ToolboxVO_BBS_TagExpression[]
	 * @return ToolboxVO_BBS_Tag
	 */
	public function updateTag($tagSetId, $tagId, $tagExpressions);
}

class ToolboxVO_BBS_Category {
	var $id;
	var $text;
	var $forumCount;
	var $topicCount;
	var $messageCount;
	var $creator;
	var $language;
	var $date;
}

class ToolboxVO_BBS_Forum {
	var $id;
	var $categoryId;
	var $text;
	var $topicCount;
	var $messageCount;
	var $creator;
	var $language;
	var $date;
}

class ToolboxVO_BBS_Topic {
	var $id;
	var $forumId;
	var $text;
	var $messageCount;
	var $creator;
	var $language;
	var $date;
}

class ToolboxVO_BBS_Message {
	var $id;
	var $topicId;
	var $text;
	var $replyMessageIds;
	var $originalMessageId;
	var $creator;
	var $language;
	var $date;
}

class ToolboxVO_BBS_Text {
	var $expression;
	var $date;
	var $creator;

	function addExpression($exp) {
		$this->expression[] = $exp;
	}
}

class ToolboxVO_BBS_CategoryText extends ToolboxVO_BBS_Text {
}

class ToolboxVO_BBS_ForumText extends ToolboxVO_BBS_Text {
}

class ToolboxVO_BBS_TopicText extends ToolboxVO_BBS_Text {
}

class ToolboxVO_BBS_MessageText extends ToolboxVO_BBS_Text {
}

class ToolboxVO_BBS_Expression {
	var $title;
	var $description;
	var $language;
}

class ToolboxVO_BBS_CategoryExpression extends ToolboxVO_BBS_Expression {
}

class ToolboxVO_BBS_ForumExpression extends ToolboxVO_BBS_Expression {
}

class ToolboxVO_BBS_TopicExpression {
	var $title;
	var $language;
}

class ToolboxVO_BBS_MessageExpression {
	var $body;
	var $language;
}

class ToolboxVO_BBS_Attachment {
	var $location;
	var $name;
	var $size;
	var $type;
}

class ToolboxVO_BBS_TagExpression {
	var $language;		// string
	var $expression;	// string
}

class ToolboxVO_BBS_TagSet {
	var $id;		// integer
	var $name;		// ToolboxVO_BBS_TagExpression[]
	var $words;		// ToolboxVO_BBS_Tag[]
}

class ToolboxVO_BBS_Tag {
	var $id;			// integer
	var $expressions;	// ToolboxVO_BBS_TagExpression[]
	var $tagSetId;		// integer
}

?>
