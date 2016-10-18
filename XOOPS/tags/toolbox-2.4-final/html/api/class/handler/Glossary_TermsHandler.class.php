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

class Glossary_TermsObject extends XoopsSimpleObject {

	var $expressions;
	var $m_expressionsLoaded = false;
	var $definitions;
	var $m_definitionsLoaded = false;
	var $relations;
	var $m_relationsLoaded = false;

	function Glossary_TermsObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('resource_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('creation_time', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
	}
	
	function _loadDefinitions() {
		$handler =& $this->_getDefinitionsHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('glossary_term_id', $this->get('id')));
		$this->definitions =& $handler->getObjects($mCriteria);
		if ($this->definitions) {
			$this->m_definitionsLoaded = true;
		}
	}

	function _loadExpressions() {
		$handler =& $this->_getExpressionsHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('glossary_term_id', $this->get('id')));
		$this->expressions =& $handler->getObjects($mCriteria);
		if ($this->expressions) {
			$this->m_expressionsLoaded = true;
		}
	}
	
	function _loadRelations() {
		$handler =& $this->_getRelationsHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('glossary_term_id', $this->get('id')));
		$this->relations =& $handler->getObjects($mCriteria);
		if ($this->relations) {
			$this->m_relationsLoaded = true;
		}
	}

	private function _getDefinitionsHandler() {
		require_once(dirname(__FILE__).'/Glossary_DefinitionsHandler.class.php');
		$handler =& new Glossary_DefinitionsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}
	
	private function _getExpressionsHandler() {
		require_once(dirname(__FILE__).'/Glossary_TermExpressionsHandler.class.php');
		$handler =& new Glossary_TermExpressionsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}

	private function _getRelationsHandler() {
		require_once(dirname(__FILE__).'/Glossary_CategoryTermRelationsHandler.class.php');
		$handler =& new Glossary_CategoryTermRelationsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}
}

class Glossary_TermsHandler extends XoopsObjectGenericHandler {

	var $mTable = "glossary_terms";
	var $mPrimary = "id";
	var $mClass = "Glossary_TermsObject";

	function &get($id) {
		$object =& parent::get($id);
		if ($object) {
			$object->_loadExpressions();
			$object->_loadDefinitions();
			$object->_loadRelations();
		}
		return $object;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			// load to outer informations.
			foreach ($objects as &$object) {
				$object->_loadExpressions();
				$object->_loadDefinitions();
				$object->_loadRelations();
			}
		}
		return $objects;
	}
}
?>