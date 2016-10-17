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
require_once dirname(__FILE__).'/../util/user.php';
require_once(XOOPS_TRUST_PATH.'/libs/altsys/class/D3LanguageManager.class.php');

class Category extends AbstractModel {

	private $id;
	private $post_uid;
	private $title;
	private $description;
	private $user;
	private $auth;

	public function __construct($params) {
		parent::__construct();
		$this->params = $params;
		$this->id = $params['cat_id'];
		if (!isset($params['title']) || $params['title'] == "") {
			$this->title = _MD_D3FORUM_NO_TRANSLATION;
		} else {
			$this->title = $params['title'];
		}
		$this->description = $params['description'];
				$this->originalLanguage = $params['cat_original_language'];

		if (isset($params['uid'])) {
			$this->user = new User($params['uid']);
		}
		
		if ($params['cat_last_post_time'] > 0) {
			$this->params['cat_last_post_time_f'] = formatTimestamp($params['cat_last_post_time'], 'm');
			$this->setLastPostTime($params['cat_last_post_time']);
		} else {
			$this->params['cat_last_post_time_f'] = "";
		}
				
		$this->post_uid = $params['post_uid'];
	}
	public function getUser() {
		return $this->user;
	}
	public function getParams() {
		return $this->params;
	}
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	

	
	public function getTitle() {
		return $this->title;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setCategoryForumsCount ($var) {
		$this->params['cat_forums_count'] = $var;
	}
	public function setCategoryTopicsCount ($var) {
		$this->params['cat_topics_count'] = $var;
	}
	public function setCategoryPostsCount ($var) {
		$this->params['cat_posts_count'] = $var;
	}
	public function setTopicLastPostTime ($var) {
		if ($var > 0) {
			$this->params['cat_last_post_time_f'] = formatTimestamp($var, 'm');
//			$this->params['cat_last_post_time_f'] = date('Y-m-d H:i:s(T)', $var);
		} else {
			$this->params['cat_last_post_time_f'] = "";
		}
	}
	public function setPostUname ($var) {
		$this->params['post_uname'] = $var;
	}
	public function setPostUid ($var) {
		$this->params['post_uid'] = $var;
		$this->post_uid = $var;
	}
	public function getPostUid ($var) {
		return $this->post_uid;
	}
	public function getUid() {
		return $this->user->getId();
	}

	public function getOriginal() {
		$sql  = ' SELECT title, description FROM %s ';
		$sql .= ' WHERE cat_id = %d AND `language_code` = \'%s\' ';

		$sql = sprintf($sql, $this->db->prefix(
				$this->moduleName.'_categories_body'), $this->id,
				$this->originalLanguage);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		$this->original = array();
		if ($row = $this->db->fetchArray($result)) {
			$this->original['title'] = $row['title'];
			$this->original['description'] = $row['description'];
		}

		return $this->original;
	}
	public function getOriginalTitle() {
		if (!$this->original) {
			$this->getOriginal();
		}
		return $this->original['title'];
	}
	public function getOriginalDescription() {
		if (!$this->original) {
			$this->getOriginal();
		}
		return $this->original['description'];
	}
	public function setOriginalLanguage($language) {
		$this->language = $language;
	}
	public function getOriginalLanguage() {
		return $this->language;
	}
	public function getAuth() {
		return $this->auth;
	}
	public function setAuth($auth) {
		$this->auth = $auth;
	}
}
?>