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

require_once(dirname(__FILE__).'/Toolbox_AbstractManager.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_CategoriesHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_CategoriesBodyHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_CategoryAccessHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_ForumsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_ForumsBodyHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_ForumAccessHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_TopicsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_TopicsBodyHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_TopicAccessHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_PostsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_PostsBodyHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_PostFileHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/Profile_UsersHandler.class.php');

require_once(dirname(__FILE__).'/../../class/handler/BBS_TagSetsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_TagSetExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_TagsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_TagExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/BBS_TagRelationsHandler.class.php');

abstract class Toolbox_BBS_AbstractManager extends Toolbox_AbstractManager {

	protected $m_categoryHandler;
	protected $m_categoryBodyHandler;
	protected $m_categoryAccessHandler;
	protected $m_forumHandler;
	protected $m_forumBodyHandler;
	protected $m_forumAccessHandler;
	protected $m_topicHandler;
	protected $m_topicBodyHandler;
	protected $m_topicAccessHandler;
	protected $m_postHandler;
	protected $m_postBodyHandler;
	protected $m_postFileHandler;
	protected $m_userHandler;
	protected $m_modName;

	protected $m_tagSetsHandler;
	protected $m_tagSetExpressionsHandler;
	protected $m_tagsHandler;
	protected $m_tagExpressionsHandler;
	protected $m_tagRelationsHandler;

