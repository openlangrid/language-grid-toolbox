<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once dirname(__FILE__).'/abstract-manager.php';
require_once dirname(__FILE__).'/post-manager.php';
require_once dirname(__FILE__).'/../model/topic.php';
require_once dirname(__FILE__).'/../translation/translation.php';

class TopicManager extends AbstractManager {

	private $topics = array();
	private $CurPage;
	private $ParPage;
	private $SortKey;

	public function __construct() {
		parent::__construct();

		$this->CurPage = 0;
		$this->ParPage = 0;
		$this->SortKey = 0;
	}

	public function getTopics() {
		return $this->getTopicsByForumId($this->forumId);
	}

	/**
	 * @return Array
	 */
	public function getTopicsByForumId($forumId) {
		if (!$this->topics) {
			$topicsTable = $this->db->prefix($this->moduleName.'_topics');
			$topicsBodyTable = $this->db->prefix($this->moduleName.'_topics_body');
			$postsTable = $this->db->prefix($this->moduleName.'_posts');
			$usersTable = $this->db->prefix('users');

			$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

			$OrderSQL = "";
			$sortkey = intval($this->SortKey);
			if (!$sortkey) {
				$sortkey = 0;
			}
			$OrderSQL = ' ORDER BY ';
			switch($sortkey){
				case  1:
				case  2:$OrderSQL.='T2.title';break;
				case  3:
				case  4:$OrderSQL.='COALESCE(T3.post_count,0)';break;
				case  5:
				case  6:$OrderSQL.='T4.post_time';break;
				default:$OrderSQL.='T1.topic_id';break;
			}
			if($sortkey % 2 == 0){
				$OrderSQL .= ' ASC ';
			}else{
				$OrderSQL .= ' DESC ';
			}

			$page = intval($this->CurPage);
			if (!$page) $page = 0;

			$view = intval($this->ParPage);
			if (!$view) $view = 0;

			$sql  = '';
			$sql .= ' SELECT  ';
			$sql .= '  T1.forum_id, ';
			$sql .= '  T1.topic_id, ';
			$sql .= '  T2.title, ';
			//$sql .= '  T2.description, ';
			$sql .= '  T1.topic_original_language, ';
			//$sql .= '  T1.topic_last_post_time, ';
			$sql .= '  COALESCE(T3.post_count,0) AS topic_posts_count , ';
			$sql .= '  T4.uid, ';
			$sql .= '  T5.uname,  ';
			$sql .= '  T4.post_time AS topic_last_post_time ';
			$sql .= ' FROM '.$topicsTable.' T1  ';
			$sql .= ' LEFT JOIN ( ';
			$sql .= '   SELECT  ';
			$sql .= '    topic_id, ';
			$sql .= '    REPLACE(REPLACE(title, CHAR(13),\'\'),CHAR(10),\' \') AS title  ';
			//$sql .= '    REPLACE(REPLACE(description, CHAR(13),\'\'),CHAR(10),\' \') AS description  ';
			$sql .= '   FROM '.$topicsBodyTable.'  ';
			$sql .= '   WHERE `language_code` = \''.$this->languageManager->getSelectedLanguage().'\' ';
			$sql .= ' ) AS T2 USING(topic_id)  ';
			$sql .= ' LEFT JOIN ( ';
			$sql .= '   SELECT ';
			$sql .= '   topic_id, ';
			$sql .= '   COUNT(post_id) AS post_count, ';
			$sql .= '   MAX(post_id) AS max_pid  ';
			$sql .= '   FROM '.$postsTable.' ';
			$sql .= '   WHERE delete_flag = 0   ';
			$sql .= '   GROUP BY topic_id  ';
			$sql .= ' ) AS T3 USING(topic_id)  ';
			$sql .= ' LEFT JOIN ('.$postsTable.' AS T4  ';
			$sql .= '   LEFT JOIN( ';
			$sql .= '     SELECT  ';
			$sql .= '       uid, ';
			$sql .= '       CASE WHEN COALESCE(`name`, \'\') = \'\' THEN `uname` ELSE `name` END `uname`  ';
			$sql .= '     FROM '.$usersTable.'  ';
			$sql .= '   ) AS T5 USING(uid) ';
			$sql .= ' ) ';
			$sql .= ' ON T4.post_id = T3.max_pid  ';
			$sql .= ' WHERE T1.forum_id = ? ';
			$sql .= ' AND T1.delete_flag = 0 ';
			$sql .= $OrderSQL;
			if($page > 0 && $view > 0){
				$page--;
				$start = $page * $view;
				$sql .= ' LIMIT '.$view.' OFFSET '.$start.' ';
			}

			$this->db->prepare($sql);
			$this->db->bind_param('i', intval($forumId));
			$result = $this->db->execute();
			$this->topics = array();
			while ($row = $this->db->fetchArray($result)) {
				$topic = new Topic($row);
				$this->topics[] = $topic;
			}
		}


		return $this->topics;
	}

	public function setCurPage($var){
		$this->CurPage = intval($var);
	}

