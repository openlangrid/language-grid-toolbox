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

require_once(dirname(__FILE__).'/../Toolbox_AbstractManager.class.php');
require_once(dirname(__FILE__).'/../../exception/Toolbox_SQLException.class.php');
require_once(dirname(__FILE__).'/Toolbox_TranslationTemplate_Sorter.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_BoundWordExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_BoundWordSetExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_BoundWordSetIdsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_BoundWordSetsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_BoundWordsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_CategoriesHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_CategoryExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_CategoryTranslationTemplateRelationsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_DefaultBoundWordSetsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_TranslationTemplateExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../handler/translation_template/TranslationTemplate_TranslationTemplatesHandler.class.php');

/**
 * 
 * @author kitajima
 *
 */
abstract class Toolbox_TranslationTemplateAbstractManager extends Toolbox_AbstractManager {
	
	const DEFAULT_OFFSET = 0;
	const LIMIT_MAX = 999999;

	protected $sorter;
	protected $m_boundWordExpressionsHandler;
	protected $m_boundWordSetExpressionsHandler;
	protected $m_boundWordSetIdsHandler;
	protected $m_boundWordSetTranslationTemplateRelationsHandler;
	protected $m_boundWordSetsHandler;
	protected $m_boundWordsHandler;
	protected $m_categoriesHandler;
	protected $m_categoryExpressionsHandler;
	protected $m_categoryTranslationTemplateRelationsHandler;
	protected $m_defaultBoundWordSetsHandler;
	protected $m_translationTemplateExpressionsHandler;
	protected $m_translationTemplatesHandler;