	public function __construct($modname) {
		parent::__construct();
		$this->m_modName = $modname;

		if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$this->m_modName.'/class/history/bbs-edit-history-manager.php')) {
			require_once XOOPS_ROOT_PATH.'/modules/'.$this->m_modName.'/config.php';
			require_once XOOPS_ROOT_PATH.'/modules/'.$this->m_modName.'/class/history/bbs-edit-history-manager.php';
			require_once XOOPS_ROOT_PATH.'/modules/'.$this->m_modName.'/class/history/enum-bbs-item-type-code.php';
			require_once XOOPS_ROOT_PATH.'/modules/'.$this->m_modName.'/class/history/enum-process-type-code.php';
		} else {
			die('bbs edit history manager file is not found.');
		}

		$this->m_categoryHandler =& new BBS_CategoriesHandler($this->db,$this->m_modName);
		$this->m_categoryBodyHandler =& new BBS_CategoriesBodyHandler($this->db,$this->m_modName);
		$this->m_categoryAccessHandler =& new BBS_CategoryAccessHandler($this->db,$this->m_modName);
		$this->m_forumHandler =& new BBS_ForumsHandler($this->db,$this->m_modName);
		$this->m_forumBodyHandler =& new BBS_ForumsBodyHandler($this->db,$this->m_modName);
		$this->m_forumAccessHandler =& new BBS_ForumAccessHandler($this->db,$this->m_modName);
		$this->m_topicHandler =& new BBS_TopicsHandler($this->db,$this->m_modName);
		$this->m_topicBodyHandler =& new BBS_TopicsBodyHandler($this->db,$this->m_modName);
		$this->m_topicAccessHandler =& new BBS_TopicAccessHandler($this->db,$this->m_modName);
		$this->m_postHandler =& new BBS_PostsHandler($this->db,$this->m_modName);
		$this->m_postBodyHandler =& new BBS_PostsBodyHandler($this->db,$this->m_modName);
		$this->m_postFileHandler =& new BBS_PostFileHandler($this->db,$this->m_modName);
		$this->m_userHandler =& new Profile_UsersHandler($this->db,$this->m_modName);

		$this->m_tagSetsHandler =& new BBS_TagSetsHandler($this->db, $this->m_modName);
		$this->m_tagSetExpressionsHandler =& new BBS_TagSetExpressionsHandler($this->db,$this->m_modName);
		$this->m_tagsHandler =& new BBS_TagsHandler($this->db, $this->m_modName);
		$this->m_tagExpressionsHandler =& new BBS_TagExpressionsHandler($this->db, $this->m_modName);
		$this->m_tagRelationsHandler =& new BBS_TagRelationsHandler($this->db, $this->m_modName);
	}

	protected function setLog($bbsId, $bbsItemTypeCode, $languageCode, $processTypeCode, $bbsText) {
		$bbsEditHistoryManager = new BBSEditHistoryManager();
		$bbsEditHistoryManager->registerModificationHistory(
			$bbsId, $bbsItemTypeCode, $languageCode
			, $processTypeCode, $bbsText, $this->root->mContext->mXoopsUser->get('uid')
		);
	}


	protected function categoryObject2responseVo($object) {
		$category =& new ToolboxVO_BBS_Category();
		$category->id = $object->get('cat_id');
		$category->language = $object->get('cat_original_language');
		$category->date = $object->get('update_date');
		$category->creator = $this->getUname('1');	// make can admin only

		if (!$object->m_bodyLoaded) {
			$object->_loadBody($this->m_modName);
		}
		$bodys =& $object->body;

		$text =& new ToolboxVO_BBS_Text();
		$text->date = $object->get('create_date');
		$text->creator = $this->getUname('1');		// make can admin only
		foreach ($bodys as $body) {
			$exp =& new ToolboxVO_BBS_CategoryExpression();
			$exp->title = $body->get('title');
			$exp->description = $body->get('description');
			$exp->language = $body->get('language_code');
			$text->addExpression($exp);
		}
		$category->text = $text;

		$cris =& new CriteriaCompo();
		$cris->add(new Criteria('cat_id', $object->get('cat_id')));
		$cris->add(new Criteria('delete_flag', '0'));
		$category->forumCount = $this->m_forumHandler->getCount($cris);

		$category->topicCount = $this->m_topicHandler->getCountByCategoryId($object->get('cat_id'));
		$category->messageCount = $this->m_postHandler->getCountByCategoryId($object->get('cat_id'));

//		$category->forumCount = 0;
//		$category->topicCount = 0;
//		$category->messageCount = 0;

		return $category;
	}

	protected function forumObject2responseVO($object) {
		$forum =& new ToolboxVO_BBS_Forum();
		$forum->id = $object->get('forum_id');
		$forum->categoryId = $object->get('cat_id');
		$forum->language = $object->get('forum_original_language');
		$forum->date = $object->get('update_date');
		$forum->creator = $this->getUname($object->get('uid'));

		if (!$object->m_bodyLoaded) {
			$object->_loadBody($this->m_modName);
		}
		$bodys =& $object->body;

		$text =& new ToolboxVO_BBS_Text();
		$text->date = $object->get('update_date');
		$text->creator = $this->getUname($object->get('uid'));
		foreach ($bodys as $body) {
			$exp =& new ToolboxVO_BBS_ForumExpression();
			$exp->title = $body->get('title');
			$exp->description = $body->get('description');
			$exp->language = $body->get('language_code');
			$text->addExpression($exp);
		}
		$forum->text = $text;

		$crt =& new CriteriaCompo();
		$crt->add(new Criteria('forum_id', $object->get('forum_id')));
		$crt->add(new Criteria('delete_flag', '0'));
		$forum->topicCount = $this->m_topicHandler->getCount($crt);

		$forum->messageCount = $this->m_postHandler->getCountByForumId($object->get('forum_id'));

//		$forum->topicCount = 0;
//		$forum->messageCount = 0;

		return $forum;
	}

	protected function topicObject2responseVO($object) {
		$topic =& new ToolboxVO_BBS_Topic();
		$topic->id = $object->get('topic_id');
		$topic->forumId = $object->get('forum_id');
		$topic->language = $object->get('topic_original_language');
		$topic->date = $object->get('update_date');
		$topic->creator = $this->getUname($object->get('uid'));

		if (!$object->m_bodyLoaded) {
			$object->_loadBody($this->m_modName);
		}
		$bodys =& $object->body;

		$text =& new ToolboxVO_BBS_Text();
		$text->date = $object->get('update_date');
		$text->creator = $this->getUname($object->get('uid'));
		foreach ($bodys as $body) {
			$exp =& new ToolboxVO_BBS_TopicExpression();
			$exp->title = $body->get('title');
			$exp->language = $body->get('language_code');
			$text->addExpression($exp);
		}
		$topic->text = $text;

		$topic->messageCount = $this->m_postHandler->getCountByTopicId($object->get('topic_id'));

//		$topic->messageCount = 0;

		return $topic;
	}

	protected function postObject2responseVO($object) {
		$message =& new ToolboxVO_BBS_Message();
		$message->id = $object->get('post_id');
		$message->topicId = $object->get('topic_id');
		$message->language = $object->get('post_original_language');
		$message->date = $object->get('post_time');
		$message->creator = $this->getUname($object->get('uid'));
		$message->postOrder = $object->get('post_order');
		$message->originalMessageId = ($object->get('reply_post_id') == 0) ? null :$object->get('reply_post_id');

		$message->replyMessageIds = $this->m_postHandler->getReplyMessageIds($object->get('post_id'));


		if (!$object->m_bodyLoaded) {
			$object->_loadBody($this->m_modName);
		}
		$bodys =& $object->body;

		$text =& new ToolboxVO_BBS_MessageText();
		$text->date = $object->get('post_time');
		$text->creator = $this->getUname($object->get('uid'));
		foreach ($bodys as $body) {
			$exp = new ToolboxVO_BBS_MessageExpression();
			$exp->language = $body->get('language_code');
			$exp->body = $body->get('description');
			$text->addExpression($exp);
		}
		$message->text = $text;

		return $message;
	}

	protected function getUname($uid) {
		$obj =& $this->m_userHandler->get($uid);
		if ($obj != null) {
			return $obj->get('uname');
		}
	}

	protected function tagSetObject2responseVO($object) {
		$tagSet = new ToolboxVO_BBS_TagSet();
		$tagSet->id = $object->get('tag_set_id');

		if ($object->mExpressionsLoaded == false) {
			$object->_loadExpressions($this->m_modName);
		}

		$exps = array();
		foreach ($object->mExpressions as $expObj) {
			$exp = new ToolboxVO_BBS_TagExpression();
			$exp->language = $expObj->get('language_code');
			$exp->expression = $expObj->get('expression');
			$exps[] = $exp;
		}
		$tagSet->name = $exps;

		if ($object->mTagsLoaded == false) {
			$object->_loadTags($this->m_modName);
		}

		$tags = array();
		foreach ($object->mTags as $tagObj) {
			$tags[] = $this->tagObject2responseVO($tagObj);
		}
		$tagSet->words = $tags;

		return $tagSet;
	}

	protected function tagObject2responseVO($object) {
		$tag = new ToolboxVO_BBS_Tag();
		$tag->id = $object->get('tag_id');
		$tag->tagSetId = $object->get('tag_set_id');

		if ($object->mExpressionsLoaded == false) {
			$object->_loadExpressions($this->m_modName);
		}

		$exps = array();
		foreach ($object->mExpressions as $expObj) {
			$exp = new ToolboxVO_BBS_TagExpression();
			$exp->language = $expObj->get('language_code');
			$exp->expression = $expObj->get('expression');
			$exps[] = $exp;
		}

		$tag->expressions = $exps;

		return $tag;
	}

}
?>