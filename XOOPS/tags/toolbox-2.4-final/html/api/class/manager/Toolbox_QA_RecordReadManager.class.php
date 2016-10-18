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

class Toolbox_QA_RecordReadManager extends Toolbox_QA_AbstractManager {
	protected $m_answerManager;
	protected $m_questionManager;

	public function __construct() {
		parent::__construct();
		$this->m_questionManager = new Toolbox_QA_QuestionManager();
		$this->m_answerManager = new Toolbox_QA_AnswerManager();
	}

	public function getAllRecords($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$resourceId = $this->getResourceIdByName($name);
		$questions = $this->m_questionManager->getQuestions($resourceId);
		$offset = ($offset) ? $offset : 0;
		$limit = ($limit) ? $limit : 999999;

		$records = array();
		foreach ($questions as $question) {
			$records[] = $this->questionObject2responseVO($question);
		}
		
		$this->sortRecord($records, $orderBy, $sortOrder);

		return array_slice($records, $offset, $limit);
	}

	public function getRecord($id) {
		$question = $this->m_questionManager->getQuestion($id);
		return $this->questionObject2responseVO($question);
	}
	
	public function getRecordsByCategoryId($categoryId) {
		$questions = $this->m_questionManager->getQuestionsByCategoryId($categoryId);
		$return = array();
		foreach ($questions as $question) {
			$return[] = $this->questionObject2responseVO($question);
		}
		return $return;
	}
	
	public function searchRecord($name, $word, $language, $matchingMethod
			, $categoryIds = null, $scope = null, $sortOrder = null
			, $orderBy = null, $offset = null, $limit = null) {

		$resourceId = $this->getResourceIdByName($name);

		$scope = ($scope == null) ? 'qa' : $scope;
		$offset = ($offset == null) ? 0 : $offset;
		$limit = ($limit == null) ? 999999 : $limit;

		$questions = $this->m_questionManager->searchQuestion($resourceId, $word, $language
			, $matchingMethod, $categoryIds, $scope);

		$results = array();
		foreach ($questions as $question) {
			$results[] = $this->questionObject2responseVO($question);
		}
		
		$this->sortRecord($results, $orderBy, $sortOrder);
		
		return array_slice($results, $offset, $limit);
	}
	
	private function sortRecord(&$records, $orderBy = null, $sortOrder = null) {
		
		$sortOrder = ($sortOrder == null) ? 'asec' : $sortOrder;
		$orderBy = ($orderBy == null) ? 'creationDate' : $orderBy;
		
		$sortManager = new Toolbox_QA_RecordSortManager();
		switch ($orderBy) {
		case 'updateDate':
			$sortManager->sortByUpdateDate($records, $sortOrder);
			break;
		default:
			require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
			if (in_array($orderBy, $LANGRID_LANGUAGE_ARRAY)) {
				$sortManager->sortByLanguageCode($records, $orderBy, $sortOrder);
			} else {
				$sortManager->sortByCreationDate($records, $sortOrder);
			}
			break;
		}
	}
	
	public function countRecords($name, $word, $language, $matchingMethod
			, $categoryIds = null, $scope = null, $sortOrder = null
			, $orderBy = null) {
		return count($this->searchRecord($name, $word, $language
			, $matchingMethod, $categoryIds, $scope));
	}
	
	public function getCount($name) {
		$resourceId = $this->getResourceIdByName($name);
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('resource_id', $resourceId));
		return $this->m_questionsHandler->getCount($criteriaCompo);
	}
}

class Toolbox_QA_RecordSortManager {
	
	public function __construct() {
		
	}
	
	public function sortByCreationDate(&$results, $sortOrder) {
		usort($results, array($this, 'sortByCreationDate'.ucfirst($sortOrder)));
	}

	public function sortByCreationDateAsec($a, $b) {
		if ($a->creationDate == $b->creationDate) {
			return 0;
		}
		return ($a->creationDate > $b->creationDate) ? 1 : -1;
	}
	
	public function sortByCreationDateDesc($a, $b) {
		return $this->sortByCreationDateAsec($b, $a);
	}
	
	public function sortByUpdateDate(&$results, $sortOrder) {
		usort($results, array($this, 'sortByUpdateDate'.ucfirst($sortOrder)));
	}
	
	public function sortByUpdateDateAsec($a, $b) {
		if ($a->updateDate == $b->updateDate) {
			return 0;
		}
		return ($a->updateDate > $b->updateDate) ? 1 : -1;
	}
	
	public function sortByUpdateDateDesc($a, $b) {
		return $this->sortByUpdateDateAsec($b, $a);
	}
	
	public function sortByLanguageCode(&$results, $orderBy, $sortOrder) {
		$this->orderBy = $orderBy;
		usort($results, array($this, 'sortByQuestion'.ucfirst($sortOrder)));
		foreach ($results as $r) {
			usort($r->answers, array($this, 'sortByAnswer'.ucfirst($sortOrder)));
		}
	}
	
	public function sortByQuestionAsec($a, $b) {
		return $this->sortByExpressionAsec($a->question, $b->question);
	}
	
	public function sortByQuestionDesc($a, $b) {
		return $this->sortByExpressionAsec($b->question, $a->question);
	}
	
	public function sortByAnswerAsec($a, $b) {
		return $this->sortByExpressionAsec($a->expression, $b->expression);
	}
	
	public function sortByAnswerDesc($a, $b) {
		return $this->sortByExpressionAsec($b->expression, $a->expression);
	}
	
	public function sortByExpressionAsec($a, $b) {
		$languageCode = $this->orderBy;
		$aFlag = false; 
		$bFlag = false;
		
		foreach ($a as $vo) {
			if ($vo->language == $languageCode) {
				$aFlag = true;
				$aLang = $vo->expression;
				break;
			}
		}
		
		foreach ($b as $vo) {
			if ($vo->language == $languageCode) {
				$bFlag = true;
				$bLang = $vo->expression;
				break;
			}
		}
		
		if (!$aFlag && !$bFlag) {
			return 0;
		} else if (!$aFlag) {
			return 1;
		} else if (!$bFlag) {
			return -1;
		}
		
		if ($aLang == $bLang) {
			return 0;
		}
		return ($aLang < $bLang) ? -1 : 1;
	}
}
?>