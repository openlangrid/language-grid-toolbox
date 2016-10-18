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

class Toolbox_TranslationTemplate_CategoryCreateEditManager extends Toolbox_TranslationTemplateAbstractManager {
	
	/**
	 * 
	 * @param String $name
	 * @param unknown_type $categoryName
	 * @return unknown_type
	 */
	public function addCategory($name, $categoryName) {
		$resourceId = $this->getResourceIdByName($name);
		
		$obj = $this->createCategory($resourceId);
		
		$this->createCategoryExpressions($obj->get('id'), $categoryName);
		
		return $this->categoryObject2responseVo($obj);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @return unknown_type
	 */
	public function deleteCategory($name, $categoryId) {
		$this->doDeleteCategory($categoryId);
		$this->deleteCategoryRelations($categoryId);
		$this->deleteCategoryExpressions($categoryId);
	}
	
	/**
	 * 
	 * @param String $name
	 * @return unknown_type
	 */
	public function deleteAllCategories($name) {
		$resourceId = $this->getResourceIdByName($name);
		
		$manager = new Toolbox_TranslationTemplate_CategoryReadManager();
		$objs = $manager->getCategoriesByResourceId($resourceId);
		
		foreach ($objs as $obj) {
			$this->deleteCategory($name, $obj->get('id'));
		}
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @param String $categoryName
	 * @return unknown_type
	 */
	public function updateCategory($name, $categoryId, $categoryName) {
		$this->deleteCategoryExpressions($categoryId);
		$this->createCategoryExpressions($categoryId, $categoryName);
	}
	
	/**
	 * 
	 * @param int $resourceId
	 * @param int $id
	 * @return unknown_type
	 */
	private function createCategory($resourceId, $id = null) {
		$obj = $this->m_categoriesHandler->create(true);
		$obj->set('id', $id);
		$obj->set('resource_id', $resourceId);
		
		if (!$this->m_categoriesHandler->insert($obj, true)) {
			throw new Toolbox_SQLException();
		}
		
		return $obj;
	}
	
	/**
	 * 
	 * @param int $categoryId
	 * @return unknown_type
	 */
	private function doDeleteCategory($categoryId) {
		$obj = $this->m_categoriesHandler->create(true);
		$obj->set('id', $categoryId);
		
		if (!$this->m_categoriesHandler->delete($obj, true)) {
			throw new Toolbox_SQLException();
		}
	}
	
	/**
	 * 
	 * @param unknown_type $categoryId
	 * @return unknown_type
	 */
	private function deleteCategoryRelations($categoryId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('category_id', $categoryId));
	
		if (!$this->m_categoryTranslationTemplateRelationsHandler->deleteAll($c)) {
			throw new Toolbox_SQLException();
		}
	}
	
	/**
	 * 
	 * @param int $categoryId
	 * @param unknown_type $expressions
	 * @return unknown_type
	 */
	private function createCategoryExpressions($categoryId, $expressions) {
		foreach ($expressions as $expression) {
			$obj = $this->m_categoryExpressionsHandler->create(true);
			
			$obj->set('category_id', $categoryId);
			$obj->set('language_code', $expression->language);
			$obj->set('expression', $expression->expression);
			
			if (!$this->m_categoryExpressionsHandler->insert($obj, true)) {
				throw new Toolbox_SQLException();
			}
		}
	}

	/**
	 * 
	 * @param int $categoryId
	 * @param unknown_type $expressions
	 * @return unknown_type
	 */
	private function deleteCategoryExpressions($categoryId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('category_id', $categoryId));
		
		if (!$this->m_categoryExpressionsHandler->deleteAll($c, true)) {
			throw new Toolbox_SQLException();
		}
	}
}
?>