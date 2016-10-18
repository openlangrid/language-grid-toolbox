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

require_once dirname(__FILE__).'/Toolbox_TranslationTemplate_AbstractManager.class.php';

class Toolbox_TranslationTemplate_RecordSearchManager extends Toolbox_TranslationTemplate_RecordReadManager {

	/**
	 *
	 * Facade
	 *
	 * @param String $name
	 * @param String $word
	 * @param String $language
	 * @param String $matchingMethod
	 * @param int[] $categoryIds
	 * @param String $sortOrder
	 * @param String $orderBy
	 * @param int $offset
	 * @param int $limit
	 * @return unknown_type
	 */
	public function searchRecord($name, $word, $language, $matchingMethod, $categoryIds, $sortOrder, $orderBy, $offset, $limit) {
		$id = $this->getResourceIdByName($name);

		$objs = $this->searchObjects($id, $word, $language, $matchingMethod, $categoryIds);

		$return = array();
		foreach ($objs as $obj) {
			$return[] = $this->translationTemplateObject2responseVO($obj);
		}

		$this->sort($return, $sortOrder, $orderBy, 'creationDate');

		return $this->slice($return, $offset, $limit);
	}

	/**
	 *
	 * @param String $name
	 * @param String $word
	 * @param String $language
	 * @param String $matchingMethod
	 * @param int[] $categoryIds
	 * @return number
	 */
	public function countRecords($name, $word, $language, $matchingMethod, $categoryIds) {
		return count($this->searchRecord($name, $word, $language, $matchingMethod, $categoryIds, null, null, null, null));
	}

	/**
	 *
	 * @param unknown_type $id
	 * @param unknown_type $word
	 * @param unknown_type $language
	 * @param unknown_type $matchingMethod
	 * @param unknown_type $categoryIds
	 * @return unknown_type
	 */
	private function searchObjects($id, $word, $language, $matchingMethod, $categoryIds) {

		$scopeIds = $this->getScopeIds($id, $categoryIds);
		$searchIds = $this->searchIds($scopeIds, $language, $this->getPattern($word, $matchingMethod));

		if (empty($searchIds)) {
			return array();
		}

		$c = new CriteriaCompo();
//		$c->add(new Criteria('id', implode(',', $searchIds), 'IN'));
		foreach ($searchIds as $id) {
			$c->add(new Criteria('id', $id), 'OR');
		}

		$objs = $this->m_translationTemplatesHandler->getObjects($c);

		return $objs;
	}

	/**
	 *
	 * @param int $id
	 * @param int[] $categoryIds
	 * @return unknown_type
	 */
	private function getScopeIds($id, $categoryIds) {
		return ($categoryIds) ? $this->getScopeIdsByCategoryIds($categoryIds)
							  : $this->getScopeIdsByResourceId($id);
	}

	/**
	 *
	 * @param int[] $categoryIds
	 * @return unknown_type
	 */
	private function getScopeIdsByCategoryIds($categoryIds) {
		$c = new CriteriaCompo();
//		$c->add(new Criteria('category_id', implode(',', $categoryIds), 'IN'));
		foreach ($categoryIds as $id) {
			$c->add(new Criteria('category_id', $id), 'OR');
		}


		$objects = $this->m_categoryTranslationTemplateRelationsHandler($c);

		$scopeIds = array();
		foreach ($objects as $o) {
			$scopeIds[] = $o->get('translation_template_id');
		}

		return $scopeIds;
	}

	/**
	 *
	 * @param int $id
	 * @return int[]
	 */
	private function getScopeIdsByResourceId($id) {
		$objects = $this->getRecordsByResourceId($id);

		$scopeIds = array();
		foreach ($objects as $o) {
			$scopeIds[] = $o->get('id');
		}

		return $scopeIds;
	}

	/**
	 *
	 * @param int $scopeIds
	 * @param String $language
	 * @param String $pattern
	 * @return int[]
	 */
	private function searchIds($scopeIds, $language, $pattern) {
		if (empty($scopeIds)) {
			return array();
		}

		$c = new CriteriaCompo();
//		$c->add(new Criteria('translation_template_id', implode(',', $scopeIds), 'IN'));
		$sc = new CriteriaCompo();
		foreach ($scopeIds as $scopeId) {
			$sc->add(new Criteria('translation_template_id', $scopeId), 'OR');
		}
		$c->add($sc);
		$c->add(new Criteria('language_code', $language));
		$c->add(new Criteria('expression', $pattern, 'LIKE'));

		$expObjs = $this->m_translationTemplateExpressionsHandler->getObjects($c);

		$ids = array();
		foreach ($expObjs as $expObj) {
			$ids[] = $expObj->get('translation_template_id');
		}

		return array_values(array_unique($ids));
	}

	/**
	 *
	 * @param String $word
	 * @param String $matchingMethod
	 * @return String
	 */
	private function getPattern($word, $matchingMethod) {
		switch (strtoupper($matchingMethod)) {
		case 'COMPLETE':
			return $word;
		case 'PREFIX':
			return $word.'%';
		case 'SUFFIX':
			return '%'.$word;
		case 'PARTIAL':
		default:
			return '%'.$word.'%';
		}
	}
}
?>