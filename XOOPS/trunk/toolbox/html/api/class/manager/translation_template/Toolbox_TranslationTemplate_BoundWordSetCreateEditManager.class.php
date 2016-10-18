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

class Toolbox_TranslationTemplate_BoundWordSetCreateEditManager extends Toolbox_TranslationTemplateAbstractManager {

	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $setName
	 * @return unknown_type
	 */
	public function addBoundWordSet($name, $setName) {
		$resourceId = $this->getResourceIdByName($name);
		
		$sequence = $this->createBoundWordSetSequence();
		
		$obj = $this->createBoundWordSet($sequence->get('id'), $resourceId);
		
		$this->createBoundWordSetExpressions($sequence->get('id'), $setName);
		
		return $this->boundWordSetObject2responseVO($obj);
	}
	
	/**
	 * 
	 * @param String $type
	 * @return void
	 */
	public function addDefaultBoundWordSet($type) {
		$sequence = $this->createBoundWordSetSequence();

		$o = $this->m_defaultBoundWordSetsHandler->create(true);
		$o->set('id', $sequence->get('id'));
		$o->set('type', $type);

		if (!$this->m_defaultBoundWordSetsHandler->insert($o, true)) {
			throw new Toolbox_SQLException();
		}
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	public function deleteBoundWordSet($name, $boundWordSetId) {
		$this->deleteBoundWordSetSequence($boundWordSetId);
		$this->doDeleteBoundWordSet($boundWordSetId);
		$this->deleteBoundWordSetExpressions($boundWordSetId);
		$this->deleteBoundWordSetRelations($boundWordSetId);
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	public function deleteAllBoundWordSets($name) {
		$resourceId = $this->getResourceIdByName($name);
		
		$manager = new Toolbox_TranslationTemplate_BoundWordSetReadManager();
		$sets = $manager->getBoundWordSetsByResourceId($resourceId);
		
		foreach ($sets as $set) {
			$this->deleteBoundWordSet($name, $set->get('id'));
		}
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $id
	 * @param unknown_type $setName
	 * @return unknown_type
	 */
	public function updateBoundWordSet($name, $id, $setName) {
		$this->deleteBoundWordSetExpressions($id);
		$this->createBoundWordSetExpressions($id, $setName);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function createBoundWordSetSequence($id = null) {
		$obj = $this->m_boundWordSetIdsHandler->create(true);
		$obj->set('id', $id);
		
		if (!$this->m_boundWordSetIdsHandler->insert($obj, true)) {
			throw new Toolbox_SQLException();
		}
		
		return $obj;
	}

	/**
	 * 
	 * @return unknown_type
	 */
	private function deleteBoundWordSetSequence($id) {
		$obj = $this->m_boundWordSetIdsHandler->create(false);
		$obj->set('id', $id);
		
		if (!$this->m_boundWordSetIdsHandler->delete($obj, true)) {
			throw new Toolbox_SQLException();
		}
	}

	/**
	 * 
	 * @param int $id
	 * @param int $resourceId
	 * @return unknown_type
	 */
	private function createBoundWordSet($id, $resourceId) {
		$obj = $this->m_boundWordSetsHandler->create(true);
		$obj->set('id', $id);
		$obj->set('resource_id', $resourceId);
		
		if (!$this->m_boundWordSetsHandler->insert($obj, true)) {
			throw new Toolbox_SQLException();
		}
		
		$obj->set('id', $id);
		
		return $obj;
	}

	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $resourceId
	 * @return unknown_type
	 */
	private function doDeleteBoundWordSet($id) {
		$obj = $this->m_boundWordSetsHandler->create(true);
		$obj->set('id', $id);
		
		if (!$this->m_boundWordSetsHandler->delete($obj, true)) {
			throw new Toolbox_SQLException();
		}
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $setNames
	 * @return unknown_type
	 */
	private function createBoundWordSetExpressions($id, $setNames) {
		foreach ($setNames as $setName) {
			$obj = $this->m_boundWordSetExpressionsHandler->create(true);
			
			$obj->set('bound_word_set_id', $id);
			$obj->set('language_code', $setName->language);
			$obj->set('expression', $setName->expression);
			
			if (!$this->m_boundWordSetExpressionsHandler->insert($obj, true)) {
				throw new Toolbox_SQLException();
			}
		}
	}

	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	private function deleteBoundWordSetExpressions($id) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('bound_word_set_id', $id));
		
		if (!$this->m_boundWordSetExpressionsHandler->deleteAll($c, true)) {
			throw new Toolbox_SQLException();
		}
	}
	
	/**
	 * 
	 * @param unknown_type $categoryId
	 * @return unknown_type
	 */
	private function deleteBoundWordSetRelations($boundWordSetId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('bound_word_set_id', $boundWordSetId));
	
		if (!$this->m_boundWordSetTranslationTemplateRelationsHandler->deleteAll($c)) {
			throw new Toolbox_SQLException();
		}
	}
}
?>