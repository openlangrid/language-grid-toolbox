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

class Toolbox_TranslationTemplate_RecordReadManager extends Toolbox_TranslationTemplateAbstractManager {

	/**
	 * 
	 * @param String $name
	 * @param String $sortOrder
	 * @param String $orderBy
	 * @param int $offset
	 * @param int $limit
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord[]
	 */
	public function getAllRecords($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$id = $this->getResourceIdByName($name);
		
		$recordObjects = $this->getRecordsByResourceId($id);
		
		$return = array();
		foreach ($recordObjects as $i => $o) {
			$return[] = $this->translationTemplateObject2responseVO($o);
		}
		
		$this->sort($return, $sortOrder, $orderBy, 'creationDate');
		
		return $this->slice($return, $offset, $limit);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord
	 */
	public function getRecord($name, $id) {
		$o = $this->m_translationTemplatesHandler->get($id);
		return $this->translationTemplateObject2responseVO($o);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @param unknown_type $boundWords
	 * @return unknown_type
	 */
	public function fillTranslationTemplate($name, $id, $boundWords) {
		$record = $this->getRecord($name, $id);
		
		foreach ($boundWords as $key => $boundWord) {
			foreach ($boundWord->expressions as $exp) {
				foreach ($record->expressions as $rexp) {
					if ($rexp->language == $exp->language) {
						$rexp->expression = str_replace('['.$key.']', $exp->expression, $rexp->expression);
					}
				}
			}
		}
		
		return $record;
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $categoryId
	 * @return unknown_type
	 */
	public function getRecordsByCategory($name, $categoryId) {
		$id = $this->getResourceIdByName($name);
		
		$recordObjects = $this->getRecordsByCategoryId($id);
		
		$return = array();
		foreach ($recordObjects as $i => $o) {
			$return[] = $this->translationTemplateObject2responseVO($o);
		}
		
		return $return;
	}
	
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function getRecordsByResourceId($id) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('resource_id', $id));
		
		return $this->m_translationTemplatesHandler->getObjects($c);
	}
	
	/**
	 * 
	 * @param unknown_type $categoryId
	 * @return unknown_type
	 */
	private function getRecordsByCategoryId($categoryId) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('category_id', $categoryId));
		
		$objs = $this->m_categoryTranslationTemplateRelationsHandler->getObjects($c);
		
		$ids = array();
		foreach ($objs as $obj) {
			$ids[] = $obj->get('translation_template_id');
		}
		
		$c = new CriteriaCompo();
//		$c->add(new Criteria('id', implode(',', $ids), 'IN'));
		foreach ($ids as $id) {
			$c->add(new Criteria('id', $id), 'OR');
		}
		
		return $this->m_translationTemplatesHandler->getObjects($c);
	}
}
?>