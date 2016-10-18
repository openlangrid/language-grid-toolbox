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

class Toolbox_QA_AnswerManager extends Toolbox_QA_AbstractManager {

	/**
	 * 
	 * @param int $questionId
	 * @param ToolboxVO_QA_Answer[][] $answers
	 * @return ToolboxVO_QA_Answer[][]
	 */
	public function createAnswers($questionId, $answers) {
		foreach ($answers as $a) {
			$answer =& $this->m_answersHandler->create(true);
			$answer->set('qa_question_id', $questionId);
			$answer->set('id', isset($a->id) ? $a->id : null);
			$answer->set('creation_date', ($a->creationDate) ? $a->creationDate : time());

			if (!$this->m_answersHandler->insert($answer, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}

			$this->createAnswerExpressions($answer->get('id'), $a->expression);
		}
		
		return $answers;
	}
	
	/**
	 * Delete & Insert
	 * @param $questionId
	 * @param $answers
	 * @return unknown_type
	 */
	public function updateAnswers($questionId, $answers) {
		$this->deleteAnswers($questionId);
		$this->createAnswers($questionId, $answers);
	}
	
	/**
	 * 答え取得
	 * @param $questionId
	 * @return unknown_type
	 */
	public function getAnswers($questionId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('qa_question_id', $questionId));
		return $this->m_answersHandler->getObjects($criteriaCompo);
	}

	/**
	 * 引数の質問IDの答えを全部消す
	 * @param $questionId
	 * @return unknown_type
	 */
	public function deleteAnswers($questionId) {
		foreach ($this->getAnswers($questionId) as $answer) {
			$this->deleteAnswerExpressions($answer->get('id'));
			$this->m_answersHandler->delete($answer);
		}
	}

	/**
	 * 引数の答えIDを全部消す
	 * @param $answerId
	 * @return unknown_type
	 */
	public function deleteAnswerExpressions($answerId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('qa_answer_id', $answerId));
		if (!$this->m_answerExpressionsHandler->deleteAll($criteriaCompo, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
	}

	public function createAnswerExpressions($answerId, $answerExpressions) {
		foreach ($answerExpressions as $answerExpression) {
			$exp =& $this->m_answerExpressionsHandler->create(true);
			$exp->set('qa_answer_id', $answerId);
			$exp->set('language_code', $answerExpression->language);
			$exp->set('expression', $answerExpression->expression);

			if (!$this->m_answerExpressionsHandler->insert($exp, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}

	public function deleteLanguage($id, $language) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('qa_answer_id', $id));
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