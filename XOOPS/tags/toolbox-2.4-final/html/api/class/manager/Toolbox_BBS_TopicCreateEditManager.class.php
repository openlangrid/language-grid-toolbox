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

require_once(dirname(__FILE__).'/Toolbox_BBS_AbstractManager.class.php');

class Toolbox_BBS_TopicCreateEditManager extends Toolbox_BBS_AbstractManager {

	public function __construct($modname) {
		parent::__construct($modname);
	}

	/*
	 * Create topic
	 */
	function create($forumId, $language, $expressions) {

		$object =& $this->m_topicHandler->create(true);
		$object->set('forum_id', $forumId);
		$object->set('uid', $this->root->mContext->mXoopsUser->get('uid'));
		$object->set('topic_original_language', $language);
		$object->set('create_date', time());

		if (!$this->m_topicHandler->insert($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		foreach ($expressions as $exp) {
			$body =& $this->m_topicBodyHandler->create(true);
			$body->set('topic_id', $object->get('topic_id'));
			$body->set('language_code', $exp->language);
			$body->set('title', $exp->title);

			if (!$this->m_topicBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setCreateLog($object->get('topic_id'), $exp->language, $exp->title);
		}

		$access =& $this->m_topicAccessHandler->create(true);
		$access->set('topic_id', $object->get('topic_id'));
		$access->set('all', '1');
		$access->set('can_post', '1');
		$access->set('can_edit', '1');
		$access->set('can_delete', '1');

		if (!$this->m_topicAccessHandler->insert($access, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		return $this->getResponsePayload($this->topicObject2responseVO($object));
	}

	/*
	 * Edit topic
	 */
	function update($topicId, $expressions) {
		$topicObj =& $this->m_topicHandler->get($topicId);

		foreach ($expressions as $exp) {
			$pkArray = array('topic_id'=>$topicId, 'language_code'=>$exp->language);
			$body =& $this->m_topicBodyHandler->get($pkArray);
			$body->set('title', $exp->title);
			if (!$this->m_topicBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setEditLog($topicId, $exp->language, $exp->title);
		}

		$topicObj->set('update_date', time());
		if (!$this->m_topicHandler->insert($topicObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$topicObj =& $this->m_topicHandler->get($topicId);
		return $this->getResponsePayload($this->topicObject2responseVo($topicObj));
	}

	/*
	 * Modify topic
	 */
	function modify($topicId, $expressions) {
		$topicObj =& $this->m_topicHandler->get($topicId);

		foreach ($expressions as $exp) {
			$pkArray = array('topic_id'=>$topicId, 'language_code'=>$exp->language);
			$body =& $this->m_topicBodyHandler->get($pkArray);
			$body->set('title', $exp->title);
			if (!$this->m_topicBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setModifyLog($topicId, $exp->language, $exp->title);
		}

		$topicObj->set('update_date', time());
		if (!$this->m_topicHandler->insert($topicObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$topicObj =& $this->m_topicHandler->get($topicId);
		return $this->getResponsePayload($this->topicObject2responseVo($topicObj));
	}

	/*
	 * Remove topic
	 */
	function remove($topicId) {
		$object =& $this->m_topicHandler->get($topicId);
		if (!$this->m_topicHandler->delete($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		if (!$this->m_postHandler->deleteByTopicId($topicId, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		return $this->getResponsePayload(true);
		//return true;
	}

	private function _setCreateLog($id, $language, $title) {
		$this->setLog($id, EnumBBSItemTypeCode::$topicTitle, $language, EnumProcessTypeCode::$new, $title);
	}

	private function _setEditLog($id, $language, $title) {
		$this->setLog($id, EnumBBSItemTypeCode::$topicTitle, $language, EnumProcessTypeCode::$edit, $title);
	}

	private function _setModifyLog($id, $language, $title) {
		$this->setLog($id, EnumBBSItemTypeCode::$topicTitle, $language, EnumProcessTypeCode::$modify, $title);
	}

}
?>