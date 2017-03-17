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

class Toolbox_BBS_PostGetAllManager extends Toolbox_BBS_AbstractManager {

	var $mModName = "";

	public function __construct($modname) {
		parent::__construct($modname);
		$this->mModName = $modname;
	}

	function getPostMessage($messageId) {
		if (!$messageId) {
			return $this->getErrorResponsePayload('invalid parameter.');
		}
		$a = $this->m_postHandler->get($messageId);
		if ($a) {
			return $this->getResponsePayload($this->postObject2responseVO($a));
		} else {
			return $this->getErrorResponsePayload('post message is not found. [$messageId = '.$messageId.']');
		}
	}

	function getPostList($topicId, $offset = null, $limit = null) {

		$mCriteria = new CriteriaCompo();
		$mCriteria->add(new Criteria('topic_id', $topicId));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$objects =& $this->m_postHandler->getObjects($mCriteria, $limit, $offset);

		$postArray = array();

		foreach ($objects as &$object) {
			$postArray[] = $this->postObject2responseVO($object);
		}

		return $this->getResponsePayload($postArray);
	}

	function getUpdatedPostLimit($topicId, $timestamp) {
		$mCriteria = new CriteriaCompo();
		if ($topicId) {
			$mCriteria->add(new Criteria('topic_id', $topicId));
		}
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$objects =& $this->m_postHandler->getObjects($mCriteria, null, null);

		$postArray = array();
		foreach ($objects as &$object) {
			$post = $this->postObject2responseVO($object);
			if ($post->date <= $timestamp) {
				continue;
			}
			$postArray[] = $post;
		}

		return $this->getResponsePayload($postArray);
	}

	function searchPost($word, $language = null, $matchingMethod = null, $scope = null, $offset = null, $limit = null) {
		if (empty($word)) {
			return $this->getErrorResponsePayload("search word is empty.");
		}

		if ($matchingMethod == null) {
			$matchingMethod = 'prefix';
		}
		if ($scope == null) {
			$scope = 'body';
		}

		$mCriteria = new CriteriaCompo();

		$fieldName = "";
		switch (strtoupper($scope)) {
			case "BODY":
				$fieldName = "description";
				break;
			case "CREATORNAME":
				$fieldName = "name";
				break;
			default:
				return $this->getErrorResponsePayload("No supported scope paramter [$scope]");
				break;
		}

		$criteria = null;
		switch (strtoupper($matchingMethod)) {
			case 'COMPLETE':
				$criteria = new Criteria($fieldName, $word);
				break;
			case 'PREFIX':
				$criteria = new Criteria($fieldName, $word.'%', 'LIKE');
				break;
			case 'PARTIAL':
				$criteria = new Criteria($fieldName, '%'.$word.'%', 'LIKE');
				break;
			case 'SUFFIX':
				$criteria = new Criteria($fieldName, '%'.$word, 'LIKE');
				break;
			default:
				return $this->getErrorResponsePayload('matchingMethod is not supported type. ['.$matchingMethod.']');
				break;
		}
		$mCriteria->add($criteria);

		if(strtoupper($scope) == "CREATORNAME"){
			//$objects =& $this->m_userHandler->getObjects($mCriteria);
			//if (count($objects) == 0) {
			//	return $this->getErrorResponsePayload(array());
			//}
			$userTable = $this->db->prefix('users');
			$postTable = $this->db->prefix($this->mModName.'_posts');
			$where = " `name` ";
			switch (strtoupper($matchingMethod)) {
				case 'COMPLETE': $where .= "  =   ".$this->db->quoteString($word)." ";      break;
				case 'PREFIX'  : $where .= " LIKE ".$this->db->quoteString($word."%")." ";  break;
				case 'PARTIAL' : $where .= " LIKE ".$this->db->quoteString("%".$word."%")." "; break;
				case 'SUFFIX'  : $where .= " LIKE ".$this->db->quoteString("%".$word)." ";  break;
			}
			$sql = "";
			$sql .= " SELECT DISTINCT `post_id` ";
			$sql .= " FROM   `".$postTable."` ";
			$sql .= " WHERE  `delete_flag` = '0' ";
			$sql .= " AND    `uid` in (SELECT uid FROM `".$userTable."` WHERE ".$where." AND `delete_flag` = '0') ";

			unset($mCriteria);
			$mCriteria = new CriteriaCompo();

			$result = $this->db->query($sql);
			if ($result) {
				while($row = $this->db->fetchArray($result)) {
					$mCriteria->add(new Criteria('post_id', $row['post_id']), 'OR');
				}
			}else{
				return $this->getResponsePayload(array());
			}
		}
		$postArray = array();
		if ($language != null) {
			$mCriteria->add(new Criteria('language_code', $language));
		}
		$objects =& $this->m_postBodyHandler->getObjects($mCriteria, $limit, $offset);
		if (count($objects)) {
			foreach ($objects as $object) {
				$postObject =& $this->m_postHandler->get($object->get('post_id'));
				//if($postObject->get('delete_flag') == 0){
					$postArray[] =& $this->postObject2responseVo($postObject);
				//}
			}
		}

		if (count($postArray) == 0) {
			return $this->getResponsePayload($postArray);
			//return $this->getErrorResponsePayload("search result is 0 hit.");
		}else{
			return $this->getResponsePayload($postArray);
		}
	}

}
?>