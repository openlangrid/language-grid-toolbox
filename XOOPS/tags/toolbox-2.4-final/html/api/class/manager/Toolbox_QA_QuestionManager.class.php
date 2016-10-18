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
require_once dirname(__FILE__).'/Toolbox_QA_AbstractManager.class.php';

class Toolbox_QA_QuestionManager extends Toolbox_QA_AbstractManager {
	
	public function getQuestions($resourceId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('resource_id', $resourceId));
		
		return $this->m_questionsHandler->getObjects($criteriaCompo);
	}

	public function getQuestion($questionId) {
		return $this->m_questionsHandler->get($questionId);
	}
	
	public function getQuestionsByCategoryId($categoryId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('qa_category_id', $categoryId));
		$objects = $this->m_categoryQuestionRelationsHandler->getObjects($criteriaCompo);
		
		$questions = array();
		foreach ($objects as $object) {
			$questions[] = $this->getQuestion($object->get('qa_question_id'));
		}
		
		return $questions;
	}

	public function searchQuestion($resourceId, $word, $language, $matchingMethod
			, $categoryIds, $scope) {

		$sql = $this->getSearchSql($resourceId, $word, $language, $matchingMethod
			, $categoryIds, $scope);

		$result = $this->db->queryF($sql);
		
		$questions = array();
		while ($row = $this->db->fetchArray($result)) {
			$questions[] = $this->m_questionsHandler->get($row['id']);
		}

		return $questions;
	}
	
	private function getSearchSql($resourceId, $word, $language, $matchingMethod
			, $categoryIds, $scope) {
				
		$questionsTable = $this->m_questionsHandler->mTable;
		$questionExpressionsTable = $this->m_questionExpressionsHandler->mTable;
		$answersTable = $this->m_answersHandler->mTable;
		$answerExpressionsTable = $this->m_answerExpressionsHandler->mTable;
		$categoryQuestionRelationsTable = $this->m_categoryQuestionRelationsHandler->mTable;

		$wordWhere = $this->getSearchWordSql('expression', $matchingMethod, $word);
		$language = mysql_real_escape_string($language);

		$sql  = ' SELECT	`id` ';
		$sql .= ' FROM		`'.$questionsTable.'` ';
		
		$sql .= ' WHERE ';
		if ($scope == 'qa') {
			$sql .= ' ( ';
		}
		if ($scope == 'question' || $scope == 'qa') {
			$sql .= ' (`id` IN ( ';
			$sql .= '	SELECT `qa_question_id` ';
			$sql .= '	FROM '.$questionExpressionsTable.' ';
			$sql .= '	WHERE `language_code` = \''.$language.'\' AND '.$wordWhere.')) ';
		}
		if ($scope == 'qa') {
			$sql .= ' OR ';
		}
		if ($scope == 'answer' || $scope == 'qa') {
			$sql .= ' (`id` IN ';
			$sql .= ' 	(SELECT `qa_question_id` ';
			$sql .= '	 FROM	`'.$answersTable.'` ';
			$sql .= '	 WHERE `id` IN ( ';
			$sql .= '		SELECT `qa_answer_id` ';
			$sql .= '		FROM '.$answerExpressionsTable.' ';
			$sql .= '		WHERE `language_code` = \''.$language.'\' AND '.$wordWhere.'))) ';
		}
		if ($scope == 'qa') {
			$sql .= ' ) ';
		}
		$sql .= ' AND `resource_id` = '.$resourceId;
		if ($categoryIds != null) {
			$sql .= ' AND (`id` IN ( ';
			$sql .= '	SELECT `qa_question_id` ';
			$sql .= '	FROM	`'.$categoryQuestionRelationsTable.'` ';
			$sql .= '	WHERE	`qa_category_id` IN ('.implode(', ', $categoryIds).') ';
			$sql .= ' )) ';
		}
		
		return $sql;
	}
	
	private function getSearchWordSql($column, $matchingMethod, $word) {
		switch (strtoupper($matchingMethod)) {
		case 'COMPLETE':
			$return = ' `%s` = \'%s\' ';
			break;
		case 'PREFIX':
			$return = ' `%s` LIKE \'%s%%\' ';
			break;
		case 'PARTIAL':
			$return = ' `%s` LIKE \'%%%s%%\' ';
			break;
		case 'SUFFIX':
			$return = ' `%s` = \'%%%s\' ';
			break;
		case 'REGEX':
			$return = ' `%s` REGEXP \'%s\' ';
			break;
		}
		
		return sprintf($return, $column, mysql_real_escape_string($word));
	}

	/**
	 * 
	 * @param int $resourceId
	 * @param ToolboxVO_Resource_Expression[] $questionExpressions
	 * @return object
	 */
	public function createQuestion($resourceId, $questionExpressions) {
		$question =& $this->m_questionsHandler->create(true);
		$time = time();
		$question->set('resource_id', $resourceId);
		$question->set('creation_time', $time);
		$question->set('update_time', $time);

		if (!$this->m_questionsHandler->insert($question, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		$this->createQuestionExpressions($question->get('id'), $questionExpressions);

		return $question;
	}
	
	public function updateQuestion($questionId, $questionExpressions) {
		$question =& $this->m_questionsHandler->get($questionId);
		$question->set('update_time', time());

		if (!$this->m_questionsHandler->insert($question, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		$this->updateQuestionExpressions($questionId, $questionExpressions);
		
		return $question;
	}

	/**
	 * @param $questionId
	 * @return unknown_type
	 */
	public function deleteQuestion($questionId) {
		$obj = $this->m_questionsHandler->get($questionId);
		if (!$this->m_questionsHandler->delete($obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$this->deleteQuestionExpressions($questionId);
	}

	public function createQuestionExpressions($questionId, $questionExpressions) {
		foreach ($questionExpressions as $questionExpression) {
			$exp =& $this->m_questionExpressionsHandler->create(true);
			$exp->set('qa_question_id', $questionId);
			$exp->set('language_code', $questionExpression->language);
			$exp->set('expression', $questionExpression->expression);

			if (!$this->m_questionExpressionsHandler->insert($exp, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}

	public function updateQuestionExpressions($questionId, $questionExpressions) {
		$this->deleteQuestionExpressions($questionId);
		$this->createQuestionExpressions($questionId, $questionExpressions);
	}
	
	public function deleteQuestionExpressions($questionId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('qa_question_id', $questionId));
		if (!$this->m_questionExpressionsHandler->deleteAll($criteriaCompo, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
	}

	/**
	 * 
	 * @param int $questionId
	 * @param int[] $categoryIds
	 */
	public function setCategory($questionId, $categoryIds) {
		if (!$this->m_categoryQuestionRelationsHandler->deleteByQuestionId($questionId)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		if (!$categoryIds || !is_array($categoryIds)) {
			return;
		}

		foreach ($categoryIds as $categoryId) {
			$relations = $this->m_categoryQuestionRelationsHandler->create(true);
			$relations->set('qa_question_id', $questionId);
			$relations->set('qa_category_id', $categoryId);

			if (!$this->m_categoryQuestionRelationsHandler->insert($relations, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}
	
	public function deleteLanguage($id, $language) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('qa_question_id', $id));
		$criteriaCompo->add(new Criteria('language_code', $language));
		$objects = $this->m_questionExpressionsHandler->getObjects($criteriaCompo);
		foreach ($objects as $object) {
			$object->set('expression', '');
			if (!$this->m_questionExpressionsHandler->insert($object, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}
}
?>