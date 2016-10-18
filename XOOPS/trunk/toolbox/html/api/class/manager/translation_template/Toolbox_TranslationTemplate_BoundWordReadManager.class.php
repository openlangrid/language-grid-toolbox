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

class Toolbox_TranslationTemplate_BoundWordReadManager extends Toolbox_TranslationTemplateAbstractManager {

	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @param String $sortOrder
	 * @param String $orderBy
	 * @param int $offset
	 * @param int $limit
	 * @return unknown_type
	 */
	public function getAllBoundWords($name, $boundWordSetId, $sortOrder, $orderBy, $offset, $limit) {
		$objs = $this->getBoundWordsByBoundWordSetId($boundWordSetId);
		
		$return = array();
		foreach ($objs as $obj) {
			$return[] = $this->boundWordObject2responseVO($obj);
		}
		
		$this->sort($return, $sortOrder, $orderBy);
		
		return $this->slice($return, $offset, $limit);
	}
	
	/**
	 * 
	 * @param int $setId
	 * @return unknown_type
	 */
	public function getBoundWordsByResourceId($resourceId) {
		$manager = new Toolbox_TranslationTemplate_BoundWordSetReadManager();
		$objs = $manager->getBoundWordSetsByResourceId($resourceId);
		
		$return = array();
		foreach ($objs as $obj) {
			$return = array_merge($return, $this->getBoundWordsByBoundWordSetId($obj->get('id')));
		}
		
		return $return;
	}
	
	/**
	 * 
	 * @param int $setId
	 * @return unknown_type
	 */
	public function getBoundWordsByBoundWordSetId($setId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('bound_word_set_id', $setId));
		
		return $this->m_boundWordsHandler->getObjects($c);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $boundWordSetId
	 * @param int $boundWordId
	 * @return unknown_type
	 */
	public function getBoundWord($name, $boundWordSetId, $boundWordId) {
		$obj = $this->m_boundWordsHandler->get($boundWordId);
		return $this->boundWordObject2responseVO($obj);
	}
}
?>