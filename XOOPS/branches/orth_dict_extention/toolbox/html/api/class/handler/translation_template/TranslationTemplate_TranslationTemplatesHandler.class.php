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

require_once(dirname(__FILE__).'/../Toolbox_ObjectGenericHandler.class.php');

class TranslationTemplate_TranslationTemplatesObject extends XoopsSimpleObject {

	public $expressions;
	public $m_expressionsLoaded = false;
	public $boundWordSetRelations;
	public $m_boundWordSetRelationsLoaded = false;
	public $categoryRelations;
	public $m_categoryRelationsLoaded = false;

	public function TranslationTemplate_TranslationTemplatesObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('resource_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('creation_time', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, false);
	}
	
	public function _load() {
		$this->_loadExpressions();
		$this->_loadBoundWordSetRelations();
		$this->_loadCategoryRelations();
	}

	public function _loadExpressions() {
		$handler =& $this->_getExpressionsHandler();
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('translation_template_id', $this->get('id')));
		
		$this->expressions =& $handler->getObjects($criteria);
		if ($this->expressions) {
			$this->m_expressionsLoaded = true;
		}
	}
	
	public function _loadBoundWordSetRelations() {
		$handler =& $this->_getBoundWordSetRelationsHandler();
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('translation_template_id', $this->get('id')));
		
		$this->boundWordSetRelations =& $handler->getObjects($criteria);
		if ($this->boundWordSetRelations) {
			$this->m_boundWordSetRelationsLoaded = true;
		}
	}
	
	public function _loadCategoryRelations() {
		$handler =& $this->_getCategoryRelationsHandler();
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('translation_template_id', $this->get('id')));
		
		$this->categoryRelations =& $handler->getObjects($criteria);
		if ($this->categoryRelations) {
			$this->m_categoryRelationsLoaded = true;
		}
	}

	private function _getExpressionsHandler() {
		require_once(dirname(__FILE__).'/TranslationTemplate_TranslationTemplateExpressionsHandler.class.php');
		$handler = new TranslationTemplate_TranslationTemplateExpressionsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}

	private function _getBoundWordSetRelationsHandler() {
		require_once(dirname(__FILE__).'/TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler.class.php');
		$handler = new TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}

	private function _getCategoryRelationsHandler() {
		require_once(dirname(__FILE__).'/TranslationTemplate_CategoryTranslationTemplateRelationsHandler.class.php');
		$handler = new TranslationTemplate_CategoryTranslationTemplateRelationsHandler($GLOBALS['xoopsDB']);
		return $handler;
	}
}

class TranslationTemplate_TranslationTemplatesHandler extends XoopsObjectGenericHandler {

	public $mTable = "template_translation_templates";
	public $mPrimary = "id";
	public $mClass = "TranslationTemplate_TranslationTemplatesObject";

	public function &get($id) {
		$object =& parent::get($id);
		
		if ($object) {
			$object->_load();
		}
		
		return $object;
	}

