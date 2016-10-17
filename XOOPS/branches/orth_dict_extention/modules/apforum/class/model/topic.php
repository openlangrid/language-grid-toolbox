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
require_once dirname(__FILE__).'/abstract-model.php';

class Topic extends AbstractModel {

	private $id;
	private $title;
	private $description;
	private $auth;
	private $user;
	private $uid;
	public function __construct($params) {
		parent::__construct();
		$selectedLanguageTag = $this->languageManager->getSelectedLanguage();

		$this->id = $params['topic_id'];
		$this->forumId = $params['forum_id'];
//		$this->title = $params['topic_title_'.$selectedLanguageTag];
		if (!isset($params['title']) || $params['title'] == "") {
			$this->title = _MD_D3FORUM_NO_TRANSLATION;
		} else {
			$this->title = $params['title'];
		}
		$this->language = $params['topic_original_language'];
		if (isset($params['uid'])) {
			$this->user = new User($params['uid']);
		}
		$this->uid = $params['uid'];
		$this->params = $params;
		if (isset($params['topic_last_post_time'])) {
			//$this->params['topic_last_post_time_f'] = date('Y-m-d H:i:s(T)', $params['topic_last_post_time']);
			$this->params['topic_last_post_time_f'] = formatTimestamp($params['topic_last_post_time'], 'm');
			$this->setLastPostTime($params['topic_last_post_time']);
		}else{
			$this->params['topic_last_post_time_f'] = "";
		}
		if (isset($params['topic_create_time'])) {
			$this->setTopicCreateTime($params['topic_create_time']);
		}else{
			$this->params['topic_create_time_f'] = "";
		}
	}
	public function getParams() {
		return $this->params;
	}
	public function getId() {
		return $this->id;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getUser() {
		return $this->user;
	}
	public function getForumId() {
		return $this->forumId;
	}
	public function setTopicLastPostTime ($var) {
		if ($var > 0) {
			$this->params['topic_last_post_time_f'] = formatTimestamp($var, 'm');
		} else {
			$this->params['topic_last_post_time_f'] = "";
		}
//		$this->params['topic_last_post_time_f'] = date('Y-m-d H:i:s(T)', $var);
	}
	public function setUname ($var) {
		$this->params['uname'] = $var;
	}
	public function setUid ($var) {
		$this->params['uid'] = $var;
		$this->uid = $var;
	}
	public function getUid(){
		return $this->uid;
	}

	public function setCount($count) {
		$this->params['topic_posts_count'] = intval($count);
	}
	public function setBody($body) {
		$this->body = $body;
	}
	public function getBody($count) {
		return $this->body;
	}
	public function setOriginalLanguage($language) {
		$this->language = $language;
	}
	public function getOriginalLanguage() {
		return $this->language;
	}
		public function setAuthorId($var) {
		$this->prams['author_id'] = $var;
	}
	public function setAuthorName($var) {
		$this->params['author_name'] = $var;

	}
	public function setTopicCreateTime($var) {
		if ($var > 0) {
			$this->params['topic_create_time_f'] = formatTimestamp($var, 'm');
		}else{
			$this->params['topic_create_time_f'] = "";
		}


	}
	public function setAuth($auth){
		$this->auth = $auth;
	}
	public function getAuth(){
		return $this->auth;
	}
	

	
}
?>