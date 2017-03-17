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
require_once dirname(__FILE__).'/ngram_converter.php';

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
	 * @param String $matchingAndor
	 * @param int[] $categoryIds
	 * @param String $sortOrder
	 * @param String $orderBy
	 * @param int $offset
	 * @param int $limit
	 * @return unknown_type
	 */
	public function searchRecordAndor($name, $word, $language, $matchingAndor, $searchType, $categoryIds, $sortOrder, $orderBy, $offset, $limit) {
		//$id = $this->getResourceIdByName($name);
		$resources = $this->getResourcesByNames($name);
		$ids = array();
		foreach( $resources as $key => $value ){
			$ids[] = $key;
		}

		$objs = $this->searchObjectsAndor($ids, $word, $language, $matchingAndor, $searchType, $categoryIds);
		
		$return = array();
		foreach ($objs as $obj) {
			$return[] = $this->translationObject2responseVO($obj, $resources);
		}
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
	 * @param unknown_type $id
	 * @param unknown_type $word
	 * @param unknown_type $language
	 * @param unknown_type $matchingAndor
	 * @param unknown_type $categoryIds
	 * @return unknown_type
	 */
	//private function searchObjectsAndor($id, $word, $language, $matchingAndor, $searchType, $categoryIds) {
	private function searchObjectsAndor($ids, $word, $language, $matchingAndor, $searchType, $categoryIds) {
		$c = new CriteriaCompo();
		if($ids) {
			$sc = new CriteriaCompo();
			foreach ($ids as $id) {
				$sc->add(new Criteria('resource_id', $id), 'OR');
			}
			$c->add($sc);
		}
		
		if($categoryIds) {
			$scopeIds = $this->getScopeIdsByCategoryIds($categoryIds);
			$c->add(new Criteria('translation_template_id', implode(',', $scopeIds), 'IN'));
		}

		$c->add(new Criteria('language_code', $language));
		
		if($matchingAndor == "fulltext") {
			$c->add($this->getCriteriaForFulltextSearch($word, $language, $matchingAndor));
		} else {
			$c->add($this->getCriteriaForAndOrSearch($word, $matchingAndor));
		}
		
		$objs = $this->m_translationTemplatesHandler->getObjectsJoinExpressions($c);
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

		$objects = $this->m_categoryTranslationTemplateRelationsHandler->getObjects($c);

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

	/*
	 * MySQL FULLTEXTインデックスを利用した全文検索
	 */
	private function getCriteriaForFulltextSearch($word, $language, $matchingAndor) {
		$word = mb_convert_kana( $word, "s", "UTF-8" );
		$words = explode(' ', str_replace('　', ' ', $word));
		
		$p = new NgramConverter();
		
		$ngrams = array();
		foreach($words as $w) {
			if($this -> isNgramLanguage($language)) {
				$tmp = explode(' ', $p->to_fulltext( $w, 2 ));
				if(count($tmp) != 1) {
					$tmp = array_slice($tmp, 0, count($tmp) - 1);
				} 				
			} else {
				$tmp = array($w);
			}
			
			//if($matchingAndor == "AND") {
			//	for ($i = 0; $i < count($tmp); $i++) {
			//			$tmp[$i] = '+'.$tmp[$i];
			//	}	
			//}
			$ngrams = array_merge($ngrams, $tmp);
		}
		
		$values = implode(' ', $ngrams);
		
		//$condition = ($matchingAndor == "AND") ? 
		//	"against('{$values}' in boolean mode)" :
		//	"against('{$values}')";

        $cri = new Criteria("ngram", "against('{$values}')", "", "", "match(%s)");
        debugLog(print_r($cri, true));
        debugLog($cri->render());

		return $cri;
	}
	
	private function getCriteriaForAndOrSearch($word, $matchingAndor) {
		
		$exc = new CriteriaCompo();
		
		$word = mb_convert_kana( $word, "s", "UTF-8" );
		
		// if you set magic_quotes_gpc = ON in php.ini
	    //preg_match_all ('/\\\\\"{1}[^\\\\\"]*\\\\\"{1}|[\S^\\\\\"]*/', $word, $matches, PREG_PATTERN_ORDER );  
   
		// if you set magic_quotes_gpc = Off in php.ini
		preg_match_all ('/\"{1}[^\"]*\"{1}|[\S^\"]*/', $word, $matches, PREG_PATTERN_ORDER );
		
	    for ($i = 0; $i < count($matches[0]); $i++) {
	    	
	    	if ($this->checkSearchWord($matches[0][$i]) == false) {
				continue;
			}
			
			// if you set magic_quotes_gpc = ON in php.ini
			//if (strpos($matches[0][$i], '\"') !== false) {
			
			// if you set magic_quotes_gpc = Off in php.ini
			if (strpos($matches[0][$i], '"') !== false) {
	    		// phrase searching
	    		
				// if you set magic_quotes_gpc = ON in php.ini
	    	   	//$matchText = str_replace('\"', '', $matches[0][$i]);
	    	   	
				// if you set magic_quotes_gpc = Off in php.ini
				$matchText = str_replace('"', '', $matches[0][$i]);
	    		
				$matchText = '%' . $matchText . '%';
        	} else {
        		
         		// keyword searching
        		$matchText = '%' . str_replace(' ', '%', $matches[0][$i] . '%');
         	}
        	
	     	switch (strtoupper($matchingAndor)) {
				case 'AND':
					$exc->add(new Criteria('expression', $matchText, 'LIKE'), 'AND');
					break;
				case 'OR':
					$exc->add(new Criteria('expression', $matchText, 'LIKE'), 'OR');
					break;
				default:
					$exc->add(new Criteria('expression', $matchText, 'LIKE'), 'AND');
			}
	    }		
		return $exc;
	}

	/**
	 * @param unknown_type $object
	 * @return 
	 */
	protected function translationObject2responseVO($object, $resources) {
		if (!$object->m_expressionsLoaded) {
			$object->_loadExpressions();
		}
	
		if (!$object->m_boundWordSetRelationsLoaded) {
			$object->_loadBoundWordSetRelations();
		}

		if (!$object->m_categoryRelationsLoaded) {
			$object->_loadCategoryRelations();
		}

		$record = new ToolboxVO_TranslationTemplate_TranslationTemplateRecord();
		$record->id = $object->get('id');
		$record->resourceId = $object->get('resource_id');
		$record->resourceName = $resources[$object->get('resource_id')];
		$record->expressions = $this->expressionsObject2ResponseVOs($object->expressions);
		$record->wordSetIds = array();
		$record->categoryIds = array();
		$record->creationDate = $object->get('creation_time');
		$record->updateDate = $object->get('update_time');
		$record->matchValue = @$object->get('match_value');
		
		foreach ($object->boundWordSetRelations as $relation) {
			$record->wordSetIds[] = $relation->get('bound_word_set_id');
		}
		
		foreach ($object->categoryRelations as $relation) {
			$record->categoryIds[] = $relation->get('category_id');
		}

		return $record;
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
	
	/**
	 * Check search word.
	 * @param String $word
	 */
	private function checkSearchWord($word) {
		
		if (strlen(trim($word)) == 0) {
			return false;
		} else if (strcmp('""', $word) == 0) {
			return false;
		} else if (strcmp('"', $word) == 0) {
			return false;
		} else {
			return true;
		}
	}
}
?>
