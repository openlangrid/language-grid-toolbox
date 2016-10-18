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
require_once(dirname(__FILE__).'/../../class/handler/BBS_CorrectEditHistoryHandler.class.php');

class Toolbox_BBS_CorrectEditHistoryManager extends Toolbox_AbstractManager {

	protected $m_handler;

	public function __construct($modname) {
		parent::__construct($modname);
		$this->handler =& new BBS_CorrectEditHistoryHandler($this->db,$modname);
	}

	public function getCategoryRevisions($categoryId, $offset = null, $limit = null) {
		$dist = $this->load('category', $categoryId, $offset, $limit);
		if ($dist == null) {
			return $this->getErrorResponsePayload("CategoryRevisions is not found.");
		}

		$categoryTexts = array();
		foreach ($dist as $item) {
			$text = new ToolboxVO_BBS_CategoryText();
			$text->date = $item['create_date'];
			$text->creator = $item['user_id'];
			$expArray = array();
			foreach ($item['body'] as $key => $body) {
				$exp = new ToolboxVO_BBS_CategoryExpression();
				$exp->language = $key;
				$exp->title = $body['title'];
				$exp->description = $body['description'];
				$expArray[] = $exp;
			}
			$text->expression = $expArray;
			$categoryTexts[] = $text;
		}
		return $this->getResponsePayload($categoryTexts);
	}

	public function getForumRevisions($forumId, $offset = null, $limit = null) {
		$dist = $this->load('forum', $forumId, $offset, $limit);
		if ($dist == null) {
			return $this->getErrorResponsePayload("ForumRevisions is not found.");
		}

		$texts = array();
		foreach ($dist as $item) {
			$text = new ToolboxVO_BBS_ForumText();
			$text->date = $item['create_date'];
			$text->creator = $item['user_id'];
			$expArray = array();
			foreach ($item['body'] as $key => $body) {
				$exp = new ToolboxVO_BBS_ForumExpression();
				$exp->language = $key;
				$exp->title = $body['title'];
				$exp->description = $body['description'];
				$expArray[] = $exp;
			}
			$text->expression = $expArray;
			$texts[] = $text;
		}
		return $this->getResponsePayload($texts);
	}

	public function getTopicRevisions($topicId, $offset = null, $limit = null) {
		$dist = $this->load('topic', $topicId, $offset, $limit);
		if ($dist == null) {
			return $this->getErrorResponsePayload("TopicRevisions is not found.");
		}

		$texts = array();
		foreach ($dist as $item) {
			$text = new ToolboxVO_BBS_TopicText();
			$text->date = $item['create_date'];
			$text->creator = $item['user_id'];
			$expArray = array();
			foreach ($item['body'] as $key => $body) {
				$exp = new ToolboxVO_BBS_TopicExpression();
				$exp->language = $key;
				$exp->title = $body['title'];
				//$exp->description = $body['description'];
				$expArray[] = $exp;
			}
			$text->expression = $expArray;
			$texts[] = $text;
		}
		return $this->getResponsePayload($texts);
	}

	public function getPostRevisions($postId, $offset = null, $limit = null) {
		$dist = $this->load('post', $postId, $offset, $limit);
		if ($dist == null) {
			return $this->getErrorResponsePayload("Post(Message)Revisions is not found.");
		}

		$texts = array();
		foreach ($dist as $item) {
			$text = new ToolboxVO_BBS_MessageText();
			$text->date = $item['create_date'];
			$text->creator = $item['user_id'];
			$expArray = array();
			foreach ($item['body'] as $key => $body) {
				$exp = new ToolboxVO_BBS_MessageExpression();
				$exp->language = $key;
				$exp->body = $body['description'];
				$expArray[] = $exp;
			}
			$text->expression = $expArray;
			$texts[] = $text;
		}
		return $this->getResponsePayload($texts);
	}

	/**
	 *
	 * @return
	 * Array (
	 * [#history_count#] => Array (
	 *  [proc_type_cd] => #proc_type_cd#
	 *  [user_id] => #user_id#
	 *  [create_date] => #create_date#
	 *  [body] => Array (
	 *   [#language_code#] => Array (
	 *    [title] => #bbs_text#
	 *    [description] => #bbs_text#
	 *   )
	 *  )
	 * )
	 *)
	 */
	function load($type, $id, $offset = null, $limit = null) {

		if (empty($id)) {
			return null;
		}

		$titleCd = "";
		$descCd = "";

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$mCriteria->addSort('history_count');

		switch ( $type ) {
			case "category":
				$c = new CriteriaCompo();
				$c->add(new Criteria('bbs_item_type_cd', '01'), 'OR');
				$c->add(new Criteria('bbs_item_type_cd', '02'), 'OR');
				$mCriteria->add($c);
				$mCriteria->add(new Criteria('bbs_id', $id));
				$titleCd = "01";
				$descCd = "02";
				break;
			case "forum":
				$c = new CriteriaCompo();
				$c->add(new Criteria('bbs_item_type_cd', '03'), 'OR');
				$c->add(new Criteria('bbs_item_type_cd', '04'), 'OR');
				$mCriteria->add($c);
				$mCriteria->add(new Criteria('bbs_id', $id));
				$titleCd = "03";
				$descCd = "04";
				break;
			case "topic":
				$c = new CriteriaCompo();
				$c->add(new Criteria('bbs_item_type_cd', '05'), 'OR');
				$c->add(new Criteria('bbs_item_type_cd', '06'), 'OR');
				$mCriteria->add($c);
				$mCriteria->add(new Criteria('bbs_id', $id));
				$titleCd = "05";
				$descCd = "06";
				break;
			case "post":
				$mCriteria->add(new Criteria('bbs_item_type_cd', '07'));
				$mCriteria->add(new Criteria('bbs_id', $id));
				$descCd = "07";
				break;
			default:
				return null;
				break;
		}

		$objects =& $this->handler->getObjects($mCriteria, $limit, $offset);

		$dist = array();// key is history_count
		foreach ($objects as $object) {
			$historyCnt = $object->get('history_count');
			if (!array_key_exists($historyCnt, $dist)) {
				$dist[$historyCnt] = array();
			}
			$dist[$historyCnt]['proc_type_cd'] = $object->get('proc_type_cd');
			$dist[$historyCnt]['user_id'] = $object->get('user_id');
			$dist[$historyCnt]['create_date'] = $object->get('create_date');

			if (!isset($dist[$historyCnt]['body'])) {
				$dist[$historyCnt]['body'] = array();
			}
			$language = $object->get('language_code');
			if (!isset($dist[$historyCnt]['body'][$language])) {
				$dist[$historyCnt]['body'][$language] = array();
			}

			$typeCd = $object->get('bbs_item_type_cd');
			if ($typeCd == $titleCd) {
				$dist[$historyCnt]['body'][$language]['title'] = $object->get('bbs_text');
			}
			if ($typeCd == $descCd) {
				$dist[$historyCnt]['body'][$language]['description'] = $object->get('bbs_text');
			}
		}

		return $dist;
	}

}
?>