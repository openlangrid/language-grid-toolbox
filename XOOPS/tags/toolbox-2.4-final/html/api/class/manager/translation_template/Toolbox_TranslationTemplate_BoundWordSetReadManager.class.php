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

class Toolbox_TranslationTemplate_BoundWordSetReadManager extends Toolbox_TranslationTemplateAbstractManager {
	
	/**
	 * 
	 * @param String $name
	 * @param String $sortOrder
	 * @param String $orderBy
	 * @param int $offset
	 * @param int $limit
	 * @return unknown_type
	 */
	public function getAllBoundWordSets($name, $sortOrder, $orderBy, $offset, $limit) {
		$id = $this->getResourceIdByName($name);
		
		$objs = $this->getBoundWordSetsByResourceId($id);
		
		$return = array();
		foreach ($objs as $obj) {
			$return[] = $this->boundWordSetObject2responseVO($obj);
		}
		
		$return = array_merge($return, $this->getDefaultBoundWordSets());
		
		$this->sort($return, $sortOrder, $orderBy);
		
		return $this->slice($return, $offset, $limit);
	}
	
	/**
	 * 
	 * @param int $id
	 * @return TranslationTemplate_BoundWordSetsObject[]
	 */
	public function getDefaultBoundWordSets() {
		$boundWordSets = $this->m_defaultBoundWordSetsHandler->getObjects();
		
		$return = array();
		foreach ($boundWordSets as $b) {
			$return[] = $this->defaultBoundWordSetObject2responseVO($b);
		}
		
		return $return;
	}
	
	/**
	 * 
	 * @param int $id
	 * @return TranslationTemplate_BoundWordSetsObject[]
	 */
	public function getBoundWordSetsByResourceId($id) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('resource_id', $id));
		
		return $this->m_boundWordSetsHandler->getObjects($c);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $boundWordSetId
	 * @return ToolboxVO_TranslationTemplate_BoundWordSet
	 */
	public function getBoundWordSet($name, $boundWordSetId) {
		$obj = $this->m_defaultBoundWordSetsHandler->get($boundWordSetId);
		
		if (!$obj) {
			$obj = $this->m_boundWordSetsHandler->get($boundWordSetId);
		}
		
		return $this->BoundWordSetObject2responseVO($obj);
	}
}
?>