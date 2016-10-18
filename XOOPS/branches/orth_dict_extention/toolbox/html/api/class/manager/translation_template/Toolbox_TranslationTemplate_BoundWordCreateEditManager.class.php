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

class Toolbox_TranslationTemplate_BoundWordCreateEditManager extends Toolbox_TranslationTemplateAbstractManager {
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $boundWordSetId
	 * @param unknown_type $expressions
	 * @return unknown_type
	 */
	public function addBoundWord($name, $boundWordSetId, $expressions) {
		$obj = $this->createBoundWord($boundWordSetId);
		
		$this->createBoundWordExpressions($obj->get('id'), $expressions);
		
		return $this->boundWordObject2responseVO($obj);
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $boundWordSetId
	 * @param unknown_type $boundWordId
	 * @return unknown_type
	 */
	public function deleteBoundWord($name, $boundWordSetId, $boundWordId) {
		$this->doDeleteBoundWord($boundWordId);
		$this->deleteBoundWordExpressions($boundWordId);
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $boundWordSetId
	 * @return unknown_type
	 */
	public function deleteAllBoundWords($name, $boundWordSetId) {
		$manager = new Toolbox_TranslationTemplate_BoundWordReadManager();
		$objs = $manager->getBoundWordsByBoundWordSetId($boundWordSetId);
		
		foreach ($objs as $obj) {
			$this->deleteBoundWord($name, $boundWordSetId, $obj->get('id'));
		}
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $boundWordSetId
	 * @param unknown_type $boundWordId
	 * @return unknown_type
	 */
	public function updateBoundWord($name, $boundWordSetId, $boundWordId, $expressions) {
		$this->deleteBoundWordExpressions($boundWordId);
		$this->createBoundWordExpressions($boundWordId, $expressions);
	}
	
	/**
	 * 
	 * @param int $boundWordSetId
	 * @param int $id
	 * @return unknown_type
	 */
	private function createBoundWord($boundWordSetId, $id = null) {
		$obj = $this->m_boundWordsHandler->create(true);
		$obj->set('id', $id);
		$obj->set('bound_word_set_id', $boundWordSetId);
		
		if (!$this->m_boundWordsHandler->insert($obj, true)) {
			throw new Toolbox_SQLException();
		}
		
		return $obj;
	}
	
	/**
	 * 
	 * @param unknown_type $boundWordId
	 * @return unknown_type
	 */
	private function doDeleteBoundWord($boundWordId) {
		$obj = $this->m_boundWordsHandler->create(true);
		$obj->set('id', $boundWordId);
		
		if (!$this->m_boundWordsHandler->delete($obj, true)) {
			throw new Toolbox_SQLException();
		}
	}
	
	/**
	 * 
	 * @param unknown_type $boundWordId
	 * @param unknown_type $expressions
	 * @return unknown_type
	 */
	private function createBoundWordExpressions($boundWordId, $expressions) {
		foreach ($expressions as $expression) {
			$obj = $this->m_boundWordExpressionsHandler->create(true);
			
			$obj->set('bound_word_id', $boundWordId);
			$obj->set('language_code', $expression->language);
			$obj->set('expression', $expression->expression);
			
			if (!$this->m_boundWordExpressionsHandler->insert($obj, true)) {
				throw new Toolbox_SQLException();
			}
		}
	}
	
	/**
	 * 
	 * @param unknown_type $boundWordId
	 * @return unknown_type
	 */
	private function deleteBoundWordExpressions($boundWordId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('bound_word_id', $boundWordId));
		
		if (!$this->m_boundWordExpressionsHandler->deleteAll($c, true)) {
			throw new Toolbox_SQLException();
		}
	}
}
?>