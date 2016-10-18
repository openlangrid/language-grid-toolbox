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

class Toolbox_Resource_DictionaryCreateEditManager extends Toolbox_Resource_AbstractManager {

	protected $voClass = "ToolboxVO_Dictionary_DictionaryRecord";

	public function __construct() {
		parent::__construct();
	}

	public function setRecords($name, $records) {
		$object =& $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}
		$userDictionaryId = $object->get('user_dictionary_id');

		$currentLanguageArray = $this->m_contentsHandler->getLanguages($userDictionaryId);

		if (!$this->m_contentsHandler->allRealDeleteContents($userDictionaryId)) {
			return $this->getErrorResponsePayload("create contents failed.");
			//die('SQL Error'.__FILE__.'('.__LINE__.')');
		}
		for ($i = 0; $i < count($records); $i++) {
			$row = $records[$i]->id;
			$exps = $this->_exp2hash($records[$i]->expressions);
			foreach ($currentLanguageArray as $language) {
				$contents = '';
				if (array_key_exists($language, $exps)) {
					$contents = $exps[$language];
				}
				$contentsObject =& $this->m_contentsHandler->create(true);
				$contentsObject->set('user_dictionary_id', $userDictionaryId);
				$contentsObject->set('language', $language);
				$contentsObject->set('row', $row);
				$contentsObject->set('contents', $contents);
				if (!$this->m_contentsHandler->insert($contentsObject, true)) {
					//die('SQL Error'.__FILE__.'('.__LINE__.')');
					return $this->getErrorResponsePayload("create contents failed.");
				}
			}
		}
		return $this->getResponsePayload(true);
	}

	public function addRecord($name, $expressions, $recordId = null) {
		$object =& $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}
		$userDictionaryId = $object->get('user_dictionary_id');
		$currentLanguageArray = $this->m_contentsHandler->getLanguages($userDictionaryId);

		if ($recordId == null) {
			$row = $this->m_contentsHandler->getCurrentMaxRow($userDictionaryId) + 1;
		} else {
			$row = $recordId;
		}

		$exps = $this->_exp2hash($expressions);
		$retExpressions = array();
		foreach ($currentLanguageArray as $language) {
			$contents = '';
			if (array_key_exists($language, $exps)) {
				$contents = $exps[$language];
			}
			$contentsObject =& $this->m_contentsHandler->create(true);
			$contentsObject->set('user_dictionary_id', $userDictionaryId);
			$contentsObject->set('language', $language);
			$contentsObject->set('row', $row);
			$contentsObject->set('contents', $contents);
			if (!$this->m_contentsHandler->insert($contentsObject, true)) {
				//die('SQL Error'.__FILE__.'('.__LINE__.')');
				return $this->getErrorResponsePayload("create contents failed.");
			}
		}
		return $this->getResponsePayload($this->contentsObject2ResponseVO($userDictionaryId,$expressions));
	}

	public function deleteRecord($name, $recordId) {
		$object =& $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}
		$userDictionaryId = $object->get('user_dictionary_id');

		if($this->m_contentsHandler->realDeleteContentsByRow($userDictionaryId, $recordId)){
			return $this->getResponsePayload(true);
		}else{
			return $this->getErrorResponsePayload("delete contents failed.");
		}
	}

	public function updateRecord($name, $recordId, $expressions) {
		$this->deleteRecord($name, $recordId);
		$this->addRecord($name, $expressions, $recordId);
		return $this->getResponsePayload(true);
		//return true;
	}

	private function _exp2hash($exps) {
		$ary = array();
		foreach ($exps as $exp) {
			$ary[$exp->language] = $exp->expression;
		}
		return $ary;
	}
	
	protected function contentsObject2ResponseVO($id,$expressions,$priority = null) {
		$record = new ToolboxVO_Dictionary_DictionaryRecord();
		$record->id = $id;
		$record->expressions = $expressions;
		$record->priority = "";
		return $record;
	}

}
?>