	public function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false) {
		$objects =& parent::getObjects($criteria, $limit, $start, $id_as_key);

		if (count($objects)) {
			$this -> loadRelationObects($objects);			
		}
		
		return $objects;
	}
	
	protected function loadRelationObects($objects) {
		$ids = array();
		foreach($objects as $obj) {
			array_push($ids, $obj->get('id'));
		}

		$criteria = $this -> getCriteriaCompo($ids);		
		$this -> loadExpressions($objects, $criteria);
		$this -> loadBoundWordSetRelationsHandler($objects, $criteria);
		$this -> loadCategoryRelationsHandler($objects, $criteria);
	}
	
	protected function loadExpressions($templates, $criteria) {
		require_once(dirname(__FILE__).'/TranslationTemplate_TranslationTemplateExpressionsHandler.class.php');
		$handler = new TranslationTemplate_TranslationTemplateExpressionsHandler($GLOBALS['xoopsDB']);

		$hash = $this -> groupByTemplateId($handler->getObjects($criteria));
		foreach($templates as $template) {
			$id = $template -> get('id');
			if (isset($hash[$id])) {
				$template->expressions = $hash[$id];
			}
			$template->m_expressionsLoaded = true;	
		}
		return $templates;
	}

	protected function loadBoundWordSetRelationsHandler($templates, $criteria) {
		//require_once(dirname(__FILE__).'/TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler.class.php');
		//$handler = new TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler($GLOBALS['xoopsDB']);
		
		//$hash = $this -> groupByTemplateId($handler->getObjects($criteria));
		foreach($templates as $template) {
			$id = $template -> get('id');
		//	$template->boundWordSetRelations = $hash[$id];
			$template->boundWordSetRelations = array();
			$template->m_boundWordSetRelationsLoaded = true;	
		}
		return $templates;
	}

	protected function loadCategoryRelationsHandler($templates, $criteria) {
		//require_once(dirname(__FILE__).'/TranslationTemplate_CategoryTranslationTemplateRelationsHandler.class.php');
		//$handler = new TranslationTemplate_CategoryTranslationTemplateRelationsHandler($GLOBALS['xoopsDB']);
		
		//$hash = $this -> groupByTemplateId($handler->getObjects($criteria));
		foreach($templates as $template) {
			$id = $template -> get('id');
		//	$template->boundWordSetRelations = $hash[$id];
			$template->categoryRelations = array();
			$template->m_categoryRelationsLoaded = true;	
		}
		return $templates;
	}
	
	protected function getCriteriaCompo($ids) {
		$criteria = new CriteriaCompo();
		foreach ($ids as $id) {
			$criteria->add(new Criteria('translation_template_id', $id), "OR");			
		}
		return $criteria;
	}
	
	protected function groupByTemplateId($objects) {
		$result = array();
		foreach($objects as $obj) {
			if(!@$result[$obj->get('translation_template_id')]) {
				$result[$obj->get('translation_template_id')] = array();
			}
			array_push($result[$obj->get('translation_template_id')], $obj);
		}
		return $result;
	}
	
	function getObjectsJoinExpressions($criteria, $limit = null, $start = null, $id_as_key = null) {
		$ret = array();
		
		$columnNgram = $this ->matchAgainstColumn($criteria);
		
		$sql =<<<SQL
		SELECT
			tt.*
			{$columnNgram}
		FROM
			{$this->mTable} tt
			INNER JOIN {$this->db->prefix}_template_translation_template_expressions tte 
				ON tt.id = tte.translation_template_id 
SQL;

		if($criteria !== null && is_a($criteria, 'CriteriaElement')) {
			$where = $this->_makeCriteria4sql($criteria);
			if (trim($where)) {
				$sql .= " WHERE " . $where;
			}

			$sorts = array();
			foreach ($criteria->getSorts() as $sort) {
                $sorts[] = '`' . $sort['sort'] . '` ' . $sort['order'];
			}
			if ($criteria->getSort() != '') {
				$sql .= " ORDER BY " . implode(',', $sorts);
			}

			if ($limit === null) {
				$limit = $criteria->getLimit();
			}

			if ($start === null) {
				$start = $criteria->getStart();
			}
		}
		else {
			if ($limit === null) {
				$limit = 0;
			}

			if ($start === null) {
				$start = 0;
			}
		}

        debugLog($sql);
		$result = $this->db->query($sql, $limit, $start);

		if (!$result) {
			return $ret;
		}

		while($row = $this->db->fetchArray($result)) {
			$obj = new $this->mClass();
			if($columnNgram) {
				$obj->initVar('match_value', XOBJ_DTYPE_FLOAT, 0, false);
			}

			$obj->assignVars($row);
			$obj->unsetNew();

			if ($id_as_key)	{
				$ret[$obj->get($this->mPrimary)] =& $obj;
			}
			else {
				$ret[]=&$obj;
			}

			unset($obj);
		}

		if (is_resource($result)) {
			mysql_free_result($result);
		}

		return $ret;
	}
	
	protected function matchAgainstColumn($criteria) {
		foreach($criteria->criteriaElements as $child) {
			if($child->getName() == "ngram") {
				$name = sprintf($child->function, $child->getName());
				return ", ".$name . " " . $child->getValue(). " as match_value ";
			}
		}
		return "";
	}

	function _makeCriteria4sql($criteria)
	{
		if ($this->_mDummyObj == null) {
			$this->_mDummyObj =& $this->create();
		}

		return $this->_makeCriteriaElement4sql($criteria, $this->_mDummyObj);
	}

	function _makeCriteriaElement4sql($criteria, &$obj)
	{
		
		if (is_a($criteria, "CriteriaElement")) {
			if ($criteria->hasChildElements()) {
				$queryString = "";
				$maxCount = $criteria->getCountChildElements();

	            $queryString = '('. $this->_makeCriteria4sql($criteria->getChildElement(0));
	            for ($i = 1; $i < $maxCount; $i++) {
					$queryString .= " " . $criteria->getCondition($i) . " " . $this->_makeCriteria4sql($criteria->getChildElement($i));
	            }
	            $queryString .= ')';

	            return $queryString;
			}
			else {
				//
				// Render
				//
				$name = $criteria->getName();
				$value = $criteria->getValue();
				
				if ($name != null && isset($obj->mVars[$name])) {
					if ($value === null) {
						$criteria->operator = $criteria->getOperator() == '=' ? "IS" : "IS NOT";
						$value = "NULL";
					}
					else {
						
						switch ($obj->mVars[$name]['data_type']) {
							case XOBJ_DTYPE_BOOL:
								$value = $value ? "1" : "0";
								break;

							case XOBJ_DTYPE_INT:
								$value = intval($value);
								break;

							case XOBJ_DTYPE_FLOAT:
								$value = floatval($value);
								break;

							case XOBJ_DTYPE_STRING:
							case XOBJ_DTYPE_TEXT:
								if(stripos($value, "against(") === false) {
									$value = $this->db->quoteString($value);
								}
								break;

							default:
								$value = $this->db->quoteString($value);
								
						}
					}
				} else {
					if(stripos($value, "against(") === false) {
				    	$value = $this->db->quoteString($value);
					}
				}

				if ($name != null) {
					if ($criteria->function != null) {
						$name = sprintf($criteria->function, $name);
					}
					return $name . " " . $criteria->getOperator() . " " . $value;
				}
				else {
					return null;
				}
			}
		}
	}
}
?>
