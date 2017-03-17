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
require_once dirname(__FILE__).'/Toolbox_Glossary_AbstractManager.class.php';
require_once dirname(__FILE__).'/Toolbox_Glossary_DefinitionManager.class.php';
require_once dirname(__FILE__).'/Toolbox_Glossary_TermManager.class.php';

class Toolbox_Glossary_RecordReadManager extends Toolbox_Glossary_AbstractManager {
	protected $m_definitionManager;
	protected $m_termManager;

	public function __construct() {
		parent::__construct();
		$this->m_termManager = new Toolbox_Glossary_TermManager();
		$this->m_definitionManager = new Toolbox_Glossary_DefinitionManager();
	}

	public function getAllRecords($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$resourceId = $this->getResourceIdByName($name);
		$terms = $this->m_termManager->getTerms($resourceId);
		$offset = ($offset) ? $offset : 0;
		$limit = ($limit) ? $limit : 999999;

		$records = array();
		foreach ($terms as $term) {
			$records[] = $this->termObject2responseVO($term);
		}
		
		$this->sortRecord($records, $orderBy, $sortOrder);

		return array_slice($records, $offset, $limit);
	}

	public function getRecord($id) {
		$term = $this->m_termManager->getTerm($id);
		return $this->termObject2responseVO($term);
	}
	
	public function getRecordsByCategoryId($categoryId) {
		$term = $this->m_termManager->getTermsByCategoryId($categoryId);
		$return = array();
		foreach ($terms as $term) {
			$return[] = $this->termObject2responseVO($term);
		}
		return $return;
	}
	
	public function searchRecord($name, $word, $language, $matchingMethod
			, $categoryIds = null, $scope = null, $sortOrder = null
			, $orderBy = null, $offset = null, $limit = null) {

		$resourceId = $this->getResourceIdByName($name);

		$scope = ($scope == null) ? 'all' : $scope;
		$offset = ($offset == null) ? 0 : $offset;
		$limit = ($limit == null) ? 999999 : $limit;

		$terms = $this->m_termManager->searchTerm($resourceId, $word, $language
			, $matchingMethod, $categoryIds, $scope);

		$results = array();
		foreach ($terms as $term) {
			$results[] = $this->termObject2responseVO($term);
		}
		
		$this->sortRecord($results, $orderBy, $sortOrder);
		
		return array_slice($results, $offset, $limit);
	}
	
	private function sortRecord(&$records, $orderBy = null, $sortOrder = null) {
		
		$sortOrder = ($sortOrder == null) ? 'asec' : $sortOrder;
		$orderBy = ($orderBy == null) ? 'creationDate' : $orderBy;
		
		$sortManager = new Toolbox_Glossary_RecordSortManager();
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
		return $this->m_termsHandler->getCount($criteriaCompo);
	}
}

class Toolbox_Glossary_RecordSortManager {
	
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
		usort($results, array($this, 'sortByTerm'.ucfirst($sortOrder)));
		foreach ($results as $r) {
			usort($r->definition, array($this, 'sortByDefinition'.ucfirst($sortOrder)));
		}
	}
	
	public function sortByTermAsec($a, $b) {
		return $this->sortByExpressionAsec($a->term, $b->term);
	}
	
	public function sortByTermDesc($a, $b) {
		return $this->sortByExpressionAsec($b->term, $a->term);
	}
	
	public function sortByDefinitionAsec($a, $b) {
		return $this->sortByExpressionAsec($a->expression, $b->expression);
	}
	
	public function sortByDefinitionDesc($a, $b) {
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