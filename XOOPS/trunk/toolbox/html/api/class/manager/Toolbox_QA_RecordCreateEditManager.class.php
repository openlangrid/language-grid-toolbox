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
require_once dirname(__FILE__).'/Toolbox_QA_AnswerManager.class.php';
require_once dirname(__FILE__).'/Toolbox_QA_QuestionManager.class.php';

class Toolbox_QA_RecordCreateEditManager extends Toolbox_QA_AbstractManager {

	protected $m_answerManager;
	protected $m_questionManager;

	public function __construct() {
		parent::__construct();
		$this->m_questionManager = new Toolbox_QA_QuestionManager();
		$this->m_answerManager = new Toolbox_QA_AnswerManager();
	}

	/**
	 *
	 * @param String $name
	 * @param ToolboxVO_Resource_Expression[] $question
	 * @param ToolboxVO_QA_Answer[][] $answers
	 * @param int[] $categoryIds
	 * @return ToolboxVO_QA_QARecord
	 */
	public function addRecord($name, $questionExpressions, $answers, $categoryIds = null) {
		$resourceId = $this->getResourceIdByName($name);

		$question = $this->m_questionManager->createQuestion($resourceId, $questionExpressions);
		$this->m_questionManager->setCategory($question->get('id'), $categoryIds);
		$answers = $this->m_answerManager->createAnswers($question->get('id'), $answers);

		return $this->questionObject2responseVo($question);
	}

	/**
	 * @param int $questionId
	 * @param ToolboxVO_Resource_Expression[] $question
	 * @param ToolboxVO_QA_Answer[][] $answers
	 * @param int[] $categoryIds
	 * @return void
	 */
	public function updateRecord($questionId, $questionExpressions, $answers, $categoryIds = null) {
		$question = $this->m_questionManager->updateQuestion($questionId, $questionExpressions);
		$this->m_questionManager->setCategory($questionId, $categoryIds);
		$answers = $this->m_answerManager->updateAnswers($questionId, $answers);

		$question->_loadAnswers();
		return $this->questionObject2responseVo($question);
	}

	/**
	 * @param int $questionId
	 * @return void
	 */
	public function deleteRecord($questionId) {
		$this->m_questionManager->deleteQuestion($questionId);
		$this->m_answerManager->deleteAnswers($questionId);
		$this->m_categoryQuestionRelationsHandler->deleteByQuestionId($questionId);
	}

	/**
	 *
	 * @param String $name
	 * @return void
	 */
	public function deleteAllRecords($name) {
		$resourceId = $this->getResourceIdByName($name);
		$questions = $this->m_questionManager->getQuestions($resourceId);
		foreach ($questions as $question) {
			$this->deleteRecord($question->get('id'));
		}
	}

	/**
	 *
	 * @param int $id
	 * @return void
	 */
	public function deleteLanguage($name, $language) {
		$resourceId = $this->getResourceIdByName($name);
		$questions = $this->m_questionManager->getQuestions($resourceId);

		foreach ($questions as $question) {
			$this->m_questionManager->deleteLanguage($question->get('id'), $language);
			foreach ($question->answers as $answer) {
				$this->m_answerManager->deleteLanguage($answer->get('id'), $language);
			}
		}
	}
}
?>