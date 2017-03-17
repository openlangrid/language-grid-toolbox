<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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

require_once(dirname(__FILE__).'/Toolbox_Resource_AbstractManager.class.php');

class Toolbox_Resource_DictionaryReadManager extends Toolbox_Resource_AbstractManager {

	protected $voClass = "ToolboxVO_Dictionary_DictionaryRecord";

	public function __construct() {
		parent::__construct();
	}

	public function getAllRecords($name, $offset = null, $limit = null) {
		$object = $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('user_dictionary_id', $object->get('user_dictionary_id')));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$mCriteria->add(new Criteria('row', '0' , '>'));
		$mCriteria->setSort('row');

//		return $this->getResponsePayload($this->load($mCriteria, $limit, $offset));
		return $this->getResponsePayload($this->load($mCriteria, $offset, $limit));
	}

	public function searchRecords($name, $language, $word, $matchingMethod = 'prefix', $offset = null, $limit = null) {
		$object = $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}
		if (empty($word)) {
			return $this->getErrorResponsePayload('search word is empty.');
		}
		if (empty($matchingMethod)) {
			return $this->getErrorResponsePayload('matchingMethod is empty.');
		}
		$criteria = null;
		switch (strtoupper($matchingMethod)) {
			case 'COMPLETE':
				$criteria =& new Criteria('contents', $word);
				break;
			case 'PREFIX':
				$criteria =& new Criteria('contents', $word.'%', 'LIKE');
				break;
			case 'PARTIAL':
				$criteria =& new Criteria('contents', '%'.$word.'%', 'LIKE');
				break;
			case 'SUFFIX':
				$criteria =& new Criteria('contents', '%'.$word, 'LIKE');
				break;
			default:
				return $this->getErrorResponsePayload('matchingMethod is not supported type. ['.$matchingMethod.']');
				break;
		}
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('user_dictionary_id', $object->get('user_dictionary_id')));
		$mCriteria->add(new Criteria('language', $language));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$mCriteria->add(new Criteria('row', '0' , '>'));
		$mCriteria->add($criteria);
		$mCriteria->setSort('row');
		
//		$objects =& $this->m_contentsHandler->getObjects($criteria);
		$objects =& $this->m_contentsHandler->getObjects($mCriteria);
		unset($mCriteria);
		$mCriteria =& new CriteriaCompo();
		if(count($objects)){
//			$SubCri = new CriteriaCompo();
//			foreach ($objects as $obj) {
//				$SubCri->add(new Criteria('user_dictionary_id', $obj->get('user_dictionary_id')));
//				$SubCri->add(new Criteria('row', $obj->get('row')));
//			}
			foreach ($objects as $obj) {
				$subCriteria = new CriteriaCompo();
				$subCriteria->add(new Criteria('user_dictionary_id', $obj->get('user_dictionary_id')));
				$subCriteria->add(new Criteria('row', $obj->get('row')));
				$mCriteria->add($subCriteria, 'OR');
				unset($subCriteria);
			}
		}else{
			$mCriteria->add(new Criteria('user_dictionary_id', 0));
		}
//		return $this->getResponsePayload($this->load($mCriteria, $limit, $offset));
		return $this->getResponsePayload($this->load($mCriteria, $offset, $limit));
	}


	protected function load($criteria, $offset = null, $limit = null) {

		$objects =& $this->m_contentsHandler->getObjects($criteria);

		if (count($objects)) {
			$tempArray = array();
			$count = -1;
			$records = 0;
			$previewId = -1;
			$previewRow = -1;
			$offset = isset($offset) ? $offset : 0;
			$limit = isset($limit) ? $limit : 999999;
			foreach ($objects as $object) {
				$row = $object->get('row');
				if ($row == '0') {
					continue;
				}
				if ($offset > $count) {
					if (!($previewId == $object->get('user_dictionary_id') && $previewRow == $object->get('row'))) {
						$previewId = $object->get('user_dictionary_id');
						$previewRow = $object->get('row');
						$count++;
					}
					if ($offset > $count) {
						continue;
					}
					$previewId = -1;
					$previewRow = -1;
				}
				if (!($previewId == $object->get('user_dictionary_id') && $previewRow == $object->get('row'))) {
					$previewId = $object->get('user_dictionary_id');
					$previewRow = $object->get('row');
					$records++;
					if ($records > $limit) {
						break;
					}
				}
				if (!isset($tempArray[$row])) {
					$record =& new $this->voClass;
					$record->id = $row;
					$record->expressions = array();
					$tempArray[$row] = $record;
				}
				$exp =& new ToolboxVO_Resource_Expression();
				$exp->language = $object->get('language');
				$exp->expression = $object->get('contents');
				$tempArray[$row]->expressions[] = $exp;
			}

			return array_values($tempArray);
		}
		return array();
	}

}
?>