	public function setParPage($var){
		$this->ParPage = intval($var);
	}

	public function setSortKey($var){
		$this->SortKey = intval($var);
	}

	public function getTopicsCountByForumId($forumId) {
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');

		$ret = 0;
		$sql  = '';
		$sql .= ' SELECT COUNT(topic_id) as topic_count ';
		$sql .= ' FROM '.$topicsTable.' ';
		$sql .= ' WHERE forum_id = ? ';
		$sql .= ' AND delete_flag = 0 ';

		$this->db->prepare($sql);
		$this->db->bind_param('i', intval($forumId));
		$result = $this->db->execute();
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}else{
			if ($row = $this->db->fetchArray($result)) {
				$ret = $row['topic_count'];
			}
		}

		return $ret;
		//return count($this->getAllowedCategoryIds());
	}

	public function getTopic($topicId) {
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$topicsBodyTable = $this->db->prefix($this->moduleName.'_topics_body');
		$sl = $this->languageManager->getSelectedLanguage();
		$sql  = '';
		$sql .= ' SELECT * FROM '.$topicsTable.' ';
		$sql .= ' LEFT JOIN (SELECT * FROM '.$topicsBodyTable.' WHERE `language_code` = \''.$sl.'\') AS T1 USING (topic_id)';
		$sql .= ' WHERE topic_id = '.intval($topicId).' AND delete_flag = 0 ';
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		if ($row = $this->db->fetchArray($result)) {
			$topic = new Topic($row);
			$topic->setOriginalLanguage($row['topic_original_language']);
		}
		return $topic;
	}
	public function getOriginalTopic($topicId) {
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$topicsBodyTable = $this->db->prefix($this->moduleName.'_topics_body');
		$sql  = '';
		$sql .= ' SELECT * FROM '.$topicsTable.' T1 ';
		$sql .= ' LEFT JOIN (SELECT * FROM '.$topicsBodyTable.') AS T2 ON ';
		$sql .= ' T1.topic_id = T2.topic_id AND T1.topic_original_language = T2.language_code ';
		$sql .= ' WHERE T1.topic_id = '.intval($topicId).' AND delete_flag = 0 ';
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		if ($row = $this->db->fetchArray($result)) {
			$topic = new Topic($row);
		}
		return $topic;
	}

	public function modifyTopic($topicId, $title, $language, $userId) {
		$table = $this->db->prefix($this->moduleName.'_topics');
		$bodyTable = $this->db->prefix($this->moduleName.'_topics_body');
		$language = $this->languageManager->getSelectedLanguage();
		$userId = $this->root->mContext->mXoopsUser->get('uid');

		$id = intval($topicId);

		$sql  = '';
		$sql .= ' SELECT topic_id, title FROM '.$table.' AS T ';
		$sql .= '   LEFT JOIN ';
		$sql .= '     ( SELECT topic_id, title, description FROM '.$bodyTable.' WHERE `language_code` = \'%s\') AS T1 ';
		$sql .= '   USING (topic_id) ';
		$sql .= '   WHERE `topic_id` = \'%d\' ';

		$sql = sprintf($sql, $language, $id);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		$row = $this->db->fetchArray($result);
		if (!$row['topic_id']) {
			return false;
		}
		if ($row['title'] == $this->escape($title)) {
			return false;
		}
		$sql = 'DELETE FROM %s WHERE `topic_id` = \'%d\' AND `language_code` = \'%s\' ';
		$result = $this->db->query(sprintf($sql, $bodyTable, $id, $language));

		$sql  = '';
		$sql .= ' INSERT %s SET ';
		$sql .= '     `title` = \'%s\' ';
		$sql .= ' ,`topic_id` = %d , `language_code` = \'%s\'';
		$sql = sprintf($sql, $bodyTable, $this->escape($title), $id, $language);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		} else {
			$this->setLog($id
							, EnumBBSItemTypeCode::$topicTitle
							, $language, EnumProcessTypeCode::$modify
							, $title);
		}
		return;
	}
	public function deleteTopic($topicId) {
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$postsTable = $this->db->prefix($this->moduleName.'_posts');

		$sql  = '';
		$sql .= ' UPDATE ';
		$sql .= '         '.$topicsTable.' AS T1 ';
		$sql .= '   SET ';
		$sql .= '     T1.delete_flag = 1 ';
		$sql .= '   WHERE topic_id = %d ';
		$sql = sprintf($sql, $topicId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$sql  = '';
		$sql .= ' UPDATE ';
		$sql .= '         '.$topicsTable.' AS T2 ';
		$sql .= '           RIGHT JOIN ';
		$sql .= '             ( ';
		$sql .= '               '.$postsTable.' AS T3 ';
		$sql .= '              ) ';
		$sql .= '           USING(topic_id) ';
		$sql .= '   SET ';
		$sql .= '     T2.delete_flag = 1, ';
		$sql .= '     T3.delete_flag = 1 ';
		$sql .= '   WHERE topic_id = %d ';
		$sql = sprintf($sql, $topicId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
	}
}
?>