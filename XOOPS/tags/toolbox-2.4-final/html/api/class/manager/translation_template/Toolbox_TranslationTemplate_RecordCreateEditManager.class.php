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

class Toolbox_TranslationTemplate_RecordCreateEditManager extends Toolbox_TranslationTemplateAbstractManager {
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $expressions
	 * @param unknown_type $wordSetIds
	 * @param unknown_type $categoryIds
	 * @return unknown_type
	 */
	public function addRecord($name, $expressions, $wordSetIds, $categoryIds) {
		$resourceId = $this->getResourceIdByName($name);
		
		$time = time();
		$template = $this->createTemplate(null, $resourceId, $time, $time);
		
		$this->createTemplateExpressions($template->get('id'), $expressions);
		$this->createWordSetsRelation($template->get('id'), $wordSetIds);
		$this->createCategoriesRelation($template->get('id'), $categoryIds);

		return $this->translationTemplateObject2responseVO($template);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return void
	 */
	public function deleteRecord($name, $id) {
		$this->deleteTemplate($id);
		$this->deleteTemplateExpressions($id);
		$this->deleteWordSetsRelation($id);
		$this->deleteCategoriesRelation($id);
	}
	
	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	public function deleteAllRecords($name) {
		$resourceId = $this->getResourceIdByName($name);
		$this->deleteRecordsByResourceId($resourceId);
	}
	
	/**
	 * 
	 * @param int $resourceId
	 * @return void
	 */
	public function deleteRecordsByResourceId($resourceId) {
		$manager = new Toolbox_TranslationTemplate_RecordReadManager();
		$records = $manager->getRecordsByResourceId($resourceId);
		
		foreach ($records as $record) {
			$this->deleteRecord(null, $record->get('id'));
		}
	}
	
	/**
	 * 
	 * @param String $name
	 * @param unknown_type $id
	 * @param unknown_type $expressions
	 * @param unknown_type $wordSetIds
	 * @param unknown_type $categoryIds
	 * @return unknown_type
	 */
	public function updateRecord($name, $id, $expressions, $wordSetIds, $categoryIds) {
		$resourceId = $this->getResourceIdByName($name);
		
		$template = $this->m_translationTemplatesHandler->get($id);
		$creationTime = $template->get('creation_time');
		
		$this->deleteRecord($name, $id);
		
		$template = $this->createTemplate($id, $resourceId, $creationTime, time());
		$this->createTemplateExpressions($id, $expressions);
		$this->createWordSetsRelation($id, $wordSetIds);
		$this->createCategoriesRelation($id, $categoryIds);

		return $this->translationTemplateObject2responseVO($template);
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $resourceId
	 * @param unknown_type $creationTime
	 * @param unknown_type $updateTime
	 * @return unknown_type
	 */
	private function createTemplate($id, $resourceId, $creationTime, $updateTime) {
		$template =& $this->m_translationTemplatesHandler->create(true);
		
		$template->set('id', $id);
		$template->set('resource_id', $resourceId);
		$template->set('creation_time', $creationTime);
		$template->set('update_time', $updateTime);

		if (!$this->m_translationTemplatesHandler->insert($template, true)) {
			throw new Toolbox_SQLException();
		}
		
		return $template;
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	private function deleteTemplate($id) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('id', $id));
		
		$this->m_translationTemplatesHandler->deleteAll($c, true);
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $expressions
	 * @return unknown_type
	 */
	private function createTemplateExpressions($id, $expressions) {
		foreach ($expressions as $expression) {
			$exp = $this->m_translationTemplateExpressionsHandler->create(true);
			$exp->set('translation_template_id', $id);
			$exp->set('language_code', $expression->language);
			$exp->set('expression', $expression->expression);

			if (!$this->m_translationTemplateExpressionsHandler->insert($exp, true)) {
				throw new Toolbox_SQLException();
			}
		}
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	private function deleteTemplateExpressions($id) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('translation_template_id', $id));
		
		$this->m_translationTemplateExpressionsHandler->deleteAll($c, true);
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $wordSetIds
	 * @return unknown_type
	 */
	private function createWordSetsRelation($id, $wordSetIds) {
		if (!is_array($wordSetIds)) {
			return;
		}
		
		foreach ($wordSetIds as $index => $wordSetId) {
			$relations = $this->m_boundWordSetTranslationTemplateRelationsHandler->create(true);
			$relations->set('bound_word_set_id', $wordSetId);
			$relations->set('translation_template_id', $id);
			$relations->set('index', $index);

			if (!$this->m_boundWordSetTranslationTemplateRelationsHandler->insert($relations, true)) {
				throw new Toolbox_SQLException();
			}
		}
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	private function deleteWordSetsRelation($id) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('translation_template_id', $id));
		
		$this->m_boundWordSetTranslationTemplateRelationsHandler->deleteAll($c, true);
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $categoryIds
	 * @return unknown_type
	 */
	private function createCategoriesRelation($id, $categoryIds) {
		if (!is_array($categoryIds)) {
			return;
		}
		
		foreach ($categoryIds as $categoryId) {
			$relations = $this->m_categoryTranslationTemplateRelationsHandler->create(true);
			$relations->set('translation_template_id', $id);
			$relations->set('category_id', $categoryId);
		
			if (!$this->m_categoryTranslationTemplateRelationsHandler->insert($relations, true)) {
				throw new Toolbox_SQLException();
			}
		}
	}

	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	private function deleteCategoriesRelation($id) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('translation_template_id', $id));
		
		$this->m_categoryTranslationTemplateRelationsHandler->deleteAll($c, true);
	}
}
?>