	public function __construct() {
		parent::__construct();

		$this->sorter = new Toolbox_TranslationTemplateSorter();
		$this->createHandler();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function createHandler() {
		$this->m_boundWordExpressionsHandler = new TranslationTemplate_BoundWordExpressionsHandler($this->db);
		$this->m_boundWordSetExpressionsHandler = new TranslationTemplate_BoundWordSetExpressionsHandler($this->db);
		$this->m_boundWordSetIdsHandler = new TranslationTemplate_BoundWordSetIdsHandler($this->db);
		$this->m_boundWordSetTranslationTemplateRelationsHandler = new TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler($this->db);
		$this->m_boundWordSetsHandler = new TranslationTemplate_BoundWordSetsHandler($this->db);
		$this->m_boundWordsHandler = new TranslationTemplate_BoundWordsHandler($this->db);
		$this->m_categoriesHandler = new TranslationTemplate_CategoriesHandler($this->db);
		$this->m_categoryExpressionsHandler = new TranslationTemplate_CategoryExpressionsHandler($this->db);
		$this->m_categoryTranslationTemplateRelationsHandler = new TranslationTemplate_CategoryTranslationTemplateRelationsHandler($this->db);
		$this->m_defaultBoundWordSetsHandler = new TranslationTemplate_DefaultBoundWordSetsHandler($this->db);
		$this->m_translationTemplateExpressionsHandler = new TranslationTemplate_TranslationTemplateExpressionsHandler($this->db);
		$this->m_translationTemplatesHandler = new TranslationTemplate_TranslationTemplatesHandler($this->db);
	}

	/**
	 * @param unknown_type $object
	 * @return 
	 */
	protected function categoryObject2responseVo($object) {
		if (!$object->m_expressionsLoaded) {
			$object->_loadExpressions();
		}
		
		if (!$object->m_recordCountLoaded) {
			$object->_loadRecordCount();
		}
		
		$category = new ToolboxVO_TranslationTemplate_TranslationTemplateCategory();
		$category->id = $object->get('id');
		$category->name = $this->expressionsObject2ResponseVOs($object->expressions);
		$category->recordCount = $object->recordCount;

		return $category;
	}

	/**
	 * @param unknown_type $object
	 * @return 
	 */
	protected function translationTemplateObject2responseVO($object) {
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
		$record->expressions = $this->expressionsObject2ResponseVOs($object->expressions);
		$record->wordSetIds = array();
		$record->categoryIds = array();
		$record->creationDate = $object->get('creation_time');
		$record->updateDate = $object->get('update_time');
		
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
	 * @param unknown_type $object
	 * @return ToolboxVO_TranslationTemplate_BoundWordSet
	 */
	protected function defaultBoundWordSetObject2responseVO($object) {
		$boundWordSet = new ToolboxVO_TranslationTemplate_BoundWordSet();
		$boundWordSet->id = $object->get('id');
		$boundWordSet->name = array();
		$boundWordSet->type = $object->get('type');
		$boundWordSet->words = array();
		$boundWordSet->recordCount = 0;
		
		return $boundWordSet;
	}

	/**
	 * @param unknown_type $object
	 * @return 
	 */
	protected function boundWordSetObject2responseVO($object) {
		
		if (get_class($object) == 'TranslationTemplate_DefaultBoundWordSetsObject'
				or is_subclass_of($object, 'TranslationTemplate_DefaultBoundWordSetsObject')) {
			return $this->defaultBoundWordSetObject2responseVO($object);
		}
		
		if (!$object->m_expressionsLoaded) {
			$object->_loadExpressions();
		}
		
		if (!$object->m_boundWordsLoaded) {
			$object->_loadBoundWords();
		}

		if (!$object->m_recordCountLoaded) {
			$object->_loadRecordCount();
		}
		
		$boundWordSet = new ToolboxVO_TranslationTemplate_BoundWordSet();
		$boundWordSet->id = $object->get('id');
		$boundWordSet->name = $this->expressionsObject2ResponseVOs($object->expressions);
		$boundWordSet->type = 'enum';
		$boundWordSet->words = array();
		$boundWordSet->recordCount = $object->recordCount;
		
		foreach ($object->boundWords as $boundWord) {
			$boundWordSet->words[] = $this->BoundWordObject2responseVO($boundWord);
		}

		return $boundWordSet;
	}

	/**
	 * @param unknown_type $object
	 * @return 
	 */
	protected function boundWordObject2responseVO($object) {
		if (!$object->m_expressionsLoaded) {
			$object->_loadExpressions();
		}
		
		$boundWord = new ToolboxVO_TranslationTemplate_BoundWord();
		$boundWord->id = $object->get('id');
		$boundWord->expressions = $this->expressionsObject2ResponseVOs($object->expressions);
		
		return $boundWord;
	}

	/**
	 * 
	 * @param unknown_type $objects
	 * @return unknown_type
	 */
	protected function expressionsObject2ResponseVOs($objects) {
		$expressions = array();
		
		if (is_array($objects)) {
		foreach ($objects as $object) {
			$expressions[] = $this->expressionObject2ResponseVO($object);
		}
		}
		
		return $expressions;
	}
	
	/**
	 * 
	 * @param unknown_type $object
	 * @return unknown_type
	 */
	protected function expressionObject2ResponseVO($object) {
		$expr = new ToolboxVO_Resource_Expression();
		$expr->expression = $object->get('expression');
		$expr->language = $object->get('language_code');
		
		return $expr;
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	protected function getResourceIdByName($name) {
		require_once dirname(__FILE__).'/../../handler/CommunityResourceHandler.class.php';
		
		$handler = new CommunityResourceHandler($this->db);
		
		$c = new CriteriaCompo();
		$c->add(new Criteria('dictionary_name', $name));
		$c->add(new Criteria('delete_flag', '0'));
		
		$resource = $handler->getObjects($c);
		
		if (!isset($resource[0])) {
			throw new Exception('Resource "'.$name.'" is  not found.');
		}
		
		return $resource[0]->get('user_dictionary_id');
	}

	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	protected function getResourcesByNames($names) {
		require_once dirname(__FILE__).'/../../handler/CommunityResourceHandler.class.php';
		
		$handler = new CommunityResourceHandler($this->db);
		
		$c = new CriteriaCompo();
		foreach ($names as $name) {
			$c->add(new Criteria('dictionary_name', $name), 'OR');
		}
		$c->add(new Criteria('delete_flag', '0'));
		
		$resource = $handler->getObjects($c);
		
		$resources = array();
		foreach ($resource as $o) {
			$resources[$o->get('user_dictionary_id')] = $o->get('dictionary_name');
		}
		return $resources;
	}
	
	/**
	 * 
	 * @param unknown_type $return
	 * @param unknown_type $sortOrder
	 * @param unknown_type $orderBy
	 * @return unknown_type
	 */
	protected function sort(&$return, $sortOrder, $orderBy, $defaultSortOrder = 'id', $defaultOrderBy = 'asec') {
		$sortOrder = ($sortOrder) ? $sortOrder : $defaultOrderBy;
		$orderBy = ($orderBy) ? $orderBy : $defaultSortOrder;
		
		$this->sorter->sort($return, $sortOrder, $orderBy);
	}
	
	/**
	 * 
	 * @param unknown_type $return
	 * @param unknown_type $offset
	 * @param unknown_type $limit
	 * @return multitype:
	 */
	protected function slice($return, $offset, $limit) {
		$limit = ($limit) ? $limit : self::LIMIT_MAX;
		$offset = ($offset) ? $offset : self::DEFAULT_OFFSET;
		
		return array_slice($return, $offset, $limit);
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
		return $this->m_translationTemplatesHandler->getCount($criteriaCompo);
	}
	
	
	protected function isNgramLanguage($language) {
		return $language == "ja" || $language == "zh-CN" || $language == "zh-TW" || $language == "zh";
	}
}
?>
