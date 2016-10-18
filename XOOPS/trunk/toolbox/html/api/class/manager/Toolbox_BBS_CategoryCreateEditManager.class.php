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


class Toolbox_BBS_CategoryCreateEditManager extends Toolbox_BBS_AbstractManager {

	public function __construct($modname) {
		parent::__construct($modname);
	}

	/*
	 * Create category
	 */
	function create($language, $expressions) {

		$category =& $this->m_categoryHandler->create(true);
		$category->set('cat_original_language', $language);
		$category->set('create_date', time());

		if (!$this->m_categoryHandler->insert($category, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		foreach ($expressions as $exp) {
			$body =& $this->m_categoryBodyHandler->create(true);
			$body->set('cat_id', $category->get('cat_id'));
			$body->set('language_code', $exp->language);
			$body->set('title', $exp->title);
			$body->set('description', $exp->description);

			if (!$this->m_categoryBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setCreateLog($category->get('cat_id'), $exp->language, $exp->title, $exp->description);
		}

		$access =& $this->m_categoryAccessHandler->create(true);
		$access->set('cat_id', $category->get('cat_id'));
		$access->set('all', '1');
		$access->set('can_post', '1');

		if (!$this->m_categoryAccessHandler->insert($access, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		return $this->getResponsePayload($this->categoryObject2responseVo($category));
	}

	/*
	 * Edit category
	 */
	function update($categoryId, $expressions) {
		$categoryObj =& $this->m_categoryHandler->get($categoryId);

		foreach ($expressions as $exp) {
			$pkArray = array('cat_id'=>$categoryId, 'language_code'=>$exp->language);
			$body =& $this->m_categoryBodyHandler->get($pkArray);
			$body->set('title', $exp->title);
			$body->set('description', $exp->description);
			if (!$this->m_categoryBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setEditLog($categoryId, $exp->language, $exp->title, $exp->description);
		}

		$categoryObj->set('update_date', time());
		if (!$this->m_categoryHandler->insert($categoryObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$categoryObj =& $this->m_categoryHandler->get($categoryId);
		return $this->getResponsePayload($this->categoryObject2responseVo($categoryObj));
	}

	/*
	 * Modify category
	 */
	function modify($categoryId, $expressions) {
		$categoryObj =& $this->m_categoryHandler->get($categoryId);

		foreach ($expressions as $exp) {
			$pkArray = array('cat_id'=>$categoryId, 'language_code'=>$exp->language);
			$body =& $this->m_categoryBodyHandler->get($pkArray);
			$body->set('title', $exp->title);
			$body->set('description', $exp->description);
			if (!$this->m_categoryBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setModifyLog($categoryId, $exp->language, $exp->title, $exp->description);
		}

		$categoryObj->set('update_date', time());
		if (!$this->m_categoryHandler->insert($categoryObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$categoryObj =& $this->m_categoryHandler->get($categoryId);
		return $this->getResponsePayload($this->categoryObject2responseVo($categoryObj));
	}

	/*
	 * Remove category
	 */
	function remove($categoryId) {
		$categoryObj =& $this->m_categoryHandler->get($categoryId);
		if (!$this->m_categoryHandler->delete($categoryObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$bodys =& $categoryObj->body;
		foreach ($bodys as $body) {
			$this->_setDeleteLog($categoryId, $body->get('language_code'), $body->get('title'), $body->get('description'));
		}

		if (!$this->m_forumHandler->deleteByCategoryId($categoryId, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		if (!$this->m_topicHandler->deleteByCategoryId($categoryId, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		if (!$this->m_postHandler->deleteByCategoryId($categoryId, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		
		return $this->getResponsePayload(true);
		//return true;
	}

	private function _setCreateLog($categoryId, $language, $title, $description) {
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryTitle, $language, EnumProcessTypeCode::$new, $title);
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryDescription, $language, EnumProcessTypeCode::$new, $description);
	}

	private function _setEditLog($categoryId, $language, $title, $description) {
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryTitle, $language, EnumProcessTypeCode::$edit, $title);
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryDescription, $language, EnumProcessTypeCode::$edit, $description);
	}

	private function _setModifyLog($categoryId, $language, $title, $description) {
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryTitle, $language, EnumProcessTypeCode::$modify, $title);
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryDescription, $language, EnumProcessTypeCode::$modify, $description);
	}

	private function _setDeleteLog($categoryId, $language, $title, $description) {
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryTitle, $language, EnumProcessTypeCode::$delete, $title);
		$this->setLog($categoryId, EnumBBSItemTypeCode::$categoryDescription, $language, EnumProcessTypeCode::$delete, $description);
	}

}
?>