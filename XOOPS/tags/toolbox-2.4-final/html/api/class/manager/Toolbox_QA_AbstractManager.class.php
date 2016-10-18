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
require_once(dirname(__FILE__).'/Toolbox_AbstractManager.class.php');
require_once(dirname(__FILE__).'/../../class/handler/QA_AnswerExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/QA_AnswersHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/QA_CategoriesHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/QA_CategoryExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/QA_CategoryQuestionRelationsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/QA_QuestionExpressionsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/QA_QuestionsHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/Profile_UsersHandler.class.php');

abstract class Toolbox_QA_AbstractManager extends Toolbox_AbstractManager {

	protected $m_questionsHandler;
	protected $m_questionExpressionsHandler;
	protected $m_answersHandler;
	protected $m_answerExpressionsHandler;
	protected $m_categoriesHandler;
	protected $m_categoryExpressionsHandler;
	protected $m_categoryQuestionRelationsHandler;
	protected $m_userHandler;

	public function __construct() {
		parent::__construct();

		$this->m_questionsHandler =& new QA_QuestionsHandler($this->db);
		$this->m_questionExpressionsHandler =& new QA_QuestionExpressionsHandler($this->db);
		$this->m_answersHandler =& new QA_AnswersHandler($this->db);
		$this->m_answerExpressionsHandler =& new QA_AnswerExpressionsHandler($this->db);
		$this->m_categoriesHandler =& new QA_CategoriesHandler($this->db);
		$this->m_categoryExpressionsHandler =& new QA_CategoryExpressionsHandler($this->db);
		$this->m_categoryQuestionRelationsHandler =& new QA_CategoryQuestionRelationsHandler($this->db);
		$this->m_userHandler =& new Profile_UsersHandler($this->db);
	}

	/**
	 * @param unknown_type $object
	 * @return ToolboxVO_BBS_Category
	 */
	protected function categoryObject2responseVo($object) {
		$category =& new ToolboxVO_QA_QACategory();
		$category->id = $object->get('id');
		$category->language = $object->get('language_code');

		if (!$object->m_expressionsLoaded) {
			$object->_loadExpressions();
		}
		if (!$object->m_relationsLoaded) {
			$object->_loadRelations();
		}
		$category->name = $this->expressionObjects2ResponseVOs($object->expressions);

		$criteria =& new CriteriaCompo();
		$criteria->add(new Criteria('qa_category_id', $object->get('id')));
		$category->qCount = $this->m_categoryQuestionRelationsHandler->getCount($criteria);

		return $category;
	}

	/**
	 * @param unknown_type $object
	 * @return ToolboxVO_QA_QARecord
	 */
	protected function questionObject2responseVO($object) {
		$record =& new ToolboxVO_QA_QARecord();
		$record->id = $object->get('id');
		$record->question = array();
		$record->answers = array();
		$record->categoryIds = array();
		$record->creationDate = $object->get('creation_time');
		$record->updateDate = $object->get('update_time');

		if (!$object->m_answersLoaded) {
			$object->_loadAnswers();
		}
	
		if (!$object->m_expressionsLoaded) {
			$object->_loadExpressions();
		}

		if (!$object->m_relationsLoaded) {
			$object->_loadRelations();
		}

		$record->question = $this->expressionObjects2ResponseVOs($object->expressions);

		foreach ($object->answers as $answer) {
			$record->answers[] = $this->answerObjects2ResponseVO($answer);
		}
		
		foreach ($object->relations as $relation) {
			$record->categoryIds[] = $relation->get('qa_category_id');
		}

		return $record;
	}

	protected function expressionObjects2ResponseVOs($objects) {
		$expressions = array();
		
		foreach ($objects as $object) {
			$expressions[] = $this->expressionObject2ResponseVO($object);
		}
		
		return $expressions;
	}
	
	protected function expressionObject2ResponseVO($object) {
		$tvre =& new ToolboxVO_Resource_Expression();
		$tvre->expression = $object->get('expression');
		$tvre->language = $object->get('language_code');
		
		return $tvre;
	}
	
	protected function answerObjects2ResponseVO($answerObject) {
		$vo =& new ToolboxVO_QA_Answer();
		$vo->id = $answerObject->get('id');
		$vo->creationDate = $answerObject->get('creation_date');
		
		foreach ($answerObject->expressions as $e) {
			$vo->expression[] = $this->expressionObject2ResponseVO($e);
		}

		return $vo;
	}

	protected function getUname($uid) {
		$obj =& $this->m_userHandler->get($uid);
		if ($obj != null) {
			return $obj->get('uname');
		}
	}
	
	protected function getResourceIdByName($name) {
		require_once dirname(__FILE__).'/../handler/CommunityResourceHandler.class.php';
		
		$handler = new CommunityResourceHandler($this->db);
		
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('dictionary_name', $name));
		$criteriaCompo->add(new Criteria('delete_flag', '0'));
		
		$resource = $handler->getObjects($criteriaCompo);
		
		if (!isset($resource[0])) {
			throw new Exception('Resource "'.$name.'" is  not found.');
		}
		
		return $resource[0]->get('user_dictionary_id');
	}
}
?>