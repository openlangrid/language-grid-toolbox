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

class Toolbox_BBS_ForumCreateEditManager extends Toolbox_BBS_AbstractManager {

	public function __construct($modname) {
		parent::__construct($modname);
	}

	/*
	 * Create forum
	 */
	function create($categoryId, $language, $expressions) {

		$object =& $this->m_forumHandler->create(true);
		$object->set('cat_id', $categoryId);
		$object->set('uid', $this->root->mContext->mXoopsUser->get('uid'));
		$object->set('forum_original_language', $language);
		$object->set('create_date', time());

		if (!$this->m_forumHandler->insert($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		foreach ($expressions as $exp) {
			$body =& $this->m_forumBodyHandler->create(true);
			$body->set('forum_id', $object->get('forum_id'));
			$body->set('language_code', $exp->language);
			$body->set('title', $exp->title);
			$body->set('description', $exp->description);

			if (!$this->m_forumBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setCreateLog($object->get('forum_id'), $exp->language, $exp->title, $exp->description);
		}

		$access =& $this->m_forumAccessHandler->create(true);
		$access->set('forum_id', $object->get('forum_id'));
		$access->set('all', '1');
		$access->set('can_post', '1');

		if (!$this->m_forumAccessHandler->insert($access, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		return $this->getResponsePayload($this->forumObject2responseVO($object));
	}

	/*
	 * Edit forum
	 */
	function update($forumId, $expressions) {
		$forumObj =& $this->m_forumHandler->get($forumId);

		foreach ($expressions as $exp) {
			$pkArray = array('forum_id'=>$forumId, 'language_code'=>$exp->language);
			$body =& $this->m_forumBodyHandler->get($pkArray);
			$body->set('title', $exp->title);
			$body->set('description', $exp->description);
			if (!$this->m_forumBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setEditLog($forumId, $exp->language, $exp->title, $exp->description);
		}

		$forumObj->set('update_date', time());
		if (!$this->m_forumHandler->insert($forumObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$forumObj =& $this->m_forumHandler->get($forumId);
		return $this->getResponsePayload($this->forumObject2responseVo($forumObj));
	}

	/*
	 * Modify forum
	 */
	function modify($forumId, $expressions) {
		$forumObj =& $this->m_forumHandler->get($forumId);

		foreach ($expressions as $exp) {
			$pkArray = array('forum_id'=>$forumId, 'language_code'=>$exp->language);
			$body =& $this->m_forumBodyHandler->get($pkArray);
			$body->set('title', $exp->title);
			$body->set('description', $exp->description);
			if (!$this->m_forumBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setModifyLog($forumId, $exp->language, $exp->title, $exp->description);
		}

		$forumObj->set('update_date', time());
		if (!$this->m_forumHandler->insert($forumObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$forumObj =& $this->m_forumHandler->get($forumId);
		return $this->getResponsePayload($this->forumObject2responseVo($forumObj));
	}

	/*
	 * Remove forum
	 */
	function remove($forumId) {
		$object =& $this->m_forumHandler->get($forumId);
		if (!$this->m_forumHandler->delete($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		if (!$this->m_topicHandler->deleteByForumId($forumId, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		if (!$this->m_postHandler->deleteByForumId($forumId, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		return $this->getResponsePayload(true);
		//return true;
	}

	private function _setCreateLog($id, $language, $title, $description) {
		$this->setLog($id, EnumBBSItemTypeCode::$forumTitle, $language, EnumProcessTypeCode::$new, $title);
		$this->setLog($id, EnumBBSItemTypeCode::$forumDescription, $language, EnumProcessTypeCode::$new, $description);
	}

	private function _setEditLog($id, $language, $title, $description) {
		$this->setLog($id, EnumBBSItemTypeCode::$forumTitle, $language, EnumProcessTypeCode::$edit, $title);
		$this->setLog($id, EnumBBSItemTypeCode::$forumDescription, $language, EnumProcessTypeCode::$edit, $description);
	}

	private function _setModifyLog($id, $language, $title, $description) {
		$this->setLog($id, EnumBBSItemTypeCode::$forumTitle, $language, EnumProcessTypeCode::$modify, $title);
		$this->setLog($id, EnumBBSItemTypeCode::$forumDescription, $language, EnumProcessTypeCode::$modify, $description);
	}

}
?>