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

class Toolbox_Glossary_TermManager extends Toolbox_Glossary_AbstractManager {
	
	public function getTerms($resourceId, $offset = null, $limit = null) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('resource_id', $resourceId));
		return $this->m_termsHandler->getObjects($criteriaCompo);
	}

	public function getTerm($termId) {
		return $this->m_termsHandler->get($termId);
	}
	
	public function getTermsByCategoryId($categoryId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_category_id', $categoryId));
		$objects = $this->m_categoryTermRelationsHandler->getObjects($criteriaCompo);
		$terms = array();
		foreach ($objects as $object) {
			$terms[] = $this->getTerm($object->get('glossary_term_id'));
		}
		return $terms;
	}

	public function searchTerm($resourceId, $word, $language, $matchingMethod
			, $categoryIds, $scope) {

		$sql = $this->getSearchSql($resourceId, $word, $language, $matchingMethod
			, $categoryIds, $scope);

		$result = $this->db->queryF($sql);
		
		$terms = array();
		while ($row = $this->db->fetchArray($result)) {
			$terms[] = $this->m_termsHandler->get($row['id']);
		}

		return $terms;
	}
	
	private function getSearchSql($resourceId, $word, $language, $matchingMethod
			, $categoryIds, $scope) {
				
		$termsTable = $this->m_termsHandler->mTable;
		$termExpressionsTable = $this->m_termExpressionsHandler->mTable;
		$definitionsTable = $this->m_definitionsHandler->mTable;
		$definitionExpressionsTable = $this->m_definitionExpressionsHandler->mTable;
		$categoryTermRelationsTable = $this->m_categoryTermRelationsHandler->mTable;

		$wordWhere = $this->getSearchWordSql('expression', $matchingMethod, $word);
		$language = mysql_real_escape_string($language);

		$sql  = ' SELECT	`id` ';
		$sql .= ' FROM		`'.$termsTable.'` ';
		
		$sql .= ' WHERE ';
		if ($scope == 'all') {
			$sql .= ' ( ';
		}
		if ($scope == 'term' || $scope == 'all') {
			$sql .= ' (`id` IN ( ';
			$sql .= '	SELECT `glossary_term_id` ';
			$sql .= '	FROM '.$termExpressionsTable.' ';
			$sql .= '	WHERE `language_code` = \''.$language.'\' AND '.$wordWhere.')) ';
		}
		if ($scope == 'all') {
			$sql .= ' OR ';
		}
		if ($scope == 'definition' || $scope == 'all') {
			$sql .= ' (`id` IN ';
			$sql .= ' 	(SELECT `glossary_term_id` ';
			$sql .= '	 FROM	`'.$definitionsTable.'` ';
			$sql .= '	 WHERE `id` IN ( ';
			$sql .= '		SELECT `glossary_definition_id` ';
			$sql .= '		FROM '.$definitionExpressionsTable.' ';
			$sql .= '		WHERE `language_code` = \''.$language.'\' AND '.$wordWhere.'))) ';
		}
		if ($scope == 'all') {
			$sql .= ' ) ';
		}
		$sql .= ' AND `resource_id` = '.$resourceId;
		if ($categoryIds != null) {
			$sql .= ' AND (`id` IN ( ';
			$sql .= '	SELECT `glossary_term_id` ';
			$sql .= '	FROM	`'.$categoryTermRelationsTable.'` ';
			$sql .= '	WHERE	`glossary_category_id` IN ('.implode(', ', $categoryIds).') ';
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
	 * @param ToolboxVO_Resource_Expression[] $termExpressions
	 * @return object
	 */
	public function createTerm($resourceId, $termExpressions) {
		$term =& $this->m_termsHandler->create(true);
		$time = time();
		$term->set('resource_id', $resourceId);
		$term->set('creation_time', $time);
		$term->set('update_time', $time);

		if (!$this->m_termsHandler->insert($term, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		$this->createTermExpressions($term->get('id'), $termExpressions);

		return $term;
	}
	
	public function updateTerm($termId, $termExpressions) {
		$term =& $this->m_termsHandler->get($termId);
		$term->set('update_time', time());

		if (!$this->m_termsHandler->insert($term, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		$this->updateTermExpressions($termId, $termExpressions);
		
		return $term;
	}

	/**
	 * @param $termId
	 * @return unknown_type
	 */
	public function deleteTerm($termId) {
		$obj = $this->m_termsHandler->get($termId);
		if (!$this->m_termsHandler->delete($obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$this->deleteTermExpressions($termId);
	}

	public function createTermExpressions($termId, $termExpressions) {
		foreach ($termExpressions as $termExpression) {
			$exp =& $this->m_termExpressionsHandler->create(true);
			$exp->set('glossary_term_id', $termId);
			$exp->set('language_code', $termExpression->language);
			$exp->set('expression', $termExpression->expression);

			if (!$this->m_termExpressionsHandler->insert($exp, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}

	public function updateTermExpressions($termId, $termExpressions) {
		$this->deleteTermExpressions($termId);
		$this->createTermExpressions($termId, $termExpressions);
	}
	
	public function deleteTermExpressions($termId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_term_id', $termId));
		if (!$this->m_termExpressionsHandler->deleteAll($criteriaCompo, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
	}

	/**
	 * 
	 * @param int $termId
	 * @param int[] $categoryIds
	 */
	public function setCategory($termId, $categoryIds) {
		if (!$this->m_categoryTermRelationsHandler->deleteByTermId($termId)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		if (!$categoryIds || !is_array($categoryIds)) {
			return;
		}

		foreach ($categoryIds as $categoryId) {
			$relations = $this->m_categoryTermRelationsHandler->create(true);
			$relations->set('glossary_term_id', $termId);
			$relations->set('glossary_category_id', $categoryId);

			if (!$this->m_categoryTermRelationsHandler->insert($relations, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}
	
	public function deleteLanguage($id, $language) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_term_id', $id));
		$criteriaCompo->add(new Criteria('language_code', $language));
		$objects = $this->m_termExpressionsHandler->getObjects($criteriaCompo);
		foreach ($objects as $object) {
			$object->set('expression', '');
			if (!$this->m_termExpressionsHandler->insert($object, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}
}
?>