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
class Toolbox_Glossary_CategoryReadManager extends Toolbox_Glossary_AbstractManager {

	/**
	 * 
	 * @param String $name
	 * @return ToolboxVO_Glossary_GlossaryCategory[]
	 */
	public function getAllCategories($name) {
		$resourceId = $this->getResourceIdByName($name);
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('resource_id', $resourceId));

		$objects = $this->m_categoriesHandler->getObjects($criteriaCompo);
		$categories = array();
		foreach ($objects as $category) {
			$categories[] = $this->categoryObject2responseVo($category);
		}

		return $categories;
	}

	/**
	 * @param int $categoryId
	 * @return ToolboxVO_Glossary_GlossaryCategory
	 */
	public function getCategory($categoryId) {
		$category = $this->m_categoriesHandler->get($categoryId);
		return $this->categoryObject2responseVo($category);
	}
}
?>