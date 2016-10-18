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

class Toolbox_Resource_GetManager extends Toolbox_Resource_AbstractManager {

	public function __construct() {
		parent::__construct();
	}

	public function getAllResources($type = null, $offset = null, $limit = null) {
		$objects = null;
		switch (strtoupper($type)) {
			case 'DICTIONARY':
				$objects = $this->getDictionarys($offset, $limit);
				break;
			case 'PARALLELTEXT';
				$objects = $this->getParallelTexts($offset, $limit);
				break;
			case 'QA';
				$objects = $this->getQAResources($offset, $limit);
				break;
			case 'GLOSSARY';
				$objects = $this->getGlossaryResources($offset, $limit);
				break;
			case 'TRANSLATION_TEMPLATE';
				$objects = $this->getTranslationTemplateResources($offset, $limit);
				break;
			default:
				$objects = $this->getAnyResources($offset, $limit);
				break;
		}

		$dist = array();
		if(is_array($objects)){
			foreach ($objects as $object) {
				$dist[] = $this->object2responseVo($object);
				unset($object);
			}
		}
		return $this->getResponsePayload($dist);
	}

	public function getResource($name) {
		$objects = $this->get(null, $name, null, null);
		if (count($objects)) {
			return $this->getResponsePayload($this->object2responseVo($objects[0]));
		} else {
			return $this->getResponsePayload(array());
		}
	}

	protected function getDictionarys($offset = null, $limit = null) {
		return $this->get('0',null,$offset,$limit);
	}

	protected function getParallelTexts($offset = null, $limit = null) {
		return $this->get('1',null,$offset,$limit);
	}

	protected function getQAResources($offset = null, $limit = null) {
		return $this->get('2',null,$offset,$limit);
	}

	protected function getGlossaryResources($offset = null, $limit = null) {
		return $this->get('3',null,$offset,$limit);
	}

	protected function getTranslationTemplateResources($offset = null, $limit = null) {
		return $this->get('4',null,$offset,$limit);
	}

	protected function getAnyResources($offset = null, $limit = null) {
		return $this->get(null,null,$offset,$limit);
	}

	protected function get($typeId, $name = null, $offset = null, $limit = null) {
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('delete_flag', '0'));
		if ($typeId != null) {
			$mCriteria->add(new Criteria('type_id', $typeId));
		}
		if ($name != null) {
			$mCriteria->add(new Criteria('dictionary_name', $name));
		}

		$objects =& $this->m_handler->getList($mCriteria, $limit, $offset, false, true, false);

		if (count($objects)) {
			return $objects;
		}
		return array();
	}

	public function search($word, $matchingMethod, $type, $languages = array(), $offset = null, $limit = null) {
		if (empty($word)) {
			return $this->getErrorResponsePayload('search word is empty.');
		}
		if (empty($matchingMethod)) {
			return $this->getErrorResponsePayload('matchingMethod is empty.');
		}

		$mCriteria =& new CriteriaCompo();

		switch (strtoupper($type)) {
			case 'DICTIONARY':
				$mCriteria->add(new Criteria('type_id', 0));
				break;
			case 'PARALLELTEXT';
				$mCriteria->add(new Criteria('type_id', 1));
				break;
			case 'QA';
				$mCriteria->add(new Criteria('type_id', 2));
				break;
			case 'GLOSSARY';
				$mCriteria->add(new Criteria('type_id', 3));
				break;
			case 'TRANSLATION_TEMPLATE';
				$mCriteria->add(new Criteria('type_id', 4));
				break;
			default:
				break;
		}

		$mCriteria->add(new Criteria('delete_flag', '0'));

		$criteria = null;
		switch (strtoupper($matchingMethod)) {
			case 'COMPLETE':
				$criteria =& new Criteria('dictionary_name', $word);
				break;
			case 'PREFIX':
				$criteria =& new Criteria('dictionary_name', $word.'%', 'LIKE');
				break;
			case 'PARTIAL':
				$criteria =& new Criteria('dictionary_name', '%'.$word.'%', 'LIKE');
				break;
			case 'SUFFIX':
				$criteria =& new Criteria('dictionary_name', '%'.$word, 'LIKE');
				break;
			default:
				return $this->getErrorResponsePayload('matchingMethod is not supported type. ['.$matchingMethod.']');
				break;
		}
		$mCriteria->add($criteria);

		if (count($languages)) {
			$langCri = new CriteriaCompo();
			foreach ($languages as $language) {
				$langCri->add(new Criteria('C.language', $language), 'OR');
			}
			$mCriteria->add($langCri);
		}

		$objects =& $this->m_handler->search($mCriteria, $offset, $limit);
		if (count($objects)) {
			$ary = array();
			foreach ($objects as $object) {
				$ary[] = $this->object2responseVo($object);
			}
			return $this->getResponsePayload($ary);
		} else {
			return $this->getErrorResponsePayload("0 hit.");
		}
	}
}
?>