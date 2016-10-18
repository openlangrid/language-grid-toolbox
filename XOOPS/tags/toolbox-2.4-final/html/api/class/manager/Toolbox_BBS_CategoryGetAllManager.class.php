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

require_once(dirname(__FILE__).'/Toolbox_BBS_AbstractManager.class.php');

class Toolbox_BBS_CategoryGetAllManager extends Toolbox_BBS_AbstractManager {

	public function __construct($modname) {
		parent::__construct($modname);
	}

	function getCategoryList($offset = null, $limit = null) {

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$objects =& $this->m_categoryHandler->getObjects($mCriteria, $limit, $offset);

		$categoryArray = array();

		foreach ($objects as &$object) {
			$categoryArray[] = $this->categoryObject2responseVo($object);
		}

		return $this->getResponsePayload($categoryArray);
	}

	function searchCategory($word, $language = null, $matchingMethod = null, $scope = null, $offset = null, $limit = null) {
		if (empty($word)) {
			return $this->getErrorResponsePayload("search word is empty.");
		}
		if ($matchingMethod == null) {
			$matchingMethod = 'prefix';
		}
		if ($scope == null) {
			$scope = 'title';
		}

		$mCriteria =& new CriteriaCompo();
		if($language != null){
			$mCriteria->add(new Criteria('language_code', $language));
		}
		$fieldName = "";
		switch (strtoupper($scope)) {
			case "TITLE":
				$fieldName = "title";
				break;
			case "DESCRIPTION":
				$fieldName = "description";
				break;
			default:
				return $this->getErrorResponsePayload("No supported scope paramter [$scope]");
				break;
		}

		$criteria = null;
		switch (strtoupper($matchingMethod)) {
			case 'COMPLETE':
				$criteria =& new Criteria($fieldName, $word);
				break;
			case 'PREFIX':
				$criteria =& new Criteria($fieldName, $word.'%', 'LIKE');
				break;
			case 'PARTIAL':
				$criteria =& new Criteria($fieldName, '%'.$word.'%', 'LIKE');
				break;
			case 'SUFFIX':
				$criteria =& new Criteria($fieldName, '%'.$word, 'LIKE');
				break;
			default:
				return $this->getErrorResponsePayload('matchingMethod is not supported type. ['.$matchingMethod.']');
				break;
		}
		$mCriteria->add($criteria);

		$categoryArray = array();
		$objects =& $this->m_categoryBodyHandler->getObjects($mCriteria, $limit, $offset);
		if (count($objects)) {
			foreach ($objects as $object) {
				$categoryObject =& $this->m_categoryHandler->get($object->get('cat_id'));
				if($categoryObject->get('delete_flag') == 0){
					$categoryArray[] =& $this->categoryObject2responseVo($categoryObject);
				}
			}
		}

		if(count($categoryArray) == 0){
			//return $this->getErrorResponsePayload("search result is 0 hit.");
			return $this->getResponsePayload($categoryArray);
		}else{
			return $this->getResponsePayload($categoryArray);
		}
	}

}
?>