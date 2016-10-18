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

/**
 * 
 * @author kitajima
 *
 */
class Toolbox_Glossary_CategoryCreateEditManager extends Toolbox_Glossary_AbstractManager {

	/**
	 * 
	 * @param String $name
	 * @param String $languageCode
	 * @param ToolboxVO_Resource_Expression[] $expressions
	 * @throws Exception
	 * @return ToolboxVO_Glossary_GlossaryCategory
	 */
	public function addCategory($name, $languageCode, $expressions) {
		$category =& $this->m_categoriesHandler->create(true);
		$category->set('resource_id', $this->getResourceIdByName($name));
		$category->set('language_code', $languageCode);

		if (!$this->m_categoriesHandler->insert($category, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		foreach ($expressions as $expression) {
			$exp =& $this->m_categoryExpressionsHandler->create(true);
			$exp->set('glossary_category_id', $category->get('id'));
			$exp->set('language_code', $expression->language);
			$exp->set('expression', $expression->expression);

			if (!$this->m_categoryExpressionsHandler->insert($exp, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}

		return $this->categoryObject2responseVo($category);
	}

	/**
	 * @param int $categoryId
	 * @return void
	 */
	public function deleteCategory($categoryId) {
		$object =& $this->m_categoriesHandler->get($categoryId);
		if (!$this->m_categoriesHandler->delete($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_category_id', $categoryId));
		if (!$this->m_categoryExpressionsHandler->deleteAll($criteriaCompo, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		if (!$this->m_categoryTermRelationsHandler->deleteAll($criteriaCompo, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
	}

	/**
	 * @param int $categoryId
	 * @param ToolboxVO_Resource_Expression[] $expressions
	 * @throws Exception
	 * @return void
	 */
	public function updateCategory($categoryId, $expressions) {
		$category =& $this->m_categoriesHandler->get($categoryId);

		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_category_id', $categoryId));
		if (!$this->m_categoryExpressionsHandler->deleteAll($criteriaCompo)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		foreach ($expressions as $expression) {
			$exp =& $this->m_categoryExpressionsHandler->create(true);
			$exp->set('glossary_category_id', $categoryId);
			$exp->set('language_code', $expression->language);
			$exp->set('expression', $expression->expression);
			if (!$this->m_categoryExpressionsHandler->insert($exp, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}
	}
}
?>