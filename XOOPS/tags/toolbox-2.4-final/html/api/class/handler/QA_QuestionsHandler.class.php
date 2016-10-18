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

class QA_QuestionsObject extends XoopsSimpleObject {

	var $expressions;
	var $m_expressionsLoaded = false;
	var $answers;
	var $m_answersLoaded = false;
	var $relations;
	var $m_relationsLoaded = false;

	function QA_QuestionsObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('resource_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('creation_time', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
	}
	
	function _loadAnswers() {
		$handler =& $this->_getAnswersHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('qa_question_id', $this->get('id')));
		$this->answers =& $handler->getObjects($mCriteria);
		if ($this->answers) {
			$this->m_answersLoaded = true;
		}
	}

	function _loadExpressions() {
		$handler =& $this->_getExpressionsHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('qa_question_id', $this->get('id')));
		$this->expressions =& $handler->getObjects($mCriteria);
		if ($this->expressions) {
			$this->m_expressionsLoaded = true;
		}
	}
	
	function _loadRelations() {
		$handler =& $this->_getRelationsHandler();
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('qa_question_id', $this->get('id')));
		$this->relations =& $handler->getObjects($mCriteria);
		if ($this->relations) {
			$this->m_relationsLoaded = true;
		}
	}

	private function _getAnswersHandler() {
		require_once(dirname(__FILE__).'/QA_AnswersHandler.class.php');
		$handler =& new QA_AnswersHandler($GLOBALS['xoopsDB']);
		return $handler;
	}
	
	private function _getExpressionsHandler() {
		require_once(dirname(__FILE__).'/QA_QuestionExpressionsHandler.class.php');
		$handler =& new QA_QuestionExpressionsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}

	private function _getRelationsHandler() {
		require_once(dirname(__FILE__).'/QA_CategoryQuestionRelationsHandler.class.php');
		$handler =& new QA_CategoryQuestionRelationsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}
}

class QA_QuestionsHandler extends XoopsObjectGenericHandler {

	var $mTable = "qa_questions";
	var $mPrimary = "id";
	var $mClass = "QA_QuestionsObject";

	function &get($id) {
		$object =& parent::get($id);
		if ($object) {
			$object->_loadExpressions();
			$object->_loadAnswers();
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
				$object->_loadAnswers();
				$object->_loadRelations();
			}
		}
		return $objects;
	}
}
?>