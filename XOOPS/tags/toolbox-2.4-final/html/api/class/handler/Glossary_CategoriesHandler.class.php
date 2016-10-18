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

require_once(dirname(__FILE__).'/Toolbox_ObjectGenericHandler.class.php');

class Glossary_CategoriesObject extends XoopsSimpleObject {

	var $expressions;
	var $m_expressionsLoaded = false;
	var $relations;
	var $m_relationsLoaded = false;

	function Glossary_CategoriesObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('resource_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, '', true, 30);
	}

	function _loadExpressions() {
		$handler =& $this->_getExpressionsHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('glossary_category_id', $this->get('id')));
		$this->expressions =& $handler->getObjects($mCriteria);
		if ($this->expressions) {
			$this->m_expressionsLoaded = true;
		}
	}
	
	function _loadRelations() {
		$handler =& $this->_getRelationsHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('glossary_category_id', $this->get('id')));
		$this->relations =& $handler->getObjects($mCriteria);
		if ($this->relations) {
			$this->m_relationsLoaded = true;
		}
	}

	private function _getExpressionsHandler() {
		require_once(dirname(__FILE__).'/Glossary_CategoryExpressionsHandler.class.php');
		$handler =& new Glossary_CategoryExpressionsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}

	private function _getRelationsHandler() {
		require_once(dirname(__FILE__).'/Glossary_CategoryTermRelationsHandler.class.php');
		$handler =& new Glossary_CategoryTermRelationsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}
}

class Glossary_CategoriesHandler extends XoopsObjectGenericHandler {

	var $mTable = "glossary_categories";
	var $mPrimary = "id";
	var $mClass = "Glossary_CategoriesObject";

	function &get($id) {
		$object =& parent::get($id);
		if ($object) {
			$object->_loadExpressions();
		}
		return $object;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			// load to outer informations.
			foreach ($objects as &$object) {
				$object->_loadExpressions();
			}
		}
		return $objects;
	}
}
?>