<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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

require_once(dirname(__FILE__).'/../Toolbox_ObjectGenericHandler.class.php');

class TranslationTemplate_TranslationTemplatesObject extends XoopsSimpleObject {

	public $expressions;
	public $m_expressionsLoaded = false;
	public $boundWordSetRelations;
	public $m_boundWordSetRelationsLoaded = false;
	public $categoryRelations;
	public $m_categoryRelationsLoaded = false;

	public function TranslationTemplate_TranslationTemplatesObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('resource_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('creation_time', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
	}
	
	public function _load() {
		$this->_loadExpressions();
		$this->_loadBoundWordSetRelations();
		$this->_loadCategoryRelations();
	}

	public function _loadExpressions() {
		$handler =& $this->_getExpressionsHandler();
		
		$criteria =& new CriteriaCompo();
		$criteria->add(new Criteria('translation_template_id', $this->get('id')));
		
		$this->expressions =& $handler->getObjects($criteria);
		if ($this->expressions) {
			$this->m_expressionsLoaded = true;
		}
	}
	
	public function _loadBoundWordSetRelations() {
		$handler =& $this->_getBoundWordSetRelationsHandler();
		
		$criteria =& new CriteriaCompo();
		$criteria->add(new Criteria('translation_template_id', $this->get('id')));
		
		$this->boundWordSetRelations =& $handler->getObjects($criteria);
		if ($this->boundWordSetRelations) {
			$this->m_boundWordSetRelationsLoaded = true;
		}
	}
	
	public function _loadCategoryRelations() {
		$handler =& $this->_getCategoryRelationsHandler();
		
		$criteria =& new CriteriaCompo();
		$criteria->add(new Criteria('translation_template_id', $this->get('id')));
		
		$this->categoryRelations =& $handler->getObjects($criteria);
		if ($this->categoryRelations) {
			$this->m_categoryRelationsLoaded = true;
		}
	}

	private function _getExpressionsHandler() {
		require_once(dirname(__FILE__).'/TranslationTemplate_TranslationTemplateExpressionsHandler.class.php');
		$handler =& new TranslationTemplate_TranslationTemplateExpressionsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}

	private function _getBoundWordSetRelationsHandler() {
		require_once(dirname(__FILE__).'/TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler.class.php');
		$handler =& new TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}

	private function _getCategoryRelationsHandler() {
		require_once(dirname(__FILE__).'/TranslationTemplate_CategoryTranslationTemplateRelationsHandler.class.php');
		$handler =& new TranslationTemplate_CategoryTranslationTemplateRelationsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}
}

class TranslationTemplate_TranslationTemplatesHandler extends XoopsObjectGenericHandler {

	public $mTable = "template_translation_templates";
	public $mPrimary = "id";
	public $mClass = "TranslationTemplate_TranslationTemplatesObject";

	public function &get($id) {
		$object =& parent::get($id);
		
		if ($object) {
			$object->_load();
		}
		
		return $object;
	}

	public function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			foreach ($objects as &$object) {
				$object->_load();
			}
		}
		
		return $objects;
	}
}
?>