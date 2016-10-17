<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
require_once dirname(__FILE__).'/../util/pager.php';

class BBSAjaxPullMessagePage {

	const MAX_POST_LIMIT = '20';

	private $topicId;
	private $offset;
	private $limit;
	private $timestamp;
	private $maxTimestamp;

	private $errorMessage;

	/**
	 * Constructor
	 * @param int $topicId
	 * @param int $offset
	 * @param int $limit
	 * @param int $timestamp
	 */
	public function __construct($topicId = 0, $offset = 0, $limit = 0, $timestamp = 0) {
		$this->topicId = intval($topicId);
		$this->offset = intval($offset);
		$this->limit = intval($limit);
		$this->timestamp = intval($timestamp);
		$this->maxTimestamp = $this->timestamp;
		$this->errorMessage = '';
	}

	/**
	 *
	 * @return boolean
	 */
	public function validate() {
		if ($this->topicId <= 0) {
			$this->errorMessage = 'Topic id is invalid.';
		}
		if ($this->offset < 0) {
			$this->errorMessage = 'Offset is invalid.';
		}
		if ($this->limit <= 0) {
			$this->errorMessage = 'Limit is invalid.';
		}
		if ($this->timestamp < 0) {
			$this->errorMessage = 'Timestamp is invalid.';
		}

		return ($this->errorMessage == '');
	}

	/**
	 *
	 * @return String
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 *
	 * @return
	 */
	public function getPosts() {
		$return = array();
		$client = new PostManager();
		$posts = $client->getPosts($this->topicId, $this->offset, self::MAX_POST_LIMIT);
		$result = array();
		foreach ($posts as $post) {
			if ($post['updateTime'] > $this->timestamp && $post['order'] <=  $this->offset + self::MAX_POST_LIMIT) {
				$result[] =$post;
				$this->maxTimestamp = max($this->maxTimestamp, $post['updateTime']);
			}
		}
		return $result;
	}

	/**
	 *
	 * @return int
	 */
	public function getMaxTimestamp() {
		return $this->maxTimestamp;
	}

	/**
	 *
	 * @return String
	 */
	public function getPager($result) {
		$currentPage = $this->offset / $this->limit + 1;
		$pager = new Pager(array(
			'currentPage' => $currentPage,
			'perPage' => $this->limit,
			'totalItems' => $result
		));

		return $pager->toHtml($this->topicId);
	}

	/**
	 *
	 */
	public function getResults() {
		$client = new PostManager();
		$result = $client->getPostsCount($this->topicId);
		return $result;
	}
}
?>