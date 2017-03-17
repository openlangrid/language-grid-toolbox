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

class Toolbox_BBS_ForumGetAllManager extends Toolbox_BBS_AbstractManager {

	public function __construct($modname) {
		parent::__construct($modname);
	}

	function getForumList($categoryId, $offset = null, $limit = null) {

		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('cat_id', $categoryId));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$objects =& $this->m_forumHandler->getObjects($mCriteria, $limit, $offset);

		$forumArray = array();

		foreach ($objects as &$object) {
			$forumArray[] = $this->forumObject2responseVO($object);
		}

		return $this->getResponsePayload($forumArray);
	}

	function searchForum($word, $language = null, $matchingMethod = null, $scope = null, $offset = null, $limit = null) {
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

		$forumArray = array();
		$objects =& $this->m_forumBodyHandler->getObjects($mCriteria, $limit, $offset);
		if (count($objects)) {
			foreach ($objects as $object) {
				$forumObject =& $this->m_forumHandler->get($object->get('forum_id'));
				if($forumObject->get('delete_flag') == 0){
					$forumArray[] =& $this->forumObject2responseVo($forumObject);
				}
			}
		}

		if (count($forumArray) == 0) {
			//return $this->getErrorResponsePayload("search result is 0 hit.");
			return $this->getResponsePayload($forumArray);
		}else{
			return $this->getResponsePayload($forumArray);
		}
	}

}
?>