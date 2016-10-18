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

class Toolbox_TranslationTemplate_CategoryReadManager extends Toolbox_TranslationTemplateAbstractManager {

	/**
	 * 
	 * @param String $name
	 * @param String $sortOrder
	 * @param String $orderBy
	 * @param int $offset
	 * @param int $limit
	 * @return unknown_type
	 */
	public function getAllCategories($name, $sortOrder, $orderBy, $offset, $limit) {
		$resourceId = $this->getResourceIdByName($name);
		
		$objs = $this->getCategoriesByResourceId($resourceId);
		
		$return = array();
		foreach ($objs as $obj) {
			$return[] = $this->categoryObject2responseVo($obj);
		}
		
		$this->sort($return, $sortOrder, $orderBy);
		
		return $this->slice($return, $offset, $limit);
	}
	
	/**
	 * 
	 * @param int $resourceId
	 * @return unknown_type
	 */
	public function getCategoriesByResourceId($resourceId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('resource_id', $resourceId));
		
		return $this->m_categoriesHandler->getObjects($c);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @return unknown_type
	 */
	public function getCategory($name, $categoryId) {
		$obj = $this->m_categoriesHandler->get($categoryId);
		return $this->categoryObject2responseVo($obj);
	}
}
?>