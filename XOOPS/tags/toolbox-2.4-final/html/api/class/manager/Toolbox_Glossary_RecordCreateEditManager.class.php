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

class Toolbox_Glossary_RecordCreateEditManager extends Toolbox_Glossary_AbstractManager {
	
	protected $m_definitionManager;
	protected $m_termManager;
	
	public function __construct() {
		parent::__construct();
		$this->m_termManager = new Toolbox_Glossary_TermManager();
		$this->m_definitionManager = new Toolbox_Glossary_DefinitionManager();
	}

	/**
	 * 
	 * @param String $name
	 * @param ToolboxVO_Resource_Expression[] $term
	 * @param ToolboxVO_Resource_Expression[][] $definitions
	 * @param int[] $categoryIds
	 * @return ToolboxVO_Glossary_GlossaryRecord
	 */
	public function addRecord($name, $termExpressions, $definitions, $categoryIds = null) {
		$resourceId = $this->getResourceIdByName($name);

		$term = $this->m_termManager->createTerm($resourceId, $termExpressions);
		$this->m_termManager->setCategory($term->get('id'), $categoryIds);
		$definitions = $this->m_definitionManager->createDefinitions($term->get('id'), $definitions);

		return $this->termObject2responseVo($term);
	}

	/**
	 * @param int $termId
	 * @param ToolboxVO_Resource_Expression[] $term
	 * @param ToolboxVO_Glossary_Definition[][] $definitions
	 * @param int[] $categoryIds
	 * @return void
	 */
	public function updateRecord($termId, $termExpressions, $definitions, $categoryIds = null) {
		$term = $this->m_termManager->updateTerm($termId, $termExpressions);
		$this->m_termManager->setCategory($termId, $categoryIds);
		$definitions = $this->m_definitionManager->updateDefinitions($termId, $definitions);
		
		$term->_loadDefinitions();
		return $this->termObject2responseVo($term);
	}

	/**
	 * @param int $termId
	 * @return void
	 */
	public function deleteRecord($termId) {
		$this->m_termManager->deleteTerm($termId);
		$this->m_definitionManager->deleteDefinitions($termId);
		$this->m_categoryTermRelationsHandler->deleteByTermId($termId);
	}

	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	public function deleteAllRecords($name) {
		$resourceId = $this->getResourceIdByName($name);
		$terms = $this->m_termManager->getTerms();
		foreach ($terms as $term) {
			$this->deleteRecord($term->get('id'));
		}
	}
	
	/**
	 * 
	 * @param int $id
	 * @return void
	 */
	public function deleteLanguage($name, $language) {
		$resourceId = $this->getResourceIdByName($name);
		$terms = $this->m_termManager->getTerms($resourceId);

		foreach ($terms as $term) {
			$this->m_termManager->deleteLanguage($term->get('id'), $language);
			foreach ($term->definitions as $definition) {
				$this->m_definitionManager->deleteLanguage($definition->get('id'), $language);
			}
		}
	}
}
?>