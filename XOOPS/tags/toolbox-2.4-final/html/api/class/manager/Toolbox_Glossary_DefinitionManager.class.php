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

class Toolbox_Glossary_DefinitionManager extends Toolbox_Glossary_AbstractManager {

	/**
	 * 
	 * @param int $termId
	 * @param ToolboxVO_Resource_Expression[][] $definitions
	 * @return ToolboxVO_Resource_Expression[][]
	 */
	public function createDefinitions($termId, $definitions) {
		foreach ($definitions as $definitionExpressions) {
			$definition =& $this->m_definitionsHandler->create(true);
			$definition->set('glossary_term_id', $termId);
			$definition->set('id', $definitionExpressions->id);

			if (!$this->m_definitionsHandler->insert($definition, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}

			$this->createDefinitionExpressions($definition->get('id'), $definitionExpressions->expression);
		}
		return $definitions;
	}
	
	/**
	 * Delete & Insert
	 * @param $termId
	 * @param $definitions
	 * @return unknown_type
	 */
	public function updateDefinitions($termId, $definitions) {
		$this->deleteDefinitions($termId);
		$this->createDefinitions($termId, $definitions);
	}
	
	/**
	 * 答え取得
	 * @param $termId
	 * @return unknown_type
	 */
	public function getDefinitions($termId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_term_id', $termId));
		return $this->m_definitionsHandler->getObjects($criteriaCompo);
	}

	/**
	 * 引数の質問IDの答えを全部消す
	 * @param $termId
	 * @return unknown_type
	 */
	public function deleteDefinitions($termId) {
		foreach ($this->getDefinitions($termId) as $definition) {
			$this->deleteDefinitionExpressions($definition->get('id'));
			$this->m_definitionsHandler->delete($definition);
		}
	}

	/**
	 * 引数の答えIDを全部消す
	 * @param $definitionId
	 * @return unknown_type
	 */
	public function deleteDefinitionExpressions($definitionId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_definition_id', $definitionId));
		if (!$this->m_definitionExpressionsHandler->deleteAll($criteriaCompo, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
	}

	public function createDefinitionExpressions($definitionId, $definitionExpressions) {
		foreach ($definitionExpressions as $definitionExpression) {
			$exp =& $this->m_definitionExpressionsHandler->create(true);
			$exp->set('glossary_definition_id', $definitionId);
			$exp->set('language_code', $definitionExpression->language);
			$exp->set('expression', $definitionExpression->expression);

			if (!$this->m_definitionExpressionsHandler->insert($exp, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}

	public function deleteLanguage($id, $language) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_definition_id', $id));
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